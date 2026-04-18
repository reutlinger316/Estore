@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>StoreFronts Under Merchant</h2>

        <p><strong>Merchant Name:</strong> {{ $merchant->name }}</p>
        <p><strong>Merchant Email:</strong> {{ $merchant->email }}</p>

        <a href="{{ route('admin.users.merchants') }}">
            <button>Back to Merchants</button>
        </a>

        <br><br>

        @forelse($storeFronts as $storeFront)
            <div style="border:1px solid #ddd; padding:12px; margin-bottom:12px;">
                <p><strong>Store Name:</strong> {{ $storeFront->name }}</p>
                <p><strong>Branch Name:</strong> {{ $storeFront->branch_name }}</p>
                <p><strong>Location:</strong> {{ $storeFront->location }}</p>
                <p><strong>Delivery City:</strong> {{ $storeFront->delivery_city }}</p>
                <p><strong>Status:</strong> {{ $storeFront->status }}</p>
                <p><strong>Confirmation Status:</strong> {{ $storeFront->confirmation_status }}</p>
                <p><strong>Balance:</strong> {{ number_format($storeFront->balance, 2) }}</p>

                @if($storeFront->storeAccount)
                    <p><strong>StoreFront User:</strong> {{ $storeFront->storeAccount->name }} ({{ $storeFront->storeAccount->email }})</p>
                @endif
            </div>
        @empty
            <p>No storefronts found under this merchant.</p>
        @endforelse
    </div>
@endsection