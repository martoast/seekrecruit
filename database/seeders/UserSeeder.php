<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Models\CandidateProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create JAE Staff (Admin) users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@seekrecruit.com',
            'password' => Hash::make('password'),
            'role' => UserRole::JAE_STAFF,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@seekrecruit.com',
            'password' => Hash::make('password'),
            'role' => UserRole::JAE_STAFF,
            'email_verified_at' => now(),
        ]);

        // Create Candidate users with profiles
        $candidates = [
            [
                'user' => [
                    'name' => 'Juan Perez',
                    'email' => 'juan@example.com',
                ],
                'profile' => [
                    'university' => 'UABC',
                    'degree' => 'Computer Science',
                    'semester' => 8,
                    'graduation_year' => 2025,
                    'skills' => ['PHP', 'Laravel', 'Vue.js', 'MySQL'],
                    'location' => 'Tijuana',
                    'age' => 23,
                    'gender' => Gender::MALE,
                    'phone' => '+52 664 123 4567',
                    'linkedin_url' => 'https://linkedin.com/in/juanperez',
                    'bio' => 'Passionate software developer with experience in web technologies.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Ana Rodriguez',
                    'email' => 'ana@example.com',
                ],
                'profile' => [
                    'university' => 'CETYS',
                    'degree' => 'Software Engineering',
                    'semester' => 6,
                    'graduation_year' => 2026,
                    'skills' => ['JavaScript', 'React', 'Node.js', 'MongoDB'],
                    'location' => 'Mexicali',
                    'age' => 21,
                    'gender' => Gender::FEMALE,
                    'phone' => '+52 686 234 5678',
                    'linkedin_url' => 'https://linkedin.com/in/anarodriguez',
                    'bio' => 'Full-stack developer focused on modern JavaScript frameworks.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Carlos Martinez',
                    'email' => 'carlos@example.com',
                ],
                'profile' => [
                    'university' => 'UABC',
                    'degree' => 'Mechanical Engineering',
                    'semester' => 10,
                    'graduation_year' => 2025,
                    'skills' => ['AutoCAD', 'SolidWorks', 'MATLAB', 'Python'],
                    'location' => 'Tijuana',
                    'age' => 24,
                    'gender' => Gender::MALE,
                    'phone' => '+52 664 345 6789',
                    'linkedin_url' => 'https://linkedin.com/in/carlosmartinez',
                    'bio' => 'Mechanical engineer with strong CAD and programming skills.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Sofia Hernandez',
                    'email' => 'sofia@example.com',
                ],
                'profile' => [
                    'university' => 'ITT',
                    'degree' => 'Industrial Engineering',
                    'semester' => 7,
                    'graduation_year' => 2025,
                    'skills' => ['Lean Manufacturing', 'Six Sigma', 'Excel', 'SAP'],
                    'location' => 'Tijuana',
                    'age' => 22,
                    'gender' => Gender::FEMALE,
                    'phone' => '+52 664 456 7890',
                    'linkedin_url' => 'https://linkedin.com/in/sofiahernandez',
                    'bio' => 'Industrial engineer passionate about process optimization.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Diego Lopez',
                    'email' => 'diego@example.com',
                ],
                'profile' => [
                    'university' => 'UABC',
                    'degree' => 'Electronic Engineering',
                    'semester' => 9,
                    'graduation_year' => 2025,
                    'skills' => ['Embedded Systems', 'C/C++', 'Arduino', 'PCB Design'],
                    'location' => 'Ensenada',
                    'age' => 23,
                    'gender' => Gender::MALE,
                    'phone' => '+52 646 567 8901',
                    'linkedin_url' => 'https://linkedin.com/in/diegolopez',
                    'bio' => 'Electronics enthusiast with hands-on experience in embedded systems.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Laura Sanchez',
                    'email' => 'laura@example.com',
                ],
                'profile' => [
                    'university' => 'CETYS',
                    'degree' => 'Computer Science',
                    'semester' => 4,
                    'graduation_year' => 2027,
                    'skills' => ['Python', 'Django', 'PostgreSQL', 'Docker'],
                    'location' => 'Tijuana',
                    'age' => 20,
                    'gender' => Gender::FEMALE,
                    'phone' => '+52 664 678 9012',
                    'linkedin_url' => 'https://linkedin.com/in/laurasanchez',
                    'bio' => 'Backend developer interested in cloud technologies and DevOps.',
                ],
            ],
            [
                'user' => [
                    'name' => 'Roberto Gomez',
                    'email' => 'roberto@example.com',
                ],
                'profile' => [
                    'university' => 'ITT',
                    'degree' => 'Mechatronics Engineering',
                    'semester' => 8,
                    'graduation_year' => 2025,
                    'skills' => ['PLC Programming', 'Robotics', 'LabVIEW', 'Python'],
                    'location' => 'Tijuana',
                    'age' => 23,
                    'gender' => Gender::MALE,
                    'phone' => '+52 664 789 0123',
                    'linkedin_url' => null,
                    'bio' => 'Mechatronics engineer with experience in industrial automation.',
                ],
            ],
        ];

        foreach ($candidates as $data) {
            $user = User::create([
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'password' => Hash::make('password'),
                'role' => UserRole::CANDIDATE,
                'email_verified_at' => now(),
            ]);

            CandidateProfile::create([
                'user_id' => $user->id,
                ...$data['profile'],
            ]);
        }
    }
}
