@extends('layouts.app')

@section('content')
    <h1>Edit Item</h1>

    <form method="POST" action="{{ route('merchant.items.update', ['storeFront' => $storeFront, 'item' => $item]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Item Name:</label><br>
        <input type="text" name="item_name" value="{{ $item->item_name }}"><br><br>

        <label>Description:</label><br>
        <textarea name="description">{{ $item->description }}</textarea><br><br>

        @if($item->image)
            <p>Current Image:</p>
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" width="120"><br><br>
        @endif

        <label>Change Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="{{ $item->price }}"><br><br>

        <label>Stock Quantity:</label><br>
        <input type="number" name="stock_quantity" value="{{ $item->stock_quantity }}"><br><br>

        <label>Discount:</label><br>
        <input type="number" step="0.01" name="discount" value="{{ $item->discount }}"><br><br>

        <label>
            <input type="checkbox" name="is_pre_order" value="1" {{ $item->is_pre_order ? 'checked' : '' }}>
            Is Pre-order
        </label><br><br>

        <button type="submit">Update Item</button>
    </form>
@endsection
