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
}

