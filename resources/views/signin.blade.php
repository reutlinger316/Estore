@extends('layout')
@section('title', 'Sign In')


@section('content')
    <div class="container">
        <div class="mt-5">
            @if($errors->any())
                <div class="col-12">
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger">{{session('error')}}</div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
        </div>
        <form action="{{ route('signin.post') }}" class="ms-auto me-auto mt-3" style='width: 500px' method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="name" class="form-control" name="name">
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="name" class="form-control" name="type">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection