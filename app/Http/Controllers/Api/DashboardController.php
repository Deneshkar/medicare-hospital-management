<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function stats(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->for($request->user()),
        ]);
    }
}
