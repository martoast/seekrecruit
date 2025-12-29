<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private StatsService $statsService
    ) {}

    public function __invoke(): JsonResponse
    {
        $stats = $this->statsService->getStats();

        return response()->json($stats);
    }
}
