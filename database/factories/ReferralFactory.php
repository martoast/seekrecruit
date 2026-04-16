<?php

namespace Database\Factories;

use App\Enums\ReferralStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends Factory
{
    public function definition(): array
    {
        return [
            'referrer_id' => User::factory()->candidate(),
            'referred_email' => fake()->unique()->safeEmail(),
            'referred_user_id' => null,
            'status' => ReferralStatus::PENDING,
        ];
    }

    public function pending(): static
    {
        return $this->state([
            'status' => ReferralStatus::PENDING,
            'referred_user_id' => null,
        ]);
    }

    public function registered(User $referredUser): static
    {
        return $this->state([
            'status' => ReferralStatus::REGISTERED,
            'referred_user_id' => $referredUser->id,
            'referred_email' => $referredUser->email,
        ]);
    }

    public function hired(User $referredUser): static
    {
        return $this->state([
            'status' => ReferralStatus::HIRED,
            'referred_user_id' => $referredUser->id,
            'referred_email' => $referredUser->email,
        ]);
    }

    public function rewarded(User $referredUser): static
    {
        return $this->state([
            'status' => ReferralStatus::REWARDED,
            'referred_user_id' => $referredUser->id,
            'referred_email' => $referredUser->email,
        ]);
    }
}
