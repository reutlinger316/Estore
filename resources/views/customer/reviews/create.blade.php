@extends('layouts.app')

@section('content')
    <h1>Leave a Review for {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>

    <form method="POST" action="{{ route('customer.reviews.store', $storeFront) }}">
        @csrf
        <div class="mb-3">
            <label for="rating">Rating (1–5)</label>
            <input type="number" name="rating" min="1" max="5">
        </div>
        <div class="mb-3">
            <label for="title">Review Title</label>
            <input type="text" name="title">
        </div>
        <div class="mb-3">
            <label for="body">Review Body</label>
            <textarea name="body"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Review</button>
    </form>
@endsection
