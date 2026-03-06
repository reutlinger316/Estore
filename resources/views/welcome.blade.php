@extends('layout')
@section('title','Home Page')

@section('content')
    @auth
        {{auth()->user()->name}}
        {{'HEY'}}
    @endauth
@endsection