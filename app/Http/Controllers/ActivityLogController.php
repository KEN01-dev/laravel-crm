<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->where('target_type', Customer::class)
            ->with(['user', 'customer'])
            ->action($request->action)
            ->dateBetween($request->from, $request->to)
            ->ipLike($request->ip)
            ->when($request->user, function ($q) use ($request) {
                $q->whereHas('user', function ($uq) use ($request) {
                    $uq->where('name', 'like', "%{$request->user}%")
                       ->orWhere('email', 'like', "%{$request->user}%");
                });
            })
            ->when($request->customer, function ($q) use ($request) {
                $q->whereHas('customer', function ($cq) use ($request) {
                    $cq->where('name', 'like', "%{$request->customer}%")
                       ->orWhere('company', 'like', "%{$request->customer}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('activity-logs.index', compact('logs'));
    }
}
