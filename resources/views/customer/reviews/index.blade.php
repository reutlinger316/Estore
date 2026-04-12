@extends('layouts.app')

@section('content')
    <h1>Reviews for {{ $storeFront->name }} - {{ $storeFront->branch_name }}</h1>

    @forelse($reviews as $review)
        <div class="card">
            <p><strong>{{ $review->customer->name }}</strong> ({{ $review->created_at->diffForHumans() }})</p>
            @if($review->rating)
                <p>Rating: {{ $review->rating }}/5</p>
            @endif
            @if($review->title)
                <h4>{{ $review->title }}</h4>
            @endif
            <p>{{ $review->body }}</p>
        </div>
    @empty
        <p>No reviews yet. Be the first to leave one!</p>
    @endforelse
@endsection
