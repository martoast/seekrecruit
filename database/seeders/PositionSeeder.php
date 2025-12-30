<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'title' => 'Junior Software Developer',
                'description' => 'We are looking for a motivated Junior Software Developer to join our growing team. You will work on exciting projects using modern technologies and learn from experienced developers.',
                'requirements' => "- Bachelor's degree in Computer Science or related field (or in progress)\n- Knowledge of at least one programming language (PHP, JavaScript, Python)\n- Understanding of web development fundamentals\n- Good communication skills\n- Eager to learn and grow",
                'location' => 'Tijuana',
                'is_active' => true,
            ],
            [
                'title' => 'Mechanical Engineer',
                'description' => 'Join our engineering team to design and develop mechanical systems for manufacturing. You will work with cutting-edge CAD software and collaborate with cross-functional teams.',
                'requirements' => "- Bachelor's degree in Mechanical Engineering\n- Proficiency in AutoCAD and SolidWorks\n- Knowledge of manufacturing processes\n- Strong analytical and problem-solving skills\n- Team player with good communication skills",
                'location' => 'Tijuana',
                'is_active' => true,
            ],
            [
                'title' => 'Industrial Engineer Intern',
                'description' => 'Great opportunity for industrial engineering students to gain hands-on experience in a manufacturing environment. Work on process improvement projects and learn lean manufacturing principles.',
                'requirements' => "- Currently enrolled in Industrial Engineering program\n- Basic knowledge of Lean/Six Sigma concepts\n- Proficiency in Microsoft Excel\n- Available to work at least 20 hours per week\n- Strong attention to detail",
                'location' => 'Mexicali',
                'is_active' => true,
            ],
            [
                'title' => 'Full Stack Developer',
                'description' => 'We are seeking an experienced Full Stack Developer to lead development of our internal tools. You will architect solutions and mentor junior developers.',
                'requirements' => "- 2+ years of experience in web development\n- Strong knowledge of Laravel and Vue.js or React\n- Experience with relational databases\n- Understanding of RESTful API design\n- Experience with Git version control",
                'location' => 'Tijuana',
                'is_active' => true,
            ],
            [
                'title' => 'Quality Assurance Engineer',
                'description' => 'Help us maintain the highest quality standards in our manufacturing processes. You will develop and implement QA procedures and work closely with production teams.',
                'requirements' => "- Bachelor's degree in Engineering\n- Knowledge of quality management systems (ISO 9001)\n- Experience with statistical analysis tools\n- Strong documentation skills\n- Attention to detail and analytical mindset",
                'location' => 'Tijuana',
                'is_active' => true,
            ],
            [
                'title' => 'Electronics Technician',
                'description' => 'Support our R&D team with prototype development and testing. Great opportunity to work with embedded systems and IoT devices.',
                'requirements' => "- Degree in Electronics or related field\n- Experience with circuit design and PCB layout\n- Programming skills (C/C++, Arduino)\n- Hands-on experience with electronic test equipment\n- Problem-solving attitude",
                'location' => 'Ensenada',
                'is_active' => true,
            ],
            [
                'title' => 'Data Analyst (Inactive Position)',
                'description' => 'This position has been filled but we may reopen it in the future.',
                'requirements' => "- Bachelor's degree in Statistics, Mathematics, or related field\n- Proficiency in SQL and Python\n- Experience with data visualization tools\n- Strong analytical skills",
                'location' => 'Tijuana',
                'is_active' => false,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
