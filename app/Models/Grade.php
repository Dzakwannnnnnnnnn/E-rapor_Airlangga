<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Grade: stores the computed final score per student per subject per academic year.
 */
class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'classroom_subject_teacher_id',
        'final_score',
        'description',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroomSubjectTeacher()
    {
        return $this->belongsTo(ClassroomSubjectTeacher::class, 'classroom_subject_teacher_id');
    }
}
