@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Report Seller</h2>
    @if(session('error'))
        <div class="alert alert-danger fade-up">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger fade-up">
            <ul class="alert-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <h3>{{ $seller->name }}</h3>

        <form method="POST" action="{{ route('customer.marketplace.sellers.report', $seller) }}">
            @csrf

            <div class="form-group">
                <label for="reason">Reason for reporting</label>

                <textarea
                    name="reason"
                    id="reason"
                    rows="5"
                    placeholder="Explain why you are reporting this seller..."
                    required
                >{{ old('reason') }}</textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Submit Report
                </button>

                <a href="{{ url()->previous() }}" class="btn btn-ghost">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection