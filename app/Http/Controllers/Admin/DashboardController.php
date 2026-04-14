<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private StatsService $statsService
    ) {}

    public function index(): View
    {
        $stats = $this->statsService->getStats();

        return view('admin.dashboard', compact('stats'));
    }
}
