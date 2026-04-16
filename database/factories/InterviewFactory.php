<?php

namespace Database\Factories;

use App\Enums\InterviewType;
use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interview>
 */
class InterviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'scheduled_at' => fake()->dateTimeBetween('-6 weeks', '+3 weeks'),
            'location' => fake()->randomElement([
                'Conference Room A',
                'Conference Room B',
                'HR Office',
                'Director Office',
                'Engineering Lab',
                'Zoom — link will be sent',
                'Microsoft Teams',
                'Google Meet',
            ]),
            'type' => fake()->randomElement([
                InterviewType::TECHNICAL,
                InterviewType::HR,
                InterviewType::FINAL,
            ]),
            'notes' => fake()->boolean(60) ? fake()->sentence(12) : null,
        ];
    }

    public function upcoming(): static
    {
        return $this->state([
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+3 weeks'),
        ]);
    }

    public function past(): static
    {
        return $this->state([
            'scheduled_at' => fake()->dateTimeBetween('-6 weeks', '-1 day'),
        ]);
    }

    public function ofType(InterviewType $type): static
    {
        return $this->state(['type' => $type]);
    }
}
