@extends('layouts.app')

@section('page_title', 'User Reports')
@section('page_subtitle', 'Review and sort marketplace user reports submitted by customers.')

@section('content')
<section class="panel">
    <div class="section-heading">
        <div>
            <h3>Report List</h3>
            <p>Search reports and sort them by latest, oldest, reporter, or reported user.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by reporter, reported user, email, or reason" class="form-control" style="flex:1; min-width:260px;">

        <select name="sort" class="form-control" style="width: auto;">
            <option value="latest" @selected($sort === 'latest')>Latest first</option>
            <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
            <option value="reported_user" @selected($sort === 'reported_user')>Reported user</option>
            <option value="reporter" @selected($sort === 'reporter')>Reporter</option>
        </select>

        <button type="submit" class="btn btn-primary">Apply</button>
        @if(!empty($search) || $sort !== 'latest')
            <a href="{{ route('admin.reports.index') }}" class="btn btn-ghost">Clear</a>
        @endif
    </form>
</section>

@if($reports->count())
    <div class="entity-grid">
        @foreach($reports as $report)
            <article class="entity-card">
                <div class="entity-card__header">
                    <div>
                        <h3 class="entity-card__title">
                            {{ optional($report->reportedUser)->name ?? 'Deleted User' }}
                        </h3>
                        <p>Reported by {{ optional($report->reporter)->name ?? 'Deleted User' }} · {{ $report->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($report->reportedUser)
                        <a href="{{ route('admin.users.show', $report->reportedUser) }}" class="btn btn-ghost">View User</a>
                    @endif
                </div>
                <p style="margin-top:12px;">{{ $report->reason }}</p>
            </article>
        @endforeach
    </div>

    <div style="margin-top:20px;">
        {{ $reports->links() }}
    </div>
@else
    <section class="panel"><p>No reports found.</p></section>
@endif
@endsection
