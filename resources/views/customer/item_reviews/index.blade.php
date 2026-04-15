@extends('layouts.app')

@section('content')
    <h1>Reviews for {{ $item->item_name }}</h1>
    <p><strong>Store:</strong> {{ $item->storeFront->name }} - {{ $item->storeFront->branch_name }}</p>

    @if(!$item->reviews->where('customer_id', auth()->id())->count())
        <a href="{{ route('customer.item-reviews.create', $item) }}" class="btn btn-primary">Leave a Review</a>
    @endif

    <hr>

    @forelse($reviews as $review)
        <div class="card review-card">
            <p><strong>{{ $review->customer->name }}</strong> ({{ $review->created_at->diffForHumans() }})</p>

            <p>
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <span style="color: gold;">★</span>
                    @else
                        <span style="color: #ccc;">★</span>
                    @endif
                @endfor
                ({{ $review->rating }}/5)
            </p>

            @if($review->title)
                <p class="review-title"><strong>{{ $review->title }}</strong></p>
            @endif

            <p>{{ $review->body }}</p>

            @if(auth()->id() === $review->customer_id)
                <div class="review-actions">
                    <form action="{{ route('customer.item-reviews.edit', $review) }}" method="GET" class="inline-form">
                        <button type="submit" class="btn btn-warning action-btn">Edit</button>
                    </form>

                    <form method="POST" action="{{ route('customer.item-reviews.destroy', $review) }}"
                          onsubmit="return confirm('Are you sure you want to delete this review?');"
                          class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger action-btn">Delete</button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <p>No reviews yet. Be the first to leave one!</p>
    @endforelse
@endsection