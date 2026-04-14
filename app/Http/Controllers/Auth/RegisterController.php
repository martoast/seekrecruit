<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\CandidateProfile;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => Hash::make($request->string('password')),
                'role' => UserRole::CANDIDATE,
                'email_verified_at' => now(),
            ]);

            CandidateProfile::create([
                'user_id' => $user->id,
                'university' => '',
                'degree' => '',
                'location' => '',
                'gender' => $request->input('gender', Gender::PREFER_NOT_TO_SAY->value),
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('candidate.profile.edit')
            ->with('success', 'Account created! Complete your profile to get started.');
    }
}
