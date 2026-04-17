@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Ratings - {{ $storeFront->name }}</h2>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Filter Ratings by Star</h3>

            <form method="GET" action="{{ route('merchant.performance.ratings', $storeFront) }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <select name="star" style="padding:8px;">
                    <option value="">All Ratings</option>
                    <option value="5" {{ $selectedStar == 5 ? 'selected' : '' }}>5 Star</option>
                    <option value="4" {{ $selectedStar == 4 ? 'selected' : '' }}>4 Star</option>
                    <option value="3" {{ $selectedStar == 3 ? 'selected' : '' }}>3 Star</option>
                    <option value="2" {{ $selectedStar == 2 ? 'selected' : '' }}>2 Star</option>
                    <option value="1" {{ $selectedStar == 1 ? 'selected' : '' }}>1 Star</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>

                <a href="{{ route('merchant.performance.ratings', $storeFront) }}" class="btn btn-secondary">
                    Clear Filter
                </a>
            </form>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Ratings List</h3>

            @forelse($ratings as $rating)
                <div style="border-bottom:1px solid #ddd; padding:10px 0;">
                    <p><strong>Customer:</strong> {{ $rating->customer?->name ?? 'Unknown' }}</p>
                    <p><strong>Rating:</strong> {{ $rating->rating }} Star</p>
                    <p><strong>Title:</strong> {{ $rating->title }}</p>
                    <p><strong>Review:</strong> {{ $rating->body }}</p>
                    <p><strong>Date:</strong> {{ $rating->created_at?->format('d M Y h:i A') }}</p>
                </div>
            @empty
                <p>No ratings found.</p>
            @endforelse
        </div>

        <div class="actions" style="gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('merchant.performance.show', $storeFront) }}" class="btn btn-secondary">
                Back to Summary
            </a>
        </div>
    </div>
@endsection