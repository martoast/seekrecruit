<?php

namespace Database\Seeders;

use App\Enums\ReferralStatus;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReferralSeeder extends Seeder
{
    public function run(): void
    {
        $juan = User::where('email', 'juan@example.com')->first();
        $ana = User::where('email', 'ana@example.com')->first();
        $laura = User::where('email', 'laura@example.com')->first();
        $sofia = User::where('email', 'sofia@example.com')->first();
        $roberto = User::where('email', 'roberto@example.com')->first();
        $carlos = User::where('email', 'carlos@example.com')->first();
        $diego = User::where('email', 'diego@example.com')->first();

        // Pending — the referred person hasn't signed up yet
        Referral::create([
            'referrer_id' => $juan->id,
            'referred_email' => 'friend.of.juan@example.com',
            'status' => ReferralStatus::PENDING,
            'created_at' => now()->subDays(10),
        ]);

        Referral::create([
            'referrer_id' => $juan->id,
            'referred_email' => 'another.friend@example.com',
            'status' => ReferralStatus::PENDING,
            'created_at' => now()->subDays(3),
        ]);

        Referral::create([
            'referrer_id' => $roberto->id,
            'referred_email' => 'prospect@tijuanamaker.com',
            'status' => ReferralStatus::PENDING,
            'created_at' => now()->subDays(6),
        ]);

        // Registered — the referred person signed up (links to a real candidate)
        Referral::create([
            'referrer_id' => $ana->id,
            'referred_email' => $carlos->email,
            'referred_user_id' => $carlos->id,
            'status' => ReferralStatus::REGISTERED,
            'created_at' => now()->subDays(25),
        ]);

        Referral::create([
            'referrer_id' => $laura->id,
            'referred_email' => $diego->email,
            'referred_user_id' => $diego->id,
            'status' => ReferralStatus::REGISTERED,
            'created_at' => now()->subDays(20),
        ]);

        // Hired — referred candidate was hired at a client
        Referral::create([
            'referrer_id' => $sofia->id,
            'referred_email' => $juan->email,
            'referred_user_id' => $juan->id,
            'status' => ReferralStatus::HIRED,
            'created_at' => now()->subDays(45),
        ]);

        // Rewarded — reward has been paid out to the referrer
        Referral::create([
            'referrer_id' => $juan->id,
            'referred_email' => $sofia->email,
            'referred_user_id' => $sofia->id,
            'status' => ReferralStatus::REWARDED,
            'created_at' => now()->subDays(60),
        ]);
    }
}
