@extends('layouts.app')

@section('page_title', 'Marketplace Settings')
@section('page_subtitle', 'Configure the marketplace registration fee and keep enrollment settings current.')

@section('content')
<div class="page-shell fade-up">
    <section class="form-shell">
        <div class="section-header">
            <div>
                <h2>Marketplace Registration Fee</h2>
                <p>Adjust the fee customers must pay to join the marketplace.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.marketplace.settings.update') }}" class="form-grid">
            @csrf
            <div class="form-group">
                <label>Registration Fee (Tk)</label>
                <input type="number" step="0.01" min="0" name="registration_fee" value="{{ $setting->registration_fee }}">
            </div>
            <div class="form-group actions" style="align-items:end; margin-top:30px;">
                <button type="submit" class="btn btn-primary">Update Fee</button>
            </div>
        </form>
    </section>
</div>
@endsection
