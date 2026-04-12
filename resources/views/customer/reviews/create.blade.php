@extends('layouts.app')

@section('content')
    <h1>Leave a Review for {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>

    <form method="POST" action="{{ route('customer.reviews.store', $storeFront) }}">
        @csrf
        <div class="mb-3">
            <label for="rating">Rating</label><br>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Select rating</option>
                <option value="1">1 ★</option>
                <option value="2">2 ★★</option>
                <option value="3">3 ★★★</option>
                <option value="4">4 ★★★★</option>
                <option value="5">5 ★★★★★</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="title">Review Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label for="body">Review Body</label>
            <textarea name="body" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Review</button>
    </form>
@endsection