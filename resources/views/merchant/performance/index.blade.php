@extends('layouts.app')

@section('page_title', 'Storefront Performance')
@section('page_subtitle', 'Compare storefront sales, order volume, and ratings to spot trends quickly.')

@section('content')
<div class="page-shell fade-up">
    @if($storeFronts->count())
        <div class="entity-grid">
            @foreach($storeFronts as $storeFront)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $storeFront->name }}</h3>
                            <p>{{ $storeFront->branch_name }} · {{ $storeFront->location }}</p>
                        </div>
                        <span class="badge badge-info">{{ number_format($storeFront->reviews_avg_rating ?? 0, 2) }} ★</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Total Orders</span><strong>{{ $storeFront->orders_count }}</strong></div>
                        <div class="entity-row"><span>Total Reviews</span><strong>{{ $storeFront->reviews_count }}</strong></div>
                        <div class="entity-row"><span>Total Sales</span><strong>{{ number_format($storeFront->orders_sum_total_amount ?? 0, 2) }}</strong></div>
                        <div class="entity-row"><span>Average Rating</span><strong>{{ number_format($storeFront->reviews_avg_rating ?? 0, 2) }}</strong></div>
                    </div>

                    <div class="entity-actions">
                        <a href="{{ route('merchant.performance.show', $storeFront) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No storefronts found.</div>
    @endif
</div>
@endsection
