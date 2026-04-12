@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Browse Shops</h2>

        <form method="GET" action="{{ route('customer.shops.index') }}" style="margin-bottom: 1rem;">
            <div class="mb-3">
                <label>Search shops</label>
                <input type="text" name="search" placeholder="Search shops..." value="{{ request('search') }}">
            </div>

            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="list-block">
            @forelse($shops as $shop)
                <div class="card">
                    <h3>{{ $shop->name }}</h3>
                    <p><strong>Branch:</strong> {{ $shop->branch_name }}</p>
                    <p><strong>Location:</strong> {{ $shop->location }}</p>


                    @if($shop->averageRating())
                        @php
                            $avg = round($shop->averageRating(), 1);
                            $fullStars = floor($avg);
                            $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - $fullStars - $halfStar;
                        @endphp

                        <p>
                            <strong>Rating:</strong>
                            {{-- Full stars --}}
                            @for($i = 0; $i < $fullStars; $i++)
                                ★
                            @endfor
                            {{-- Half star --}}
                            @if($halfStar)
                                ☆
                            @endif
                            {{-- Empty stars --}}
                            @for($i = 0; $i < $emptyStars; $i++)
                                ☆
                            @endfor

                            ({{ $avg }}/5)
                        </p>
                    @endif

                    <div class="actions">
                        <a href="{{ route('customer.shops.show', $shop) }}" class="btn btn-primary">View Menu</a>
                        <a href="{{ route('customer.reviews.create', $shop) }}" class="btn btn-primary">Leave a Review</a>
                        <a href="{{ route('customer.reviews.index', $shop) }}" class="btn btn-primary">See Reviews</a>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No shops available.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
