@extends('layout')
@section('title','Home Page')

@section('content')
    @auth
        <p2>Hello {{auth()->user()->name}}!</p2>
            
    @else
        <p2>Hello Guest User, Please log in or sign in!</p2>

    @endauth
@endsection