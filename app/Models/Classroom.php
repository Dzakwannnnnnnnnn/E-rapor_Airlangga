<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'major',
        'homeroom_teacher_id',
    ];

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'homeroom_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classroomSubjectTeachers()
    {
        return $this->hasMany(ClassroomSubjectTeacher::class);
    }
}
