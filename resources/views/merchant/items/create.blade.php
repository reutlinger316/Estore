@extends('layouts.app')

@section('content')
    <h1>Add Item to {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>

    <form method="POST" action="{{ route('merchant.items.store', $storeFront) }}" enctype="multipart/form-data">
        @csrf

        <label>Item Name:</label><br>
        <input type="text" name="item_name"><br><br>

        <label>Description:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Item Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price"><br><br>

        <label>Stock Quantity:</label><br>
        <input type="number" name="stock_quantity"><br><br>

        <label>Low Stock Alert Threshold:</label><br>
        <input type="number" name="low_stock_threshold" min="1" value="3"><br><br>

        <label>Discount:</label><br>
        <input type="number" step="0.01" name="discount" value="0"><br><br>

        <label>
            <input type="checkbox" name="is_pre_order" value="1">
            Is Pre-order
        </label><br><br>

        <button type="submit">Create Item</button>
    </form>
@endsection