<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\ReferralStatus;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $referrals = Referral::where('referrer_id', $request->user()->id)
            ->with('referredUser')
            ->latest()
            ->get();

        return response()->json([
            'referrals' => $referrals,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'referred_email' => ['required', 'email'],
        ]);

        $existingReferral = Referral::where('referrer_id', $request->user()->id)
            ->where('referred_email', $request->referred_email)
            ->first();

        if ($existingReferral) {
            return response()->json([
                'message' => 'You have already referred this email address',
            ], 422);
        }

        $referral = Referral::create([
            'referrer_id' => $request->user()->id,
            'referred_email' => $request->referred_email,
            'status' => ReferralStatus::PENDING,
        ]);

        return response()->json([
            'referral' => $referral,
            'message' => 'Referral invitation sent successfully',
        ], 201);
    }
}
