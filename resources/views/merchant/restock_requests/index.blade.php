@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Restock Requests</h2>

        <div class="list-block">
            @forelse($requests as $request)
                <div class="card">
                    <h3>{{ $request->item->item_name }}</h3>
                    <p><strong>Store:</strong> {{ $request->storeFront->name }} - {{ $request->storeFront->branch_name }}</p>
                    <p><strong>Requested By:</strong> {{ $request->requester->name }}</p>
                    <p><strong>Current Stock:</strong> {{ $request->item->stock_quantity }}</p>
                    <p><strong>Requested Quantity:</strong> {{ $request->requested_quantity }}</p>
                    <p><strong>Note:</strong> {{ $request->note ?: 'No note provided' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>

                    @if($request->status === 'pending')
                        <div class="actions">
                            <form method="POST" action="{{ route('merchant.restock-requests.status.update', $request) }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

                            <form method="POST" action="{{ route('merchant.restock-requests.status.update', $request) }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="card">
                    <p>No restock requests yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection