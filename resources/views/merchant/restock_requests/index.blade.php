@extends('layouts.app')

@section('page_title', 'Restock Requests')
@section('page_subtitle', 'Review incoming restock requests from storefront branches and take action.')

@section('content')
<div class="page-shell fade-up">
    @if($requests->count())
        <div class="entity-grid">
            @foreach($requests as $request)
                <div class="entity-card">
                    <div class="entity-card__header">
                        <div>
                            <h3 class="entity-card__title">{{ $request->item->item_name }}</h3>
                            <p>{{ $request->storeFront->name }} - {{ $request->storeFront->branch_name }}</p>
                        </div>
                        <span class="badge {{ $request->status === 'pending' ? 'badge-warning' : ($request->status === 'approved' ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($request->status) }}</span>
                    </div>

                    <div class="entity-card__meta">
                        <div class="entity-row"><span>Requested By</span><strong>{{ $request->requester->name }}</strong></div>
                        <div class="entity-row"><span>Current Stock</span><strong>{{ $request->item->stock_quantity }}</strong></div>
                        <div class="entity-row"><span>Requested Qty</span><strong>{{ $request->requested_quantity }}</strong></div>
                        <div class="entity-row"><span>Note</span><strong>{{ $request->note ?: 'No note provided' }}</strong></div>
                    </div>

                    @if($request->status === 'pending')
                        <div class="entity-actions">
                            <form method="POST" action="{{ route('merchant.restock-requests.status.update', $request) }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-secondary">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('merchant.restock-requests.status.update', $request) }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger-soft">Reject</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No restock requests yet.</div>
    @endif
</div>
@endsection
