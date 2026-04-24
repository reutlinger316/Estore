@extends('layouts.app')

@section('page_title', 'Pending Branch Requests')
@section('page_subtitle', 'Review new branch assignments and decide which requests to accept or reject.')

@section('content')
<div class="page-shell fade-up">
    @if($requests->count())
        <div class="entity-grid">
            @foreach($requests as $request)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $request->name }}</h3>
                            <p>{{ $request->branch_name }}</p>
                        </div>
                        <span class="badge badge-warning">Pending</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Location</span><strong>{{ $request->location }}</strong></div>
                    </div>

                    <div class="entity-actions">
                        <form method="POST" action="{{ route('storefront.branch-requests.accept', $request) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-primary">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('storefront.branch-requests.reject', $request) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-danger-soft">Reject</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No pending branch requests.</div>
    @endif
</div>
@endsection
