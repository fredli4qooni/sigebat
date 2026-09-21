<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest('id');

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($aksi = $request->input('aksi')) {
            $query->where('aksi', 'like', "%{$aksi}%");
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $logs = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.log.index', compact('logs', 'users'));
    }
}
