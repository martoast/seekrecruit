<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationNote>
 */
class ApplicationNoteFactory extends Factory
{
    private const NOTE_SNIPPETS = [
        'Strong technical background. Good portfolio.',
        'Moving to the next stage. Schedule technical interview.',
        'Great cultural fit. Communication skills are on point.',
        'Salary expectations aligned with budget.',
        'Needs to complete the take-home assessment.',
        'CAD test completed successfully. Solid fundamentals.',
        'Passed the technical interview with flying colors.',
        'Concerns about experience level — worth a second opinion.',
        'Great references. Previous managers spoke highly of them.',
        'Location flexibility confirmed — open to on-site work.',
        'Final interview scheduled with director.',
        'Decision pending — waiting on panel feedback.',
    ];

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'author_id' => User::factory(),
            'content' => fake()->randomElement(self::NOTE_SNIPPETS),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
