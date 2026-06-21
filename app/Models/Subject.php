<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'kkm',
    ];

    protected $casts = [
        'kkm' => 'integer',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function classroomSubjectTeachers()
    {
        return $this->hasMany(ClassroomSubjectTeacher::class);
    }
}
