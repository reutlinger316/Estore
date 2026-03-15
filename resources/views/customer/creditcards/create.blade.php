@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Add Credit Card</h2>

        <form method="POST" action="{{ route('customer.creditcards.store') }}" style="max-width: 600px;">
            @csrf

            <div class="mb-3">
                <label>Card Number</label>
                <input type="text" name="card_no" value="{{ old('card_no') }}">
            </div>

            <div class="mb-3">
                <label>CVV</label>
                <input type="text" name="cvv" value="{{ old('cvv') }}">
            </div>

            <div class="mb-3">
                <label>Expiry Date</label>
                <input type="date" name="exp_date" value="{{ old('exp_date') }}">
            </div>

            <div class="mb-3">
                <label>Balance</label>
                <input type="number" step="0.01" name="balance" value="{{ old('balance') }}">
            </div>

            <button type="submit" class="btn btn-primary">Save Card</button>
        </form>
    </div>
@endsection