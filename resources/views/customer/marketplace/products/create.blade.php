@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Sell a Product in Marketplace</h2>

        <form method="POST" action="{{ route('customer.marketplace.products.store') }}" enctype="multipart/form-data">
            @csrf

            <label>Product Name:</label><br>
            <input type="text" name="name"><br><br>

            <label>Description:</label><br>
            <textarea name="description"></textarea><br><br>

            <label>Price:</label><br>
            <input type="number" step="0.01" min="0" name="price"><br><br>

            <label>Stock:</label><br>
            <input type="number" min="1" name="stock"><br><br>

            <label>Image:</label><br>
            <input type="file" name="image" accept="image/*"><br><br>

            <button type="submit">List Product</button>
        </form>
    </div>
@endsection