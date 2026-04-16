<?php

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Enums\Modality;
use App\Enums\PositionStatus;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    public function definition(): array
    {
        $minSalary = fake()->randomElement([15000, 20000, 25000, 30000, 40000, 50000]);
        $maxSalary = $minSalary + fake()->randomElement([8000, 12000, 18000, 25000]);

        return [
            'client_id' => Client::factory(),
            'title' => fake()->randomElement([
                'Software Engineer', 'Frontend Developer', 'Backend Developer',
                'DevOps Engineer', 'Mechanical Engineer', 'Electronics Engineer',
                'Project Manager', 'Quality Engineer', 'Production Supervisor',
            ]),
            'description' => fake()->paragraphs(2, true),
            'requirements' => "- " . implode("\n- ", fake()->sentences(5)),
            'location' => fake()->randomElement(['Tijuana', 'Ensenada', 'Mexicali', 'Rosarito']),
            'salary_min' => $minSalary,
            'salary_max' => $maxSalary,
            'salary_currency' => 'MXN',
            'employment_type' => fake()->randomElement([
                EmploymentType::FULL_TIME,
                EmploymentType::PART_TIME,
                EmploymentType::INTERNSHIP,
                EmploymentType::CONTRACT,
            ]),
            'modality' => fake()->randomElement([
                Modality::ON_SITE,
                Modality::REMOTE,
                Modality::HYBRID,
            ]),
            'status' => PositionStatus::OPEN,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => PositionStatus::DRAFT]);
    }

    public function closed(): static
    {
        return $this->state(['status' => PositionStatus::CLOSED]);
    }

    public function forClient(Client $client): static
    {
        return $this->state(['client_id' => $client->id]);
    }
}
