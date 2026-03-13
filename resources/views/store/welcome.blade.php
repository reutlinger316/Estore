@extends('layout')
@section('title','Home Page')

@section('content')
    @auth
        <p2>Hello {{auth()->user()->name}}!</p2>
            

    @endauth
@endsection