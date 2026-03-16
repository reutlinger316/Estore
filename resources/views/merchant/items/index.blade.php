@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Items for {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h2>

        <div class="actions">
            <a href="{{ route('merchant.items.create', $storeFront) }}" class="btn btn-primary">Add New Item</a>
            <a href="{{ route('merchant.storefronts.index') }}" class="btn btn-primary">Back to StoreFronts</a>
        </div>

        <div class="list-block">
            @forelse($items as $item)
                <div class="card">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" width="120"><br><br>
                    @endif
                    <h3>{{ $item->item_name }}</h3>
                    <p><strong>Description:</strong> {{ $item->description }}</p>
                    <p><strong>Price:</strong> {{ $item->price }}</p>
                    <p><strong>Stock:</strong> {{ $item->stock_quantity }}</p>
                    <p><strong>Discount:</strong> {{ $item->discount }}</p>
                    <p><strong>Pre-order:</strong> {{ $item->is_pre_order ? 'Yes' : 'No' }}</p>

                    <div class="actions">
                        <a href="{{ route('merchant.items.edit', ['storeFront' => $storeFront, 'item' => $item]) }}" class="btn btn-primary">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('merchant.items.destroy', ['storeFront' => $storeFront, 'item' => $item]) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this item?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No items added yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
