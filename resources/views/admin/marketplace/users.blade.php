@extends('layouts.app')

@section('page_title', 'Marketplace Users')
@section('page_subtitle', 'Track paid marketplace enrollments and eligibility status across user accounts.')

@section('content')
<div class="page-shell fade-up">
    @if($accounts->count())
        <div class="entity-grid">
            @foreach($accounts as $acc)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $acc->user->name }}</h3>
                            <p>{{ $acc->user->email }}</p>
                        </div>
                        <span class="badge {{ $acc->is_eligible ? 'badge-success' : 'badge-warning' }}">{{ $acc->is_eligible ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Paid Amount</span><strong>{{ number_format($acc->paid_fee, 2) }} Tk</strong></div>
                        <div class="entity-row"><span>Activated At</span><strong>{{ $acc->paid_at ?? 'Not Activated' }}</strong></div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No marketplace users yet.</div>
    @endif
</div>
@endsection
