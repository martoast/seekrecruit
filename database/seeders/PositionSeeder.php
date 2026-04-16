<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Enums\Modality;
use App\Enums\PositionStatus;
use App\Models\Client;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $jae = Client::where('slug', 'jae-tijuana')->firstOrFail()->id;
        $acme = Client::where('slug', 'acme-engineering')->firstOrFail()->id;
        $tje = Client::where('slug', 'tijuana-electronics')->firstOrFail()->id;

        $positions = [
            // ==================== JAE Tijuana ====================
            [
                'client_id' => $jae,
                'title' => 'Junior Software Developer',
                'description' => 'We are looking for a motivated Junior Software Developer to join our growing team. You will work on exciting projects using modern technologies and learn from experienced developers.',
                'requirements' => "- Bachelor's degree in Computer Science or related field (or in progress)\n- Knowledge of at least one programming language (PHP, JavaScript, Python)\n- Understanding of web development fundamentals\n- Good communication skills\n- Eager to learn and grow",
                'location' => 'Tijuana',
                'salary_min' => 18000, 'salary_max' => 28000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::HYBRID,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $jae,
                'title' => 'Mechanical Engineer',
                'description' => 'Join our engineering team to design and develop mechanical systems for manufacturing. You will work with cutting-edge CAD software and collaborate with cross-functional teams.',
                'requirements' => "- Bachelor's degree in Mechanical Engineering\n- Proficiency in AutoCAD and SolidWorks\n- Knowledge of manufacturing processes\n- Strong analytical and problem-solving skills\n- Team player with good communication skills",
                'location' => 'Tijuana',
                'salary_min' => 35000, 'salary_max' => 50000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $jae,
                'title' => 'Industrial Engineer Intern',
                'description' => 'Great opportunity for industrial engineering students to gain hands-on experience in a manufacturing environment. Work on process improvement projects and learn lean manufacturing principles.',
                'requirements' => "- Currently enrolled in Industrial Engineering program\n- Basic knowledge of Lean/Six Sigma concepts\n- Proficiency in Microsoft Excel\n- Available to work at least 20 hours per week\n- Strong attention to detail",
                'location' => 'Mexicali',
                'salary_min' => 8000, 'salary_max' => 12000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::INTERNSHIP, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $jae,
                'title' => 'Production Supervisor',
                'description' => 'Lead a shift team on the production floor. Oversee daily output, coordinate with quality and maintenance, and drive continuous improvement initiatives.',
                'requirements' => "- 3+ years of experience leading production teams\n- Knowledge of Lean Manufacturing and TPM\n- Strong communication and conflict resolution skills\n- Bilingual English/Spanish preferred",
                'location' => 'Tijuana',
                'salary_min' => 28000, 'salary_max' => 42000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $jae,
                'title' => 'Plant Maintenance Technician',
                'description' => 'Keep our manufacturing equipment running at peak efficiency. Perform preventive maintenance, troubleshoot issues, and coordinate with engineering on upgrades.',
                'requirements' => "- Technical degree in Electromechanics or related field\n- Experience with industrial machinery\n- Knowledge of PLCs and pneumatics\n- Able to work rotating shifts",
                'location' => 'Tijuana',
                'salary_min' => 18000, 'salary_max' => 26000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::CLOSED,
            ],

            // ==================== Acme Engineering ====================
            [
                'client_id' => $acme,
                'title' => 'Full Stack Developer',
                'description' => 'We are seeking an experienced Full Stack Developer to lead development of our internal tools. You will architect solutions and mentor junior developers.',
                'requirements' => "- 2+ years of experience in web development\n- Strong knowledge of Laravel and Vue.js or React\n- Experience with relational databases\n- Understanding of RESTful API design\n- Experience with Git version control",
                'location' => 'Tijuana',
                'salary_min' => 45000, 'salary_max' => 70000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::REMOTE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $acme,
                'title' => 'Quality Assurance Engineer',
                'description' => 'Help us maintain the highest quality standards in our manufacturing processes. You will develop and implement QA procedures and work closely with production teams.',
                'requirements' => "- Bachelor's degree in Engineering\n- Knowledge of quality management systems (ISO 9001)\n- Experience with statistical analysis tools\n- Strong documentation skills\n- Attention to detail and analytical mindset",
                'location' => 'Tijuana',
                'salary_min' => 30000, 'salary_max' => 45000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $acme,
                'title' => 'Automotive Systems Engineer',
                'description' => 'Design and validate electronic control systems for automotive applications. Collaborate with US and European teams on global programs.',
                'requirements' => "- Bachelor's in Electrical/Electronic Engineering\n- Experience with CAN bus, AUTOSAR, or similar protocols\n- Familiarity with automotive functional safety (ISO 26262)\n- English B2 or higher",
                'location' => 'Tijuana',
                'salary_min' => 50000, 'salary_max' => 75000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::HYBRID,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $acme,
                'title' => 'CAD Designer',
                'description' => 'Translate engineering concepts into detailed 3D models and production-ready drawings. Work closely with R&D and manufacturing teams.',
                'requirements' => "- Technical degree in Industrial Design or Mechanical Engineering\n- Expert with SolidWorks or CATIA\n- GD&T knowledge\n- Portfolio of previous work",
                'location' => 'Tijuana',
                'salary_min' => 22000, 'salary_max' => 35000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::CONTRACT, 'modality' => Modality::HYBRID,
                'status' => PositionStatus::DRAFT,
            ],

            // ==================== Tijuana Electronics ====================
            [
                'client_id' => $tje,
                'title' => 'Electronics Technician',
                'description' => 'Support our R&D team with prototype development and testing. Great opportunity to work with embedded systems and IoT devices.',
                'requirements' => "- Degree in Electronics or related field\n- Experience with circuit design and PCB layout\n- Programming skills (C/C++, Arduino)\n- Hands-on experience with electronic test equipment\n- Problem-solving attitude",
                'location' => 'Ensenada',
                'salary_min' => 25000, 'salary_max' => 38000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::CONTRACT, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $tje,
                'title' => 'Firmware Engineer',
                'description' => 'Write embedded firmware for next-generation IoT products. Low-level work with microcontrollers, sensors, and wireless protocols.',
                'requirements' => "- Strong C/C++ for embedded platforms\n- Experience with ARM Cortex-M, ESP32, or similar\n- Familiar with RTOS concepts\n- Git + version control discipline",
                'location' => 'Ensenada',
                'salary_min' => 40000, 'salary_max' => 60000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::REMOTE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $tje,
                'title' => 'Test & Validation Engineer',
                'description' => 'Develop automated test benches and validate hardware-software integration for IoT devices before production.',
                'requirements' => "- Background in Electronics or Mechatronics\n- Python scripting for test automation\n- Experience with oscilloscopes, spectrum analyzers\n- Understanding of signal integrity",
                'location' => 'Tijuana',
                'salary_min' => 30000, 'salary_max' => 48000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::ON_SITE,
                'status' => PositionStatus::OPEN,
            ],
            [
                'client_id' => $tje,
                'title' => 'Data Analyst',
                'description' => 'We are drafting a data analyst role for Q3. This position is not yet published.',
                'requirements' => "- Bachelor's degree in Statistics, Mathematics, or related field\n- Proficiency in SQL and Python\n- Experience with data visualization tools\n- Strong analytical skills",
                'location' => 'Tijuana',
                'salary_min' => 35000, 'salary_max' => 55000, 'salary_currency' => 'MXN',
                'employment_type' => EmploymentType::FULL_TIME, 'modality' => Modality::HYBRID,
                'status' => PositionStatus::DRAFT,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
