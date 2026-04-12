@extends('layouts.app')

@section('content')
    <h1>Edit Your Review</h1>

    <form method="POST" action="{{ route('customer.reviews.update', $review) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="rating">Rating</label><br>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Select rating</option>
                <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>1 ★</option>
                <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>2 ★★</option>
                <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>3 ★★★</option>
                <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>4 ★★★★</option>
                <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>5 ★★★★★</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="title">Review Title</label>
            <input type="text" name="title" value="{{ $review->title }}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="body">Review Body</label>
            <textarea name="body" class="form-control">{{ $review->body }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Review</button>
    </form>
@endsection