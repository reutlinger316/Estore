<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomerLoyaltyPointWallet;
use App\Models\Item;
use App\Models\LoyaltyPointSetting;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyRedeemRule;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'customer_id' => Auth::id(),
        ]);

        $cartItems = $cart->cartItems()->with('item.storeFront')->get();
        $cart->load('storeFront');

        $globalWallet = CustomerLoyaltyPointWallet::walletFor(Auth::id(), 'admin');
        $merchantWallet = null;
        $globalRedeemRules = LoyaltyRedeemRule::global()
            ->where('is_active', true)
            ->orderBy('discount_percent')
            ->get();
        $merchantRedeemRules = collect();

        if ($cart->storeFront && $cart->storeFront->merchant_id) {
            $merchantWallet = CustomerLoyaltyPointWallet::walletFor(
                Auth::id(),
                'merchant',
                $cart->storeFront->merchant_id
            );

            $merchantRedeemRules = LoyaltyRedeemRule::forMerchant($cart->storeFront->merchant_id)
                ->where('is_active', true)
                ->orderBy('discount_percent')
                ->get();
        }

        return view('customer.cart.index', compact(
            'cart',
            'cartItems',
            'globalWallet',
            'merchantWallet',
            'globalRedeemRules',
            'merchantRedeemRules'
        ));
    }

    public function add(Item $item)
    {
        $storeFrontId = $item->store_front_id;

        $cart = Cart::firstOrCreate(
            ['customer_id' => Auth::id()],
            ['store_front_id' => $storeFrontId]
        );

        if ($cart->store_front_id === null) {
            $cart->update([
                'store_front_id' => $storeFrontId,
            ]);
        }

        if ($cart->store_front_id != $storeFrontId) {
            return back()->withErrors([
                'cart' => 'You can only add items from one shop at a time. Please clear your cart first.',
            ]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'item_id' => $item->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    public function remove(CartItem $cartItem)
    {
        $cart = Cart::where('customer_id', Auth::id())->first();

        if (!$cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        if ($cart->cartItems()->count() === 0) {
            $cart->update([
                'store_front_id' => null,
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:takeaway,delivery',
            'delivery_zone' => 'required_if:order_type,delivery|nullable|in:inside,outside',
            'delivery_phone' => 'required_if:order_type,delivery|nullable|string|max:20',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string|max:1000',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'loyalty_owner_type' => 'nullable|in:admin,merchant',
            'loyalty_redeem_rule_id' => 'nullable|integer|exists:loyalty_redeem_rules,id',
        ]);

        $cart = Cart::firstOrCreate([
            'customer_id' => Auth::id(),
        ]);

        $cartItems = $cart->cartItems()->with('item')->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        if (!$cart->store_front_id) {
            return back()->withErrors([
                'cart' => 'Your cart is not linked to a shop.',
            ]);
        }

        $storeFront = $cart->storeFront;

        try {
            $order = DB::transaction(function () use ($request, $cart, $cartItems, $storeFront) {
                if ($request->order_type === 'delivery') {
                    Auth::user()->update([
                        'phone' => $request->delivery_phone,
                        'default_delivery_address' => $request->delivery_address,
                        'default_delivery_lat' => $request->delivery_lat,
                        'default_delivery_lng' => $request->delivery_lng,
                    ]);
                }

                $itemsTotal = 0;
                $preparedOrderItems = [];

                foreach ($cartItems as $cartItem) {
                    $item = $cartItem->item()->lockForUpdate()->first();

                    if ($item->stock_quantity < $cartItem->quantity) {
                        throw new \Exception("Sorry, '{$item->item_name}' only has {$item->stock_quantity} left in stock.");
                    }

                    $item->decrement('stock_quantity', $cartItem->quantity);

                    $originalPrice = (float) $item->price;
                    $discountPercent = (float) $item->discount;
                    $discountAmount = round(($originalPrice * $discountPercent) / 100, 2);
                    $discountedUnitPrice = max(round($originalPrice - $discountAmount, 2), 0);

                    $lineTotal = $discountedUnitPrice * $cartItem->quantity;
                    $itemsTotal += $lineTotal;

                    $preparedOrderItems[] = [
                        'item_id' => $item->id,
                        'quantity' => $cartItem->quantity,
                        'price' => $discountedUnitPrice,
                    ];
                }

                $deliveryFee = 0;
                $deliveryZone = null;

                if ($request->order_type === 'delivery') {
                    $deliveryZone = $request->delivery_zone;

                    if ($deliveryZone === 'inside') {
                        $deliveryFee = (float) $storeFront->inside_delivery_fee;
                    } else {
                        $deliveryFee = (float) $storeFront->outside_delivery_fee;
                    }
                }

                $pointsRedeemed = 0;
                $pointsDiscountAmount = 0;
                $pointsDiscountPercent = 0;
                $pointsOwnerType = null;
                $pointsMerchantId = null;

                if ($request->filled('loyalty_redeem_rule_id') && $request->filled('loyalty_owner_type')) {
                    $redeemRule = LoyaltyRedeemRule::where('id', $request->loyalty_redeem_rule_id)
                        ->where('owner_type', $request->loyalty_owner_type)
                        ->where('is_active', true)
                        ->firstOrFail();

                    if ($redeemRule->owner_type === 'merchant') {
                        if (!$storeFront->merchant_id || $redeemRule->merchant_id !== $storeFront->merchant_id) {
                            throw new \Exception('This merchant loyalty rule is not valid for the selected shop.');
                        }

                        $pointsMerchantId = $storeFront->merchant_id;
                    }

                    $wallet = CustomerLoyaltyPointWallet::where('customer_id', Auth::id())
                        ->where('owner_type', $redeemRule->owner_type)
                        ->where('merchant_id', $pointsMerchantId)
                        ->lockForUpdate()
                        ->first();

                    if (!$wallet || $wallet->points < $redeemRule->points_required) {
                        throw new \Exception('You do not have enough loyalty points for this discount.');
                    }

                    $pointsRedeemed = $redeemRule->points_required;
                    $pointsDiscountPercent = (float) $redeemRule->discount_percent;
                    $pointsDiscountAmount = round(($itemsTotal * $pointsDiscountPercent) / 100, 2);
                    $pointsDiscountAmount = min($pointsDiscountAmount, $itemsTotal);
                    $pointsOwnerType = $redeemRule->owner_type;

                    $wallet->decrement('points', $pointsRedeemed);
                }

                $subtotalBeforePoints = $itemsTotal + $deliveryFee;
                $grandTotal = max(($itemsTotal - $pointsDiscountAmount) + $deliveryFee, 0);

                $globalPointsEarned = 0;
                $merchantPointsEarned = 0;

                $globalSetting = LoyaltyPointSetting::globalSetting();
                if ($globalSetting) {
                    $globalPointsEarned = $globalSetting->calculatePoints($itemsTotal);
                }

                $merchantSetting = $storeFront->merchant_id
                    ? LoyaltyPointSetting::merchantSetting($storeFront->merchant_id)
                    : null;

                if ($merchantSetting) {
                    $merchantPointsEarned = $merchantSetting->calculatePoints($itemsTotal);
                }

                $order = Order::create([
                    'customer_id' => Auth::id(),
                    'store_front_id' => $cart->store_front_id,
                    'receipt_number' => 'RCPT-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6)),
                    'receipt_generated_at' => now(),
                    'total_amount' => $grandTotal,
                    'subtotal_before_points' => $subtotalBeforePoints,
                    'points_redeemed' => $pointsRedeemed,
                    'points_discount_amount' => $pointsDiscountAmount,
                    'points_discount_percent' => $pointsDiscountPercent,
                    'points_owner_type' => $pointsOwnerType,
                    'points_merchant_id' => $pointsMerchantId,
                    'global_points_earned' => $globalPointsEarned,
                    'merchant_points_earned' => $merchantPointsEarned,
                    'status' => 'pending',
                    'type' => $request->order_type,
                    'delivery_zone' => $request->order_type === 'delivery' ? $deliveryZone : null,
                    'delivery_fee' => $request->order_type === 'delivery' ? $deliveryFee : 0,
                    'delivery_phone' => $request->order_type === 'delivery' ? $request->delivery_phone : null,
                    'delivery_address' => $request->order_type === 'delivery' ? $request->delivery_address : null,
                    'delivery_lat' => $request->order_type === 'delivery' ? $request->delivery_lat : null,
                    'delivery_lng' => $request->order_type === 'delivery' ? $request->delivery_lng : null,
                ]);

                foreach ($preparedOrderItems as $orderItemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $orderItemData['item_id'],
                        'quantity' => $orderItemData['quantity'],
                        'price' => $orderItemData['price'],
                    ]);
                }

                if ($pointsRedeemed > 0) {
                    LoyaltyPointTransaction::create([
                        'customer_id' => Auth::id(),
                        'order_id' => $order->id,
                        'owner_type' => $pointsOwnerType,
                        'merchant_id' => $pointsMerchantId,
                        'type' => 'redeemed',
                        'points' => -$pointsRedeemed,
                        'description' => 'Redeemed points for order #' . $order->id,
                    ]);
                }

                if ($globalPointsEarned > 0) {
                    $globalWallet = CustomerLoyaltyPointWallet::walletFor(Auth::id(), 'admin');
                    $globalWallet->increment('points', $globalPointsEarned);

                    LoyaltyPointTransaction::create([
                        'customer_id' => Auth::id(),
                        'order_id' => $order->id,
                        'owner_type' => 'admin',
                        'merchant_id' => null,
                        'type' => 'earned',
                        'points' => $globalPointsEarned,
                        'description' => 'Earned global loyalty points from order #' . $order->id,
                    ]);
                }

                if ($merchantPointsEarned > 0 && $storeFront->merchant_id) {
                    $merchantWallet = CustomerLoyaltyPointWallet::walletFor(Auth::id(), 'merchant', $storeFront->merchant_id);
                    $merchantWallet->increment('points', $merchantPointsEarned);

                    LoyaltyPointTransaction::create([
                        'customer_id' => Auth::id(),
                        'order_id' => $order->id,
                        'owner_type' => 'merchant',
                        'merchant_id' => $storeFront->merchant_id,
                        'type' => 'earned',
                        'points' => $merchantPointsEarned,
                        'description' => 'Earned merchant loyalty points from order #' . $order->id,
                    ]);
                }

                $cart->cartItems()->delete();

                $cart->update([
                    'store_front_id' => null,
                ]);

                return $order;
            });
        } catch (\Exception $exception) {
            return back()->withErrors([
                'cart' => $exception->getMessage(),
            ])->withInput();
        }

        return redirect()
            ->route('customer.receipts.show', $order)
            ->with('success', 'Order placed successfully. Receipt generated.');
    }
}
