<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToClient;
use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ScopesToClient;

    public function __construct(
        private StatsService $statsService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);
        $activeClient = $this->activeClient($user, $request->integer('client_id') ?: null);

        $stats = $this->statsService->getStats($clientId);

        return view('admin.dashboard', compact('stats', 'activeClient'));
    }
}
