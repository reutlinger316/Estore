@extends('layout')
@section('title','Add Credit Card')

@section('content')
    <div class="container">
        <h1>Add Credit Card</h1>

        <form method="POST" action="{{ route('creditcards.store') }}">
            @csrf

            <div class="mb-3">
                <label>Card Number:</label>
                <input type="text" name="cardNo" value="{{ old('cardNo') }}"
                       pattern="[0-9]{16}" maxlength="16"
                       placeholder="16 digit card number" required>
            </div>

            <div class="mb-3">
                <label>CVV:</label>
                <input type="text" name="cvv" value="{{ old('cvv') }}"
                       pattern="[0-9]{3,4}" maxlength="4"
                       placeholder="3 or 4 digit CVV" required>
            </div>

            <div class="mb-3">
                <label>Expiration Date:</label>
                <input type="date" name="expDate" value="{{ old('expDate') }}" required>
            </div>

            <div class="mb-3">
                <label>Balance:</label>
                <input type="number" name="balance" value="{{ old('balance') }}"
                       step="0.01" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection