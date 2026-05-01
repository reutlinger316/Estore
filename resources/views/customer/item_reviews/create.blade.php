@extends('layouts.app')

@section('content')
    <div class="shop-hero">
        <div class="shop-hero-text">
            <h1>Review {{ $item->item_name }}</h1>
            <p>Share your experience with this item to help other customers!</p>
            <p style="font-size: 0.95rem; opacity: 0.8; margin-top: 6px;"><strong>Store:</strong> {{ $item->storeFront->name }} - {{ $item->storeFront->branch_name }}</p>
        </div>
        <div class="shop-hero-anim">
            <lottie-player src="{{ asset('animations/rating stars..json') }}" background="transparent" speed="1" style="width: 160px; height: 160px;" loop autoplay></lottie-player>
        </div>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form method="POST" action="{{ route('customer.item-reviews.store', $item) }}">
            @csrf
            <div class="form-group mb-3">
            <label for="rating">Rating</label><br>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Select rating</option>
                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 ★</option>
                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 ★★</option>
                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 ★★★</option>
                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 ★★★★</option>
                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 ★★★★★</option>
            </select>
        </div>
            <div class="form-group mb-3">
                <label for="title">Review Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="form-group mb-3">
                <label for="body">Review Body</label>
                <textarea name="body" id="body" class="form-control">{{ old('body') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
@endsection