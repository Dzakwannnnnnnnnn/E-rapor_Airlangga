<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Parents;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportReleaseTest extends TestCase
{
    use RefreshDatabase;

    private AcademicYear $academicYear;
    private Classroom $classroom;
    private Student $student1;
    private Student $student2;
    private User $parentUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create active academic year
        $this->academicYear = AcademicYear::create([
            'year' => '2026',
            'semester' => 'genap',
            'is_active' => true,
            'report_release_at' => now()->subHours(2), // Past release date by default
        ]);

        // Create classroom
        $this->classroom = Classroom::create([
            'name' => 'XI PPLG',
            'major' => 'PPLG',
        ]);

        // Create two students
        $this->student1 = Student::create([
            'nisn' => '1111111111',
            'name' => 'Student One',
            'classroom_id' => $this->classroom->id,
        ]);

        $this->student2 = Student::create([
            'nisn' => '2222222222',
            'name' => 'Student Two',
            'classroom_id' => $this->classroom->id,
        ]);

        // Create parent user and connect to student1
        $this->parentUser = User::create([
            'name' => 'Parent One',
            'email' => 'parent@test.com',
            'password' => bcrypt('password'),
            'role' => 'parent',
        ]);

        $parent = Parents::create([
            'user_id' => $this->parentUser->id,
            'telp' => '08123456789',
            'relation' => 'ayah',
        ]);

        $parent->students()->attach($this->student1->id);
    }

    public function test_welcome_page_when_release_date_in_future(): void
    {
        // Set release date in the future
        $this->academicYear->update([
            'report_release_at' => now()->addDays(2),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('countdown-active-wrapper');
        $response->assertSee('Pembagian');
    }

    public function test_welcome_page_when_release_passed_but_not_all_validated(): void
    {
        // Release date is in the past (setUp sets it to 2 hours ago)
        // Let's create report cards. Student 1 validated, Student 2 not validated.
        ReportCard::create([
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 85.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        ReportCard::create([
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 80.00,
            'is_validated' => false,
            'is_submitted' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Menunggu Pengesahan');
        $response->assertSee('Tenggat rilis telah lewat, namun rapor masih dalam proses pengesahan oleh Kepala Sekolah.');
        $response->assertDontSee('Rapor Digital Telah Tersedia');
    }

    public function test_welcome_page_when_release_passed_and_all_validated(): void
    {
        // Both students validated
        ReportCard::create([
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 85.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        ReportCard::create([
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 80.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Rapor Digital Telah Tersedia');
        $response->assertDontSee('Menunggu Pengesahan');
    }

    public function test_parent_cannot_view_unreleased_or_unvalidated_report(): void
    {
        // Report for Student 1 is validated
        $rc = ReportCard::create([
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 85.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        // Student 2 is NOT validated
        ReportCard::create([
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 80.00,
            'is_validated' => false,
            'is_submitted' => true,
        ]);

        // Try accessing parent report list
        $response = $this->actingAs($this->parentUser)
            ->withSession(['active_role' => 'parent'])
            ->get('/parent/report');

        $response->assertStatus(200);
        $response->assertSee('Tenggat rilis telah lewat, namun rapor masih dalam proses pengesahan oleh Kepala Sekolah.');

        // Try viewing report card directly
        $responseView = $this->actingAs($this->parentUser)
            ->withSession(['active_role' => 'parent'])
            ->get("/parent/report/{$rc->id}");

        $responseView->assertStatus(403);
    }

    public function test_parent_can_view_released_and_all_validated_report(): void
    {
        // Both students validated
        $rc = ReportCard::create([
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 85.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        ReportCard::create([
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->academicYear->id,
            'final_score' => 80.00,
            'is_validated' => true,
            'is_submitted' => true,
        ]);

        // Try accessing parent report list
        $response = $this->actingAs($this->parentUser)
            ->withSession(['active_role' => 'parent'])
            ->get('/parent/report');

        $response->assertStatus(200);
        $response->assertSee('Buka Dokumen Rapor');

        // Try viewing report card directly
        $responseView = $this->actingAs($this->parentUser)
            ->withSession(['active_role' => 'parent'])
            ->get("/parent/report/{$rc->id}");

        $responseView->assertStatus(200);
    }
}
