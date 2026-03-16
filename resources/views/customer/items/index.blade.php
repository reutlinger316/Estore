@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Available Items</h2>

        <div class="list-block">
            @forelse($items as $item)
                <div class="card">
                    <h3>{{ $item->item_name }}</h3>
                    <p><strong>Description:</strong> {{ $item->description }}</p>
                    <p><strong>Price:</strong> {{ $item->price }}</p>
                    <p><strong>Stock:</strong> {{ $item->stock_quantity }}</p>
                    <p><strong>Discount:</strong> {{ $item->discount }}</p>
                    <p><strong>Pre-order:</strong> {{ $item->is_pre_order ? 'Yes' : 'No' }}</p>
                    <p><strong>Store:</strong> {{ $item->storeFront->name ?? 'N/A' }}</p>
                    <p><strong>Branch:</strong> {{ $item->storeFront->branch_name ?? 'N/A' }}</p>
                </div>
            @empty
                <div class="card">
                    <p>No items available.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
