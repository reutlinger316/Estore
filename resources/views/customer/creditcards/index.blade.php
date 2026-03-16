@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="section-title">My Credit Cards</h2>

        <div class="actions">
            <a href="{{ route('customer.creditcards.create') }}" class="btn btn-primary">Add Credit Card</a>
        </div>

        <div class="list-block">
            @forelse($cards as $card)
                <div class="card">
                    <p><strong>Card Number:</strong> {{ $card->card_no }}</p>
                    <p><strong>Expiry Date:</strong> {{ $card->exp_date->format('Y-m-d') }}</p>
                    <p><strong>Balance:</strong> {{ number_format($card->balance, 2) }}</p>

                    <div class="actions">
                        <form method="POST" action="/customer/creditcards/{{ $card->id }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No cards added yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
