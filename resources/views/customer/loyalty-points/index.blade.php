@extends('layouts.app')

@section('page_title', 'My Loyalty Points')
@section('page_subtitle', 'View global and merchant-specific loyalty point balances.')

@section('content')
    <div class="container">
        <div class="shop-hero">
            <div class="shop-hero-text">
                <h1>My Loyalty Points</h1>
                <p>View global and merchant-specific loyalty point balances.</p>
            </div>
            <div class="shop-hero-anim">
                <lottie-player src="{{ asset('animations/star.json') }}" background="transparent" speed="1" style="width: 160px; height: 160px;" loop autoplay></lottie-player>
            </div>
        </div>

        <div class="card">
            <h3>Point Wallets</h3>

            @forelse($wallets as $wallet)
                <div style="border:1px solid #ddd; border-radius:8px; padding:12px; margin-bottom:12px;">
                    <h4 style="margin:0 0 6px 0;">
                        @if($wallet->owner_type === 'admin')
                            Global Points
                        @else
                            Merchant Points - {{ $wallet->merchant->name ?? 'Merchant deleted' }}
                        @endif
                    </h4>
                    <p style="margin:0;"><strong>{{ $wallet->points }}</strong> points</p>
                </div>
            @empty
                <p>You do not have loyalty points yet. Place an order to start earning.</p>
            @endforelse
        </div>

        <div class="card" style="margin-top:20px;">
            <h3>Recent Point Transactions</h3>

            @forelse($transactions as $transaction)
                <div style="border-bottom:1px solid #ddd; padding:10px 0;">
                    <p style="margin:0 0 4px 0;">
                        <strong>{{ ucfirst($transaction->type) }}</strong>
                        @if($transaction->owner_type === 'admin')
                            Global Points
                        @else
                            Merchant Points - {{ $transaction->merchant->name ?? 'Merchant deleted' }}
                        @endif
                    </p>
                    <p style="margin:0 0 4px 0;">{{ $transaction->description }}</p>
                    <p style="margin:0;">
                        <strong>{{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}</strong>
                        points | {{ $transaction->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            @empty
                <p>No loyalty point transactions yet.</p>
            @endforelse
        </div>
    </div>
@endsection
