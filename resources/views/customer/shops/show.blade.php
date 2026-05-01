@extends('layouts.app')

@section('content')

    <div class="shop-hero">
        <div class="shop-hero-text">
            <h1>{{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>
            <p>Location: {{ $storeFront->location }}</p>
            <p>Explore our menu and discover amazing products!</p>
        </div>
        <div class="shop-hero-anim">
            <lottie-player src="{{ asset('animations/Ecommerce online shop blue.json') }}" background="transparent" speed="1" style="width: 160px; height: 160px;" loop autoplay></lottie-player>
        </div>
    </div>

    <div style="margin-bottom: 15px;">
        <a href="{{ route('customer.cart.index') }}">
            <button class="btn btn-primary">View My Cart ({{ $cartCount }})</button>
        </a>

        @if($cart && $cart->store_front_id)
            <p>
                Current cart shop:
                {{ $cart->storeFront?->name ?? 'N/A' }}
                - {{ $cart->storeFront?->branch_name ?? 'N/A' }}
            </p>
        @endif
    </div>

    <hr>

    @if($storeFront->allow_combos)
        <details class="marketplace-product-card" style="margin-bottom: 30px;">
            <summary class="marketplace-product-summary">
                <div class="marketplace-product-summary__left">
                    <h3>Create Your Custom Combo</h3>
                    <p>Select multiple items and quantities to group as a combo</p>
                </div>
                <span class="marketplace-product-chevron">▼</span>
            </summary>

            <div class="marketplace-product-body">
                <form method="POST" action="{{ route('customer.combos.store', $storeFront) }}">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Combo Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Example: My Lunch Combo" class="form-control" style="max-width: 400px;">
                    </div>

                    <p style="margin-bottom: 15px; font-weight: 600;">Select quantities for combo items:</p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-bottom: 20px;">
                        @foreach($items as $item)
                            <div style="background: var(--surface-strong); padding: 12px; border-radius: 12px; border: 1px solid var(--border-soft);">
                                <label style="display: block; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600;">
                                    {{ $item->item_name }} ({{ number_format($item->discounted_price, 2) }})
                                </label>
                                <input type="number" min="0" name="items[{{ $item->id }}]" value="{{ old('items.' . $item->id, 0) }}" class="form-control" style="width: 100%;">
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">Save Combo</button>
                </form>
            </div>
        </details>

        <hr>

        <details class="marketplace-product-card" style="margin-bottom: 30px;">
            <summary class="marketplace-product-summary">
                <div class="marketplace-product-summary__left">
                    <h3>My Saved Combos</h3>
                    <p>View and order your saved combos</p>
                </div>
                <span class="marketplace-product-chevron">▼</span>
            </summary>

            <div class="marketplace-product-body">
                @forelse($combos as $combo)
                    <div style="border: 1px solid var(--border-soft); padding: 20px; margin-bottom: 20px; border-radius: 12px; background: var(--surface-strong);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0; font-size: 1.2rem;">{{ $combo->name }}</h4>
                            <span style="font-weight: bold; color: var(--primary);">Total: {{ number_format($combo->calculated_total, 2) }}</span>
                        </div>

                        <p style="margin-bottom: 10px; font-weight: 600;">Items included:</p>
                        <ul style="margin-bottom: 20px; list-style: disc; margin-left: 20px;">
                        @foreach($combo->comboItems as $comboItem)
                            <li>
                                {{ $comboItem->item?->item_name ?? 'Deleted item' }}
                                - Qty: {{ $comboItem->quantity }}
                            </li>
                        @endforeach
                        </ul>

                        <form method="POST" action="{{ route('customer.combos.order-now', $combo) }}" style="margin-bottom: 15px;">
                            @csrf

                            <div class="marketplace-form-grid">
                                <div class="marketplace-form-card" style="grid-column: span 2;">
                                    <label>Order Type</label>
                                    <div style="display: flex; gap: 15px;">
                                        <label style="font-weight: normal; display: inline-flex; align-items: center; gap: 5px;">
                                            <input type="radio" name="order_type" value="takeaway" checked> Takeaway
                                        </label>
                                        <label style="font-weight: normal; display: inline-flex; align-items: center; gap: 5px;">
                                            <input type="radio" name="order_type" value="delivery"> Delivery
                                        </label>
                                    </div>
                                </div>

                                <div class="marketplace-form-card">
                                    <label>Delivery Zone</label>
                                    <select name="delivery_zone" class="form-control">
                                        <option value="">Select zone</option>
                                        <option value="inside">Inside {{ $storeFront->delivery_city }}</option>
                                        <option value="outside">Outside {{ $storeFront->delivery_city }}</option>
                                    </select>
                                </div>

                                <div class="marketplace-form-card">
                                    <label>Delivery Phone</label>
                                    <input type="text" name="delivery_phone" value="{{ auth()->user()->phone }}" class="form-control">
                                </div>

                                <div class="marketplace-form-card" style="grid-column: span 2;">
                                    <label>Delivery Address</label>
                                    <textarea name="delivery_address" class="form-control" style="min-height: 80px;">{{ auth()->user()->default_delivery_address }}</textarea>
                                </div>
                            </div>

                            <input type="hidden" name="delivery_lat" value="{{ auth()->user()->default_delivery_lat }}">
                            <input type="hidden" name="delivery_lng" value="{{ auth()->user()->default_delivery_lng }}">

                            <button type="submit" class="btn btn-primary">Order This Combo</button>
                        </form>

                        <form method="POST" action="{{ route('customer.combos.destroy', $combo) }}" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this combo?')">Delete Combo</button>
                        </form>
                    </div>
                @empty
                    <p>No combos created yet.</p>
                @endforelse
            </div>
        </details>

        <hr>
    @endif

    <h2>Menu</h2>

    @forelse($items as $item)
        @php
            $originalPrice = (float) $item->price;
            $discountPercent = (float) $item->discount;
            $discountAmount = round(($originalPrice * $discountPercent) / 100, 2);
            $discountedPrice = max(round($originalPrice - $discountAmount, 2), 0);
        @endphp

        <div>
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" width="140"><br><br>
            @endif

            <h3>{{ $item->item_name }}</h3>
            <p>{{ $item->description }}</p>

            <p>
                <strong>Price:</strong>
                @if($item->discount > 0)
                    <span style="text-decoration: line-through; color: #888;">
                        {{ number_format($item->price, 2) }}
                    </span>
                    <span style="color: green; font-weight: bold; margin-left: 6px;">
                        {{ number_format($discountedPrice, 2) }}
                    </span>
                @else
                    {{ number_format($item->price, 2) }}
                @endif
            </p>

            <p>Stock: {{ $item->stock_quantity }}</p>

            @if($item->discount > 0)
                <p>Discount: {{ number_format($item->discount, 2) }}%</p>
            @endif

            @if($item->averageRating())
                @php
                    $avg = round($item->averageRating(), 1);
                @endphp
                <p>
                    <strong>Item Rating:</strong>
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avg))
                            <span style="color: gold;">★</span>
                        @else
                            <span style="color: #ccc;">★</span>
                        @endif
                    @endfor
                    {{ $avg }}/5
                </p>
            @else
                <p><strong>Item Rating:</strong> No ratings yet</p>
            @endif

            <div style="display: flex; gap: 10px; align-items: center; margin-top: 10px; flex-wrap: wrap;">
                <form method="POST" action="{{ route('customer.cart.add', $item) }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>

                @auth
                    @if(!$item->reviews->where('customer_id', auth()->id())->count())
                        <a href="{{ route('customer.item-reviews.create', $item) }}" class="btn btn-primary">
                            Add Review
                        </a>
                    @endif
                @endauth

                <a href="{{ route('customer.item-reviews.index', $item) }}" class="btn btn-info">
                    See Item Reviews
                </a>
            </div>
        </div>

        <hr>
    @empty
        <p>No items available for this shop.</p>
    @endforelse

    <hr>

    <h2>Our Other Branches</h2>

    @forelse($otherBranches as $branch)
        <div>
            <a href="{{ route('customer.shops.show', $branch) }}">
                {{ $branch->name }} - {{ $branch->branch_name }}
            </a>
        </div>
    @empty
        <p>No other branches available.</p>
    @endforelse

@endsection