@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Marketplace Registered Users</h2>

        @forelse($accounts as $acc)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                <p><strong>Name:</strong> {{ $acc->user->name }}</p>
                <p><strong>Email:</strong> {{ $acc->user->email }}</p>
                <p><strong>Paid Amount:</strong> {{ number_format($acc->paid_fee, 2) }} Tk</p>
                <p><strong>Status:</strong> {{ $acc->is_eligible ? 'Active' : 'Inactive' }}</p>
                <p><strong>Activated At:</strong> {{ $acc->paid_at ?? 'Not Activated' }}</p>
            </div>
        @empty
            <p>No marketplace users yet.</p>
        @endforelse
    </div>
@endsection