@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Marketplace Settings</h2>

        <form method="POST" action="{{ route('admin.marketplace.settings.update') }}">
            @csrf

            <label>Marketplace Registration Fee (Tk):</label><br>
            <input type="number" step="0.01" min="0" name="registration_fee" value="{{ $setting->registration_fee }}"><br><br>

            <button type="submit">Update Fee</button>
        </form>
    </div>
@endsection