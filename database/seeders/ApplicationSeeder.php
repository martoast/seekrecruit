<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\InterviewType;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\CandidateProfile;
use App\Models\Client;
use App\Models\Interview;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNamedCandidateStories();
        $this->seedPerStatusCoverage();
    }

    /**
     * The original story arcs for the 7 named candidates — feature tests and the
     * admin dashboard "recent applications" panel depend on these being present.
     */
    private function seedNamedCandidateStories(): void
    {
        $admin = User::where('email', 'admin@seekrecruit.com')->first();
        $maria = User::where('email', 'maria@seekrecruit.com')->first();
        $jorge = User::where('email', 'jorge@acme.com')->first();

        $juan = $this->candidate('juan@example.com');
        $ana = $this->candidate('ana@example.com');
        $carlos = $this->candidate('carlos@example.com');
        $sofia = $this->candidate('sofia@example.com');
        $diego = $this->candidate('diego@example.com');
        $laura = $this->candidate('laura@example.com');
        $roberto = $this->candidate('roberto@example.com');

        $juniorDev = $this->position('Junior Software Developer');
        $mechEng = $this->position('Mechanical Engineer');
        $industrialIntern = $this->position('Industrial Engineer Intern');
        $fullStack = $this->position('Full Stack Developer');
        $electronics = $this->position('Electronics Technician');
        $qaEngineer = $this->position('Quality Assurance Engineer');

        // Juan — JAE interview in progress
        $app1 = Application::create([
            'candidate_id' => $juan->id,
            'position_id' => $juniorDev->id,
            'status' => ApplicationStatus::INTERVIEW,
            'created_at' => now()->subDays(14),
        ]);
        Interview::create(['application_id' => $app1->id, 'scheduled_at' => now()->addDays(2)->setHour(10), 'location' => 'Conference Room A', 'type' => InterviewType::TECHNICAL, 'notes' => 'Focus on PHP and Laravel knowledge']);
        Interview::create(['application_id' => $app1->id, 'scheduled_at' => now()->addDays(5)->setHour(14), 'location' => 'HR Office', 'type' => InterviewType::HR, 'notes' => null]);
        ApplicationNote::create(['application_id' => $app1->id, 'author_id' => $admin->id, 'content' => 'Strong technical background. Good portfolio with Laravel projects.', 'created_at' => now()->subDays(10)]);
        ApplicationNote::create(['application_id' => $app1->id, 'author_id' => $maria->id, 'content' => 'Moved to interview stage. Schedule technical interview first.', 'created_at' => now()->subDays(5)]);

        // Juan — also applied to Acme (registered)
        Application::create([
            'candidate_id' => $juan->id,
            'position_id' => $fullStack->id,
            'status' => ApplicationStatus::REGISTERED,
            'created_at' => now()->subDays(2),
        ]);

        // Ana — JAE preselected
        $app3 = Application::create(['candidate_id' => $ana->id, 'position_id' => $juniorDev->id, 'status' => ApplicationStatus::PRESELECTED, 'created_at' => now()->subDays(7)]);
        ApplicationNote::create(['application_id' => $app3->id, 'author_id' => $admin->id, 'content' => 'Excellent React skills. Consider for frontend-heavy projects.', 'created_at' => now()->subDays(5)]);

        // Ana — Acme finalist
        $app4 = Application::create(['candidate_id' => $ana->id, 'position_id' => $fullStack->id, 'status' => ApplicationStatus::FINALIST, 'created_at' => now()->subDays(21)]);
        Interview::create(['application_id' => $app4->id, 'scheduled_at' => now()->subDays(14)->setHour(10), 'location' => 'Conference Room B', 'type' => InterviewType::TECHNICAL, 'notes' => 'Passed technical interview with flying colors.']);
        Interview::create(['application_id' => $app4->id, 'scheduled_at' => now()->subDays(7)->setHour(11), 'location' => 'HR Office', 'type' => InterviewType::HR, 'notes' => 'Great cultural fit. Salary expectations aligned.']);
        Interview::create(['application_id' => $app4->id, 'scheduled_at' => now()->addDays(1)->setHour(15), 'location' => 'Director Office', 'type' => InterviewType::FINAL, 'notes' => 'Final interview with CTO']);
        ApplicationNote::create(['application_id' => $app4->id, 'author_id' => $jorge->id, 'content' => 'Top candidate for this position. Strong full-stack skills.', 'created_at' => now()->subDays(10)]);

        // Carlos — JAE evaluation
        $app5 = Application::create(['candidate_id' => $carlos->id, 'position_id' => $mechEng->id, 'status' => ApplicationStatus::EVALUATION, 'created_at' => now()->subDays(18)]);
        Interview::create(['application_id' => $app5->id, 'scheduled_at' => now()->subDays(10)->setHour(9), 'location' => 'Engineering Lab', 'type' => InterviewType::TECHNICAL, 'notes' => 'CAD test completed successfully.']);
        ApplicationNote::create(['application_id' => $app5->id, 'author_id' => $admin->id, 'content' => 'Strong SolidWorks skills. Needs to complete practical assessment.', 'created_at' => now()->subDays(8)]);

        // Sofia — JAE hired
        $app6 = Application::create(['candidate_id' => $sofia->id, 'position_id' => $industrialIntern->id, 'status' => ApplicationStatus::HIRED, 'created_at' => now()->subDays(30)]);
        Interview::create(['application_id' => $app6->id, 'scheduled_at' => now()->subDays(25)->setHour(10), 'location' => 'Conference Room A', 'type' => InterviewType::HR, 'notes' => 'Very motivated candidate.']);
        ApplicationNote::create(['application_id' => $app6->id, 'author_id' => $maria->id, 'content' => 'Hired! Start date: Next Monday. Assigned to Production team.', 'created_at' => now()->subDays(5)]);

        // Diego — TJE discarded
        $app7 = Application::create(['candidate_id' => $diego->id, 'position_id' => $electronics->id, 'status' => ApplicationStatus::DISCARDED, 'created_at' => now()->subDays(25)]);
        ApplicationNote::create(['application_id' => $app7->id, 'author_id' => $admin->id, 'content' => 'Good technical skills but location is too far. Candidate declined relocation.', 'created_at' => now()->subDays(20)]);

        // Diego — JAE registered
        Application::create(['candidate_id' => $diego->id, 'position_id' => $juniorDev->id, 'status' => ApplicationStatus::REGISTERED, 'created_at' => now()->subDays(1)]);

        // Laura — JAE interview
        $app9 = Application::create(['candidate_id' => $laura->id, 'position_id' => $juniorDev->id, 'status' => ApplicationStatus::INTERVIEW, 'created_at' => now()->subDays(10)]);
        Interview::create(['application_id' => $app9->id, 'scheduled_at' => now()->addDays(3)->setHour(11), 'location' => 'Zoom — link will be sent', 'type' => InterviewType::TECHNICAL, 'notes' => 'Remote interview — test Python and Django skills']);
        ApplicationNote::create(['application_id' => $app9->id, 'author_id' => $admin->id, 'content' => 'Interesting profile. Strong Python background despite being in 4th semester.', 'created_at' => now()->subDays(8)]);

        // Laura — Acme preselected
        $app10 = Application::create(['candidate_id' => $laura->id, 'position_id' => $fullStack->id, 'status' => ApplicationStatus::PRESELECTED, 'created_at' => now()->subDays(5)]);
        ApplicationNote::create(['application_id' => $app10->id, 'author_id' => $jorge->id, 'content' => 'Good Docker/DevOps skills. Could be a good fit for the team.', 'created_at' => now()->subDays(3)]);

        // Roberto — Acme evaluation
        $app11 = Application::create(['candidate_id' => $roberto->id, 'position_id' => $qaEngineer->id, 'status' => ApplicationStatus::EVALUATION, 'created_at' => now()->subDays(12)]);
        Interview::create(['application_id' => $app11->id, 'scheduled_at' => now()->subDays(5)->setHour(14)->setMinute(30), 'location' => 'Quality Lab', 'type' => InterviewType::TECHNICAL, 'notes' => 'Practical test on quality procedures completed.']);
        ApplicationNote::create(['application_id' => $app11->id, 'author_id' => $admin->id, 'content' => 'Mechatronics background is interesting for QA automation. Waiting for test results.', 'created_at' => now()->subDays(4)]);
    }

    /**
     * Ensure every client has several applications at every pipeline status,
     * so admin filters, KPIs, and stats dashboards all have data to render.
     */
    private function seedPerStatusCoverage(): void
    {
        $statuses = [
            ApplicationStatus::REGISTERED,
            ApplicationStatus::PRESELECTED,
            ApplicationStatus::INTERVIEW,
            ApplicationStatus::EVALUATION,
            ApplicationStatus::FINALIST,
            ApplicationStatus::HIRED,
            ApplicationStatus::DISCARDED,
        ];

        // Candidates generated by the factory are used here. Named seed candidates
        // are excluded so their story arcs (above) stay pristine.
        $namedEmails = [
            'juan@example.com', 'ana@example.com', 'carlos@example.com',
            'sofia@example.com', 'diego@example.com', 'laura@example.com',
            'roberto@example.com',
        ];

        $extraCandidates = CandidateProfile::whereHas('user', function ($q) use ($namedEmails) {
            $q->whereNotIn('email', $namedEmails)->whereNotNull('client_id')->orWhere(function ($inner) use ($namedEmails) {
                $inner->whereNotIn('email', $namedEmails)->whereNull('client_id');
            });
        })->get();

        foreach (Client::all() as $client) {
            $openPositions = Position::where('client_id', $client->id)
                ->where('status', 'open')
                ->get();

            if ($openPositions->isEmpty()) {
                continue;
            }

            $hrAdmin = User::where('client_id', $client->id)
                ->where('role', UserRole::HR_ADMIN->value)
                ->first();
            $author = $hrAdmin ?: User::where('role', UserRole::SUPER_ADMIN->value)->first();

            foreach ($statuses as $status) {
                $targetCount = fake()->numberBetween(2, 3);

                for ($i = 0; $i < $targetCount; $i++) {
                    if ($extraCandidates->isEmpty()) {
                        break 2;
                    }

                    $candidate = $extraCandidates->random();
                    $position = $openPositions->random();

                    if (Application::where('candidate_id', $candidate->id)
                        ->where('position_id', $position->id)
                        ->exists()) {
                        continue;
                    }

                    $createdAt = now()->subDays(fake()->numberBetween(1, 90));

                    $application = Application::create([
                        'candidate_id' => $candidate->id,
                        'position_id' => $position->id,
                        'status' => $status,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt->copy()->addDays(fake()->numberBetween(0, 14)),
                    ]);

                    $this->attachInterviews($application, $status);
                    $this->attachNotes($application, $status, $author);
                }
            }
        }
    }

    private function attachInterviews(Application $application, ApplicationStatus $status): void
    {
        $pastPipeline = [
            ApplicationStatus::EVALUATION,
            ApplicationStatus::FINALIST,
            ApplicationStatus::HIRED,
            ApplicationStatus::DISCARDED,
        ];

        $count = match ($status) {
            ApplicationStatus::INTERVIEW => fake()->numberBetween(1, 2),
            ApplicationStatus::EVALUATION, ApplicationStatus::FINALIST => fake()->numberBetween(2, 3),
            ApplicationStatus::HIRED => fake()->numberBetween(2, 3),
            ApplicationStatus::DISCARDED => fake()->numberBetween(0, 1),
            default => 0,
        };

        for ($i = 0; $i < $count; $i++) {
            if (in_array($status, $pastPipeline, true)) {
                Interview::factory()->past()->create(['application_id' => $application->id]);
            } elseif ($status === ApplicationStatus::INTERVIEW) {
                $i === 0
                    ? Interview::factory()->upcoming()->create(['application_id' => $application->id])
                    : Interview::factory()->past()->create(['application_id' => $application->id]);
            }
        }
    }

    private function attachNotes(Application $application, ApplicationStatus $status, User $author): void
    {
        $count = match ($status) {
            ApplicationStatus::REGISTERED => 0,
            ApplicationStatus::PRESELECTED => fake()->numberBetween(1, 2),
            ApplicationStatus::INTERVIEW, ApplicationStatus::EVALUATION => fake()->numberBetween(2, 3),
            ApplicationStatus::FINALIST, ApplicationStatus::HIRED => fake()->numberBetween(2, 4),
            ApplicationStatus::DISCARDED => fake()->numberBetween(1, 2),
        };

        ApplicationNote::factory()
            ->count($count)
            ->for($application)
            ->state(['author_id' => $author->id])
            ->create();
    }

    private function candidate(string $email): CandidateProfile
    {
        return CandidateProfile::whereHas('user', fn ($q) => $q->where('email', $email))->firstOrFail();
    }

    private function position(string $title): Position
    {
        return Position::where('title', $title)->firstOrFail();
    }
}
