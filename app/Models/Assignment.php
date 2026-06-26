<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_subject_teacher_id',
        'title',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function classroomSubjectTeacher()
    {
        return $this->belongsTo(ClassroomSubjectTeacher::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
