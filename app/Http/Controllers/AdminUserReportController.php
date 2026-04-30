<?php

namespace App\Http\Controllers;

use App\Models\UserReport;
use Illuminate\Http\Request;

class AdminUserReportController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $search = $request->query('search');

        $reports = UserReport::with(['reporter', 'reportedUser'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reason', 'like', "%{$search}%")
                        ->orWhereHas('reporter', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('reportedUser', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'reported_user', fn ($query) => $query->orderBy('reported_user_id'))
            ->when($sort === 'reporter', fn ($query) => $query->orderBy('reporter_id'))
            ->when(!in_array($sort, ['oldest', 'reported_user', 'reporter']), fn ($query) => $query->latest())
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', compact('reports', 'sort', 'search'));
    }
}
