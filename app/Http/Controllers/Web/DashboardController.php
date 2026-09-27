<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return view('dashboard', [
            'role' => $user->role,
            'stats' => $this->dashboardService->for($user),
        ]);
    }
}
