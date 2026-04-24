@extends('layouts.app')

@section('page_title', 'Performance Details')
@section('page_subtitle', 'Review store-level summary metrics and open detailed ratings or order analytics.')

@section('content')
<div class="page-shell fade-up">
    <section class="section-card">
        <div class="section-header">
            <div>
                <h2>{{ $storeFront->name }} - {{ $storeFront->branch_name }}</h2>
                <p>{{ $storeFront->location }}</p>
            </div>
            <a href="{{ route('merchant.performance.index') }}" class="btn btn-ghost">Back to Performance List</a>
        </div>

        <div class="metric-grid">
            <div class="metric-card"><div class="metric-card__label">Total Orders</div><div class="metric-card__value">{{ $summary['total_orders'] }}</div></div>
            <div class="metric-card"><div class="metric-card__label">Total Sales</div><div class="metric-card__value">{{ number_format($summary['total_sales'], 2) }}</div></div>
            <div class="metric-card"><div class="metric-card__label">Average Rating</div><div class="metric-card__value">{{ number_format($summary['average_rating'] ?? 0, 2) }}</div></div>
            <div class="metric-card"><div class="metric-card__label">Total Ratings</div><div class="metric-card__value">{{ $summary['total_reviews'] }}</div></div>
        </div>

        <div class="entity-actions">
            <a href="{{ route('merchant.performance.ratings', $storeFront) }}" class="btn btn-primary">View Ratings</a>
            <a href="{{ route('merchant.performance.orders', $storeFront) }}" class="btn btn-secondary">View Orders</a>
        </div>
    </section>
</div>
@endsection
