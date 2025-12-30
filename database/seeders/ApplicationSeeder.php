<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\InterviewType;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\CandidateProfile;
use App\Models\Interview;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin users for notes
        $admin = User::where('email', 'admin@seekrecruit.com')->first();
        $maria = User::where('email', 'maria@seekrecruit.com')->first();

        // Get candidates
        $juan = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'juan@example.com'))->first();
        $ana = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'ana@example.com'))->first();
        $carlos = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'carlos@example.com'))->first();
        $sofia = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'sofia@example.com'))->first();
        $diego = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'diego@example.com'))->first();
        $laura = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'laura@example.com'))->first();
        $roberto = CandidateProfile::whereHas('user', fn ($q) => $q->where('email', 'roberto@example.com'))->first();

        // Get positions
        $juniorDev = Position::where('title', 'Junior Software Developer')->first();
        $mechEng = Position::where('title', 'Mechanical Engineer')->first();
        $industrialIntern = Position::where('title', 'Industrial Engineer Intern')->first();
        $fullStack = Position::where('title', 'Full Stack Developer')->first();
        $electronics = Position::where('title', 'Electronics Technician')->first();
        $qaEngineer = Position::where('title', 'Quality Assurance Engineer')->first();

        // ============================================
        // JUAN - 2 applications (Interview, Registered)
        // ============================================

        // Juan applied for Junior Dev - In interview stage
        $app1 = Application::create([
            'candidate_id' => $juan->id,
            'position_id' => $juniorDev->id,
            'status' => ApplicationStatus::INTERVIEW,
            'created_at' => now()->subDays(14),
        ]);

        Interview::create([
            'application_id' => $app1->id,
            'scheduled_at' => now()->addDays(2)->setHour(10)->setMinute(0),
            'location' => 'Conference Room A',
            'type' => InterviewType::TECHNICAL,
            'notes' => 'Focus on PHP and Laravel knowledge',
        ]);

        Interview::create([
            'application_id' => $app1->id,
            'scheduled_at' => now()->addDays(5)->setHour(14)->setMinute(0),
            'location' => 'HR Office',
            'type' => InterviewType::HR,
            'notes' => null,
        ]);

        ApplicationNote::create([
            'application_id' => $app1->id,
            'author_id' => $admin->id,
            'content' => 'Strong technical background. Good portfolio with Laravel projects.',
            'created_at' => now()->subDays(10),
        ]);

        ApplicationNote::create([
            'application_id' => $app1->id,
            'author_id' => $maria->id,
            'content' => 'Moved to interview stage. Schedule technical interview first.',
            'created_at' => now()->subDays(5),
        ]);

        // Juan also applied for Full Stack - Registered
        Application::create([
            'candidate_id' => $juan->id,
            'position_id' => $fullStack->id,
            'status' => ApplicationStatus::REGISTERED,
            'created_at' => now()->subDays(2),
        ]);

        // ============================================
        // ANA - 2 applications (Preselected, Finalist)
        // ============================================

        // Ana applied for Junior Dev - Preselected
        $app3 = Application::create([
            'candidate_id' => $ana->id,
            'position_id' => $juniorDev->id,
            'status' => ApplicationStatus::PRESELECTED,
            'created_at' => now()->subDays(7),
        ]);

        ApplicationNote::create([
            'application_id' => $app3->id,
            'author_id' => $admin->id,
            'content' => 'Excellent React skills. Consider for frontend-heavy projects.',
            'created_at' => now()->subDays(5),
        ]);

        // Ana applied for Full Stack - Finalist
        $app4 = Application::create([
            'candidate_id' => $ana->id,
            'position_id' => $fullStack->id,
            'status' => ApplicationStatus::FINALIST,
            'created_at' => now()->subDays(21),
        ]);

        Interview::create([
            'application_id' => $app4->id,
            'scheduled_at' => now()->subDays(14)->setHour(10)->setMinute(0),
            'location' => 'Conference Room B',
            'type' => InterviewType::TECHNICAL,
            'notes' => 'Passed technical interview with flying colors.',
        ]);

        Interview::create([
            'application_id' => $app4->id,
            'scheduled_at' => now()->subDays(7)->setHour(11)->setMinute(0),
            'location' => 'HR Office',
            'type' => InterviewType::HR,
            'notes' => 'Great cultural fit. Salary expectations aligned.',
        ]);

        Interview::create([
            'application_id' => $app4->id,
            'scheduled_at' => now()->addDays(1)->setHour(15)->setMinute(0),
            'location' => 'Director Office',
            'type' => InterviewType::FINAL,
            'notes' => 'Final interview with CTO',
        ]);

        ApplicationNote::create([
            'application_id' => $app4->id,
            'author_id' => $maria->id,
            'content' => 'Top candidate for this position. Strong full-stack skills.',
            'created_at' => now()->subDays(10),
        ]);

        // ============================================
        // CARLOS - 1 application (Evaluation)
        // ============================================

        $app5 = Application::create([
            'candidate_id' => $carlos->id,
            'position_id' => $mechEng->id,
            'status' => ApplicationStatus::EVALUATION,
            'created_at' => now()->subDays(18),
        ]);

        Interview::create([
            'application_id' => $app5->id,
            'scheduled_at' => now()->subDays(10)->setHour(9)->setMinute(0),
            'location' => 'Engineering Lab',
            'type' => InterviewType::TECHNICAL,
            'notes' => 'CAD test completed successfully.',
        ]);

        ApplicationNote::create([
            'application_id' => $app5->id,
            'author_id' => $admin->id,
            'content' => 'Strong SolidWorks skills. Needs to complete practical assessment.',
            'created_at' => now()->subDays(8),
        ]);

        // ============================================
        // SOFIA - 1 application (Hired!)
        // ============================================

        $app6 = Application::create([
            'candidate_id' => $sofia->id,
            'position_id' => $industrialIntern->id,
            'status' => ApplicationStatus::HIRED,
            'created_at' => now()->subDays(30),
        ]);

        Interview::create([
            'application_id' => $app6->id,
            'scheduled_at' => now()->subDays(25)->setHour(10)->setMinute(0),
            'location' => 'Conference Room A',
            'type' => InterviewType::HR,
            'notes' => 'Very motivated candidate.',
        ]);

        ApplicationNote::create([
            'application_id' => $app6->id,
            'author_id' => $maria->id,
            'content' => 'Hired! Start date: Next Monday. Assigned to Production team.',
            'created_at' => now()->subDays(5),
        ]);

        // ============================================
        // DIEGO - 2 applications (Discarded, Registered)
        // ============================================

        $app7 = Application::create([
            'candidate_id' => $diego->id,
            'position_id' => $electronics->id,
            'status' => ApplicationStatus::DISCARDED,
            'created_at' => now()->subDays(25),
        ]);

        ApplicationNote::create([
            'application_id' => $app7->id,
            'author_id' => $admin->id,
            'content' => 'Good technical skills but location is too far. Candidate declined relocation.',
            'created_at' => now()->subDays(20),
        ]);

        Application::create([
            'candidate_id' => $diego->id,
            'position_id' => $juniorDev->id,
            'status' => ApplicationStatus::REGISTERED,
            'created_at' => now()->subDays(1),
        ]);

        // ============================================
        // LAURA - 2 applications (Interview, Preselected)
        // ============================================

        $app9 = Application::create([
            'candidate_id' => $laura->id,
            'position_id' => $juniorDev->id,
            'status' => ApplicationStatus::INTERVIEW,
            'created_at' => now()->subDays(10),
        ]);

        Interview::create([
            'application_id' => $app9->id,
            'scheduled_at' => now()->addDays(3)->setHour(11)->setMinute(0),
            'location' => 'Zoom - link will be sent',
            'type' => InterviewType::TECHNICAL,
            'notes' => 'Remote interview - test Python and Django skills',
        ]);

        ApplicationNote::create([
            'application_id' => $app9->id,
            'author_id' => $admin->id,
            'content' => 'Interesting profile. Strong Python background despite being in 4th semester.',
            'created_at' => now()->subDays(8),
        ]);

        $app10 = Application::create([
            'candidate_id' => $laura->id,
            'position_id' => $fullStack->id,
            'status' => ApplicationStatus::PRESELECTED,
            'created_at' => now()->subDays(5),
        ]);

        ApplicationNote::create([
            'application_id' => $app10->id,
            'author_id' => $maria->id,
            'content' => 'Good Docker/DevOps skills. Could be a good fit for the team.',
            'created_at' => now()->subDays(3),
        ]);

        // ============================================
        // ROBERTO - 1 application (Evaluation)
        // ============================================

        $app11 = Application::create([
            'candidate_id' => $roberto->id,
            'position_id' => $qaEngineer->id,
            'status' => ApplicationStatus::EVALUATION,
            'created_at' => now()->subDays(12),
        ]);

        Interview::create([
            'application_id' => $app11->id,
            'scheduled_at' => now()->subDays(5)->setHour(14)->setMinute(30),
            'location' => 'Quality Lab',
            'type' => InterviewType::TECHNICAL,
            'notes' => 'Practical test on quality procedures completed.',
        ]);

        ApplicationNote::create([
            'application_id' => $app11->id,
            'author_id' => $admin->id,
            'content' => 'Mechatronics background is interesting for QA automation. Waiting for test results.',
            'created_at' => now()->subDays(4),
        ]);
    }
}
