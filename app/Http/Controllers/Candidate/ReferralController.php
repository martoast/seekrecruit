<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\ReferralStatus;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request): View
    {
        $referrals = Referral::where('referrer_id', $request->user()->id)
            ->latest()
            ->get();

        return view('candidate.referrals', compact('referrals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'referred_email' => ['required', 'email'],
        ]);

        $existing = Referral::where('referrer_id', $request->user()->id)
            ->where('referred_email', $data['referred_email'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already referred this email address.');
        }

        Referral::create([
            'referrer_id' => $request->user()->id,
            'referred_email' => $data['referred_email'],
            'status' => ReferralStatus::PENDING,
        ]);

        return back()->with('success', 'Referral invitation sent successfully.');
    }
}
