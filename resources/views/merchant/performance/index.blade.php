@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Store Fronts Performance</h2>

        <div class="list-block">
            @forelse($storeFronts as $storeFront)
                <div class="card">
                    <h3>{{ $storeFront->name }}</h3>
                    <p><strong>Branch:</strong> {{ $storeFront->branch_name }}</p>
                    <p><strong>Location:</strong> {{ $storeFront->location }}</p>
                    <p><strong>Total Orders:</strong> {{ $storeFront->orders_count }}</p>
                    <p><strong>Total Reviews:</strong> {{ $storeFront->reviews_count }}</p>
                    <p>
                        <strong>Total Sales:</strong>
                        {{ number_format($storeFront->orders_sum_total_amount ?? 0, 2) }}
                    </p>
                    <p>
                        <strong>Average Rating:</strong>
                        {{ number_format($storeFront->reviews_avg_rating ?? 0, 2) }}
                    </p>

                    <div class="actions">
                        <a href="{{ route('merchant.performance.show', $storeFront) }}" class="btn btn-primary">
                            View Performance Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No storefronts found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection