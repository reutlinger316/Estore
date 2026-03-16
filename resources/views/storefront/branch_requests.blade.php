@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Pending Branch Requests</h2>

        <div class="list-block">
            @forelse($requests as $request)
                <div class="card">
                    <h3>{{ $request->name }}</h3>
                    <p><strong>Branch:</strong> {{ $request->branch_name }}</p>
                    <p><strong>Location:</strong> {{ $request->location }}</p>

                    <div class="actions">
                        <form method="POST" action="{{ route('storefront.branch-requests.accept', $request) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-primary">Accept</button>
                        </form>

                        <form method="POST" action="{{ route('storefront.branch-requests.reject', $request) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No pending branch requests.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
