@extends('layouts.app')

@section('page_title', 'Marketplace Users')
@section('page_subtitle', 'Track paid marketplace enrollments, eligibility status, and user report counts.')

@section('content')
<div class="page-shell fade-up">
    @if($accounts->count())
        <div class="entity-grid">
            @foreach($accounts as $acc)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">
                                {{ $acc->user->name }}
                                <span style="font-size:12px; font-weight:700; color:#dc2626; background:#fee2e2; padding:4px 8px; border-radius:999px; margin-left:8px;">
                                    {{ $acc->user->reports_received_count ?? $acc->user->reportsReceived()->count() }} reports
                                </span>
                            </h3>
                            <p>{{ $acc->user->email }}</p>
                        </div>
                        <span class="badge {{ $acc->is_eligible ? 'badge-success' : 'badge-warning' }}">{{ $acc->is_eligible ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Paid Amount</span><strong>{{ number_format($acc->paid_fee, 2) }} Tk</strong></div>
                        <div class="entity-row"><span>Activated At</span><strong>{{ $acc->paid_at ?? 'Not Activated' }}</strong></div>
                        <div class="entity-row"><span>Reports Made</span><strong>{{ $acc->user->reports_made_count ?? $acc->user->reportsMade()->count() }}</strong></div>
                    </div>
                    <div style="margin-top:16px;">
                        <a href="{{ route('admin.users.show', $acc->user) }}" class="btn btn-ghost">View Activity / Reports</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No marketplace users yet.</div>
    @endif
</div>
@endsection
