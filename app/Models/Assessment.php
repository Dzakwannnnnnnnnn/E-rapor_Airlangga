<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_subject_teacher_id',
        'type',
        'name',
        'date',
        'weight',
        'description',
        'sequence',
    ];

    protected $casts = [
        'date'   => 'date',
        'weight' => 'decimal:2',
    ];

    public function classroomSubjectTeacher()
    {
        return $this->belongsTo(ClassroomSubjectTeacher::class, 'classroom_subject_teacher_id');
    }

    public function gradeEntries()
    {
        return $this->hasMany(GradeEntry::class);
    }

    /**
     * Get the grade entry for a specific student.
     */
    public function gradeEntryForStudent(int $studentId)
    {
        return $this->gradeEntries->firstWhere('student_id', $studentId);
    }

    /**
     * Human-readable label for assessment type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'uh'    => 'Ulangan Harian',
            'tugas' => 'Tugas',
            'pas'   => 'PAS',
            default => ucfirst($this->type),
        };
    }
}
