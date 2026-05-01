<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomerCombo;
use App\Models\CustomerComboItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerComboController extends Controller
{
    public function store(Request $request, StoreFront $storeFront)
    {
        if ($storeFront->confirmation_status !== 'accepted' || !$storeFront->allow_combos) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'nullable|integer|min:0',
        ]);

        $selectedItems = collect($request->items)
            ->filter(fn ($qty) => (int) $qty > 0);

        if ($selectedItems->isEmpty()) {
            return back()->withErrors([
                'combo' => 'Please select at least one item for the combo.',
            ])->withInput();
        }

        $validItems = $storeFront->items()
            ->whereIn('id', $selectedItems->keys())
            ->pluck('id')
            ->toArray();

        if (count($validItems) !== $selectedItems->count()) {
            return back()->withErrors([
                'combo' => 'Some selected items are invalid for this storefront.',
            ])->withInput();
        }

        DB::transaction(function () use ($request, $storeFront, $selectedItems) {
            $combo = CustomerCombo::create([
                'customer_id' => Auth::id(),
                'store_front_id' => $storeFront->id,
                'name' => $request->name,
            ]);

            foreach ($selectedItems as $itemId => $quantity) {
                CustomerComboItem::create([
                    'customer_combo_id' => $combo->id,
                    'item_id' => $itemId,
                    'quantity' => (int) $quantity,
                ]);
            }
        });

        return back()->with('success', 'Combo created successfully.');
    }

    public function orderNow(Request $request, CustomerCombo $combo)
    {
        if ($combo->customer_id !== Auth::id()) {
            abort(403);
        }

        $combo->load(['comboItems.item', 'storeFront']);

        if (!$combo->storeFront || !$combo->storeFront->allow_combos) {
            return back()->withErrors([
                'combo' => 'This storefront does not allow combo ordering right now.',
            ]);
        }

        $request->validate([
            'order_type' => 'required|in:takeaway,delivery',
            'delivery_zone' => 'required_if:order_type,delivery|nullable|in:inside,outside',
            'delivery_phone' => 'required_if:order_type,delivery|nullable|string|max:20',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string|max:1000',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
        ]);

        $storeFront = $combo->storeFront;

        $order = DB::transaction(function () use ($request, $combo, $storeFront) {
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

            foreach ($combo->comboItems as $comboItem) {
                $item = $comboItem->item()->lockForUpdate()->first();

                if (!$item || $item->store_front_id !== $storeFront->id) {
                    abort(422, 'One of the combo items is no longer available.');
                }

                if (!$item->is_pre_order && $item->stock_quantity < $comboItem->quantity) {
                    abort(422, "Sorry, '{$item->item_name}' only has {$item->stock_quantity} left in stock.");
                }

                if (!$item->is_pre_order) {
                    $item->decrement('stock_quantity', $comboItem->quantity);
                }

                $unitPrice = (float) $item->discounted_price;
                $lineTotal = $unitPrice * $comboItem->quantity;
                $itemsTotal += $lineTotal;

                $preparedOrderItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $comboItem->quantity,
                    'price' => $unitPrice,
                    'is_pre_order' => $item->is_pre_order,
                    'pre_order_available_on' => $item->pre_order_available_on,
                    'pre_order_note' => $item->pre_order_note,
                    'pre_order_status' => $item->is_pre_order ? 'pending' : 'normal',
                ];
            }

            $deliveryFee = 0;
            $deliveryZone = null;

            if ($request->order_type === 'delivery') {
                $deliveryZone = $request->delivery_zone;
                $deliveryFee = $deliveryZone === 'inside'
                    ? (float) $storeFront->inside_delivery_fee
                    : (float) $storeFront->outside_delivery_fee;
            }

            $order = Order::create([
                'customer_id' => Auth::id(),
                'store_front_id' => $storeFront->id,
                'receipt_number' => 'RCPT-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -6)),
                'receipt_generated_at' => now(),
                'total_amount' => $itemsTotal + $deliveryFee,
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
                    'is_pre_order' => $orderItemData['is_pre_order'],
                    'pre_order_available_on' => $orderItemData['pre_order_available_on'],
                    'pre_order_note' => $orderItemData['pre_order_note'],
                    'pre_order_status' => $orderItemData['pre_order_status'],
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('customer.receipts.show', $order)
            ->with('success', 'Combo ordered successfully.');
    }

    public function destroy(CustomerCombo $combo)
    {
        if ($combo->customer_id !== Auth::id()) {
            abort(403);
        }

        $combo->delete();

        return back()->with('success', 'Combo deleted successfully.');
    }
}