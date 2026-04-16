<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CandidateProfile>
 */
class CandidateProfileFactory extends Factory
{
    private const UNIVERSITIES = [
        'UABC', 'CETYS', 'ITT', 'Universidad Xochicalco',
        'UdeG Tijuana', 'Universidad Iberoamericana Tijuana',
    ];

    private const DEGREES = [
        'Computer Science', 'Software Engineering', 'Mechanical Engineering',
        'Industrial Engineering', 'Electronic Engineering', 'Mechatronics Engineering',
        'Chemical Engineering', 'Biomedical Engineering', 'Civil Engineering',
    ];

    private const LOCATIONS = ['Tijuana', 'Ensenada', 'Mexicali', 'Rosarito', 'Tecate'];

    private const SKILLS_POOL = [
        'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Vue.js', 'React', 'Node.js',
        'Python', 'Django', 'Flask', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
        'Docker', 'Kubernetes', 'AWS', 'Git', 'Linux', 'Bash',
        'AutoCAD', 'SolidWorks', 'MATLAB', 'LabVIEW', 'PLC Programming',
        'Lean Manufacturing', 'Six Sigma', 'ISO 9001',
        'Embedded Systems', 'Arduino', 'Raspberry Pi', 'PCB Design',
        'C/C++', 'Java', 'Kotlin', 'Swift', 'Excel', 'Power BI', 'SAP',
    ];

    private const AREA_CODES = ['664', '686', '646', '661'];

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->candidate(),
            'university' => fake()->randomElement(self::UNIVERSITIES),
            'degree' => fake()->randomElement(self::DEGREES),
            'semester' => fake()->numberBetween(3, 10),
            'graduation_year' => fake()->numberBetween(2024, 2028),
            'skills' => fake()->randomElements(self::SKILLS_POOL, fake()->numberBetween(3, 6)),
            'location' => fake()->randomElement(self::LOCATIONS),
            'age' => fake()->numberBetween(19, 32),
            'gender' => fake()->randomElement([Gender::MALE, Gender::FEMALE, Gender::PREFER_NOT_TO_SAY]),
            'phone' => '+52 ' . fake()->randomElement(self::AREA_CODES) . ' ' . fake()->numerify('### ####'),
            'linkedin_url' => fake()->boolean(70)
                ? 'https://linkedin.com/in/' . fake()->userName()
                : null,
            'bio' => fake()->boolean(60) ? fake()->paragraph(2) : null,
        ];
    }

    public function withoutCv(): static
    {
        return $this->state(['cv_path' => null]);
    }
}
