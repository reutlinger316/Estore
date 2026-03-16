@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">Manage Funds</h2>

        <div class="card">
            <p><strong>Current Wallet Balance:</strong> {{ number_format($user->balance, 2) }}</p>
        </div>

        <form method="POST" action="{{ route('customer.funds.transfer') }}" style="max-width: 600px;">
            @csrf

            <div class="mb-3">
                <label>Select Card</label>
                <select name="card_id">
                    @foreach($cards as $card)
                        <option value="{{ $card->id }}">
                            {{ $card->card_no }} - Balance: {{ number_format($card->balance, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount">
            </div>

            <button type="submit" class="btn btn-primary">Transfer Funds</button>
        </form>
    </div>
@endsection
