@extends('layout')
@section('title','Manage Funds')

@section('content')
<div class="container">
    <h1>Manage Funds</h1>

    {{-- Success / Error messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <p><strong>Your Account Balance:</strong> {{ $user->balance }}</p>

    <form method="POST" action="{{ route('funds.transfer') }}">
        @csrf
        <div class="mb-3">
            <label>Select Card:</label>
            <select name="card_id" required>
                @foreach($cards as $card)
                    <option value="{{ $card->id }}">
                        {{ $card->cardNo }} (Balance: {{ $card->balance }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Amount to Transfer:</label>
            <input type="number" name="amount" step="0.01" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Transfer</button>
    </form>
</div>
@endsection