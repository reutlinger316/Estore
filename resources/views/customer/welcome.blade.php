@extends('layout')
@section('title','Home Page')

@section('content')
    <div class="welcome">
        @auth
            <p>Hello <strong>{{ auth()->user()->name }}</strong>!</p>
            <a href="{{ route('creditcards.index') }}" class="btn btn-primary">Manage Credit Cards</a>
            <br><br>
            <a href="{{ route('funds.index') }}" class="btn btn-primary">Manage Funds</a>
        @else
            <p>Hello Guest User, please log in or sign up!</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('signin') }}" class="btn btn-primary">Sign Up</a>
        @endauth
    </div>
@endsection