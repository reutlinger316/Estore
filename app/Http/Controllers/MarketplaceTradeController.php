<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceTrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketplaceTradeController extends Controller
{
    public function start(Request $request, MarketplaceProduct $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'buyer_offer_price' => ['required', 'numeric', 'min:1'],
            'buyer_message' => ['nullable', 'string', 'max:1000'],
        ]);

        $buyerId = Auth::id();

        if ($product->seller_id === $buyerId) {
            return back()->with('error', 'You cannot bargain for your own product.');
        }

        try {
            DB::transaction(function () use ($product, $validated, $buyerId) {
                $lockedProduct = MarketplaceProduct::where('id', $product->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$lockedProduct->is_active) {
                    throw new \Exception('This product is not available.');
                }

                if ($lockedProduct->stock < $validated['quantity']) {
                    throw new \Exception('Not enough stock available.');
                }

                $activeTrade = MarketplaceTrade::where('marketplace_product_id', $lockedProduct->id)
                    ->whereIn('status', ['pending', 'countered', 'accepted'])
                    ->lockForUpdate()
                    ->first();

                if ($activeTrade) {
                    throw new \Exception('Another customer is already bargaining for this product. Please try again later.');
                }

                MarketplaceTrade::create([
                    'marketplace_product_id' => $lockedProduct->id,
                    'buyer_id' => $buyerId,
                    'seller_id' => $lockedProduct->seller_id,
                    'quantity' => $validated['quantity'],
                    'original_price' => $lockedProduct->price,
                    'buyer_offer_price' => $validated['buyer_offer_price'],
                    'status' => 'pending',
                    'buyer_message' => $validated['buyer_message'] ?? null,
                ]);
            });

            return redirect()
                ->route('customer.marketplace.my-trades')
                ->with('success', 'Bargain request sent to seller.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function buyNow(Request $request, MarketplaceProduct $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $buyerId = Auth::id();

        if ($product->seller_id === $buyerId) {
            return back()->with('error', 'You cannot buy your own product.');
        }

        try {
            DB::transaction(function () use ($product, $validated, $buyerId) {
                $lockedProduct = MarketplaceProduct::where('id', $product->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $activeTrade = MarketplaceTrade::where('marketplace_product_id', $lockedProduct->id)
                    ->whereIn('status', ['pending', 'countered', 'accepted'])
                    ->lockForUpdate()
                    ->first();

                if ($activeTrade) {
                    throw new \Exception('Another customer is already bargaining for this product. Buying is locked for now.');
                }

                $buyer = User::where('id', $buyerId)->lockForUpdate()->firstOrFail();
                $seller = User::where('id', $lockedProduct->seller_id)->lockForUpdate()->firstOrFail();

                if (!$lockedProduct->is_active) {
                    throw new \Exception('This product is not available.');
                }

                if ($lockedProduct->stock < $validated['quantity']) {
                    throw new \Exception('Not enough stock available.');
                }

                $unitPrice = $lockedProduct->price;
                $totalPrice = $unitPrice * $validated['quantity'];

                if ($buyer->balance < $totalPrice) {
                    throw new \Exception('Insufficient balance.');
                }

                $buyer->balance -= $totalPrice;
                $buyer->save();

                $seller->balance += $totalPrice;
                $seller->save();

                $lockedProduct->stock -= $validated['quantity'];

                if ($lockedProduct->stock <= 0) {
                    $lockedProduct->stock = 0;
                    $lockedProduct->is_active = false;
                }

                $lockedProduct->save();

                MarketplaceOrder::create([
                    'marketplace_trade_id' => null,
                    'marketplace_product_id' => $lockedProduct->id,
                    'buyer_id' => $buyer->id,
                    'seller_id' => $seller->id,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                ]);
            });

            return redirect()
                ->route('customer.marketplace.purchases')
                ->with('success', 'Product purchased successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function myTrades()
    {
        $trades = MarketplaceTrade::with(['product', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.trades.my_trades', compact('trades'));
    }

    public function sellerTrades()
    {
        $trades = MarketplaceTrade::with(['product', 'buyer'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.trades.seller_trades', compact('trades'));
    }

    public function accept(MarketplaceTrade $trade)
    {
        if ($trade->seller_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($trade->status, ['pending', 'countered'])) {
            return back()->with('error', 'This trade cannot be accepted.');
        }

        $price = $trade->seller_counter_price ?? $trade->buyer_offer_price;

        $trade->update([
            'final_price' => $price,
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Trade accepted. Waiting for buyer payment.');
    }

    public function reject(MarketplaceTrade $trade)
    {
        if ($trade->seller_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($trade->status, ['pending', 'countered', 'accepted'])) {
            return back()->with('error', 'This trade cannot be rejected.');
        }

        $trade->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Trade rejected.');
    }

    public function counter(Request $request, MarketplaceTrade $trade)
    {
        if ($trade->seller_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'seller_counter_price' => ['required', 'numeric', 'min:1'],
            'seller_message' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!in_array($trade->status, ['pending', 'countered'])) {
            return back()->with('error', 'This trade cannot be countered.');
        }

        $trade->update([
            'seller_counter_price' => $validated['seller_counter_price'],
            'seller_message' => $validated['seller_message'] ?? null,
            'status' => 'countered',
        ]);

        return back()->with('success', 'Counter offer sent.');
    }

    public function acceptCounter(MarketplaceTrade $trade)
    {
        if ($trade->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($trade->status !== 'countered') {
            return back()->with('error', 'No counter offer available.');
        }

        $trade->update([
            'final_price' => $trade->seller_counter_price,
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Counter offer accepted. You can now complete payment.');
    }

    public function cancel(MarketplaceTrade $trade)
    {
        if ($trade->buyer_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($trade->status, ['pending', 'countered', 'accepted'])) {
            return back()->with('error', 'This trade cannot be cancelled.');
        }

        $trade->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Trade cancelled.');
    }

    public function complete(MarketplaceTrade $trade)
    {
        if ($trade->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($trade->status !== 'accepted') {
            return back()->with('error', 'Seller must accept the trade before payment.');
        }

        try {
            DB::transaction(function () use ($trade) {
                $lockedTrade = MarketplaceTrade::where('id', $trade->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedTrade->status !== 'accepted') {
                    throw new \Exception('This trade is no longer payable.');
                }

                $product = MarketplaceProduct::where('id', $lockedTrade->marketplace_product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $buyer = User::where('id', $lockedTrade->buyer_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $seller = User::where('id', $lockedTrade->seller_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$product->is_active) {
                    throw new \Exception('This product is not available.');
                }

                if ($product->stock < $lockedTrade->quantity) {
                    throw new \Exception('Not enough stock available.');
                }

                $unitPrice = $lockedTrade->final_price;
                $totalPrice = $unitPrice * $lockedTrade->quantity;

                if ($buyer->balance < $totalPrice) {
                    throw new \Exception('Insufficient balance.');
                }

                $buyer->balance -= $totalPrice;
                $buyer->save();

                $seller->balance += $totalPrice;
                $seller->save();

                $product->stock -= $lockedTrade->quantity;

                if ($product->stock <= 0) {
                    $product->stock = 0;
                    $product->is_active = false;
                }

                $product->save();

                MarketplaceOrder::create([
                    'marketplace_trade_id' => $lockedTrade->id,
                    'marketplace_product_id' => $product->id,
                    'buyer_id' => $buyer->id,
                    'seller_id' => $seller->id,
                    'quantity' => $lockedTrade->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                ]);

                $lockedTrade->update([
                    'status' => 'completed',
                ]);
            });

            return redirect()
                ->route('customer.marketplace.purchases')
                ->with('success', 'Trade completed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function purchases()
    {
        $orders = MarketplaceOrder::with(['product', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.orders.purchases', compact('orders'));
    }

    public function sales()
    {
        $orders = MarketplaceOrder::with(['product', 'buyer'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.marketplace.orders.sales', compact('orders'));
    }
}