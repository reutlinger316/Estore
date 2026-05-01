@extends('layouts.app')

@section('content')
    <div class="shop-hero">
        <div class="shop-hero-text">
            <h1>Leave a Review</h1>
            <p>Share your experience with {{ $storeFront->name }} - {{ $storeFront->branch_name }} to help others!</p>
        </div>
        <div class="shop-hero-anim">
            <lottie-player src="{{ asset('animations/rating stars..json') }}" background="transparent" speed="1" style="width: 160px; height: 160px;" loop autoplay></lottie-player>
        </div>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form method="POST" action="{{ route('customer.reviews.store', $storeFront) }}">
            @csrf
            <div class="form-group mb-3">
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
            <div class="form-group mb-3">
                <label for="title">Review Title</label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="body">Review Body</label>
                <textarea name="body" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
@endsection