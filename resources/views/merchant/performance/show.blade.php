@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Performance Details</h2>

        <div class="card" style="margin-bottom: 20px;">
            <h3>{{ $storeFront->name }}</h3>
            <p><strong>Branch:</strong> {{ $storeFront->branch_name }}</p>
            <p><strong>Location:</strong> {{ $storeFront->location }}</p>
            <p><strong>Total Orders:</strong> {{ $summary['total_orders'] }}</p>
            <p><strong>Total Sales:</strong> {{ number_format($summary['total_sales'], 2) }}</p>
            <p><strong>Overall Average Rating:</strong> {{ number_format($summary['average_rating'] ?? 0, 2) }}</p>
            <p><strong>Total Ratings:</strong> {{ $summary['total_reviews'] }}</p>
        </div>

        <div class="actions" style="gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('merchant.performance.ratings', $storeFront) }}" class="btn btn-primary">
                View Ratings
            </a>

            <a href="{{ route('merchant.performance.orders', $storeFront) }}" class="btn btn-primary">
                View Orders
            </a>

            <a href="{{ route('merchant.performance.index') }}" class="btn btn-secondary">
                Back to Performance List
            </a>
        </div>
    </div>
@endsection