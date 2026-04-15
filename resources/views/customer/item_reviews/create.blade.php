@extends('layouts.app')

@section('content')
    <h1>Leave a Review for {{ $item->item_name }}</h1>
    <p><strong>Store:</strong> {{ $item->storeFront->name }} - {{ $item->storeFront->branch_name }}</p>

    <form method="POST" action="{{ route('customer.item-reviews.store', $item) }}">
        @csrf
        <div class="mb-3">
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
        <div class="mb-3">
            <label for="title">Review Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label for="body">Review Body</label>
            <textarea name="body" id="body" class="form-control">{{ old('body') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Review</button>
    </form>
@endsection