<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'year',
        'semester',
        'is_active',
        'report_release_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'report_release_at' => 'datetime',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classroomSubjectTeachers()
    {
        return $this->hasMany(ClassroomSubjectTeacher::class);
    }
}
