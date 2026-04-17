@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Send Restock Request</h2>
        <p><strong>Branch:</strong> {{ $storeFront->name }} - {{ $storeFront->branch_name }}</p>

        <form method="POST" action="{{ route('storefront.restock-requests.store', $storeFront) }}">
            @csrf

            <label>Item:</label><br>
            <select name="item_id" required>
                <option value="">Select an item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->item_name }} (Current stock: {{ $item->stock_quantity }})
                    </option>
                @endforeach
            </select><br><br>

            <label>Requested Quantity:</label><br>
            <input type="number" name="requested_quantity" min="1" required><br><br>

            <label>Note (optional):</label><br>
            <textarea name="note" rows="4"></textarea><br><br>

            <button type="submit">Send Request</button>
        </form>
    </div>
@endsection