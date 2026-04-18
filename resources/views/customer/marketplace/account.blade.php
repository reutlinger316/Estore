@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Marketplace Account</h2>

        <p><strong>Your Balance:</strong> {{ number_format($user->balance, 2) }} Tk</p>
        <p><strong>Marketplace Registration Fee:</strong> {{ number_format($setting->registration_fee, 2) }} Tk</p>

        @if($account && $account->is_eligible)
            <p style="color: green; font-weight: bold;">Your marketplace account is active. You can now buy and sell in the marketplace.</p>

            <a href="{{ route('customer.marketplace.products.index') }}">Browse Marketplace Products</a><br><br>
            <a href="{{ route('customer.marketplace.products.create') }}">List a Product for Sale</a><br><br>
            <a href="{{ route('customer.marketplace.products.my-products') }}">My Marketplace Products</a>
        @else
            <p>You need to pay the marketplace registration fee to become eligible for selling.</p>

            <form method="POST" action="{{ route('customer.marketplace.account.pay') }}">
                @csrf
                <button type="submit">Pay {{ number_format($setting->registration_fee, 2) }} Tk and Activate Marketplace</button>
            </form>
        @endif
    </div>
@endsection