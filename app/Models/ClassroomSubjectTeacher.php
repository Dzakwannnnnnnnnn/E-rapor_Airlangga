<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomSubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'classroom_subject_teacher';

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'academic_year_id',
        'is_submitted',
    ];

    protected $casts = [
        'classroom_id' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'academic_year_id' => 'integer',
        'is_submitted' => 'boolean',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'classroom_subject_teacher_id');
    }

    /**
     * Generate default assessment templates for this assignment if they do not exist.
     */
    public function generateDefaultAssessments(): void
    {
        if ($this->assessments()->exists()) {
            return;
        }

        // UH 1, 2, 3 — each weighs 12.5% (total 37.5%)
        foreach (range(1, 3) as $i) {
            $this->assessments()->create([
                'type'        => 'uh',
                'name'        => "UH $i",
                'date'        => now()->toDateString(),
                'weight'      => 12.50,
                'description' => "Ulangan Harian ke-$i",
                'sequence'    => $i,
            ]);
        }

        // Tugas 1, 2, 3 — each weighs 8.33% (total ~25%)
        foreach (range(1, 3) as $i) {
            $this->assessments()->create([
                'type'        => 'tugas',
                'name'        => "Tugas $i",
                'date'        => now()->toDateString(),
                'weight'      => 8.33,
                'description' => "Tugas ke-$i",
                'sequence'    => $i,
            ]);
        }

        // PAS — weighs 37.5%
        $semester = $this->academicYear?->semester ?? 1;
        $this->assessments()->create([
            'type'        => 'pas',
            'name'        => "PAS Semester $semester",
            'date'        => now()->toDateString(),
            'weight'      => 37.50,
            'description' => 'Penilaian Akhir Semester',
            'sequence'    => 1,
        ]);
    }
}
