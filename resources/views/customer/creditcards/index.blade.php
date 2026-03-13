@extends('layout')
@section('title','Credit Cards')

@section('content')
    <div class="container">
        <h1>All Cards</h1>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('creditcards.create') }}" class="btn btn-primary">Add New Card</a>

        @foreach($cards as $card)
            <div class="card" style="background:#fff; padding:1rem; margin:1rem 0; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                <h2>{{ $card->cardNo }}</h2>
                <p><strong>CVV:</strong> {{ $card->cvv }}</p>
                <p><strong>Exp Date:</strong> {{ $card->expDate }}</p>
                <p><strong>Balance:</strong> {{ $card->balance }}</p>

                <form action="{{ route('creditcards.destroy', $card->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure?')">Remove</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection