<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'gender',
        'telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function classroomAsHomeroom()
    {
        return $this->hasOne(Classroom::class, 'homeroom_teacher_id');
    }

    public function classroomSubjectTeachers()
    {
        return $this->hasMany(ClassroomSubjectTeacher::class);
    }

    /**
     * Tambahkan relasi ini agar sesuai dengan panggilan di TeacherAssignmentController
     */
    public function assignments()
    {
        return $this->hasMany(ClassroomSubjectTeacher::class, 'teacher_id');
    }
}
