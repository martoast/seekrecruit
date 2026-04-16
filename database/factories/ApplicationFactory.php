<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\CandidateProfile;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-4 months', 'now');

        return [
            'candidate_id' => CandidateProfile::factory(),
            'position_id' => Position::factory(),
            'status' => fake()->randomElement([
                ApplicationStatus::REGISTERED,
                ApplicationStatus::PRESELECTED,
                ApplicationStatus::INTERVIEW,
                ApplicationStatus::EVALUATION,
                ApplicationStatus::FINALIST,
                ApplicationStatus::HIRED,
                ApplicationStatus::DISCARDED,
            ]),
            'created_at' => $createdAt,
            'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
        ];
    }

    public function withStatus(ApplicationStatus $status): static
    {
        return $this->state(['status' => $status]);
    }
}
