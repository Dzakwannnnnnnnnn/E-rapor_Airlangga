<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'name',
        'classroom_id',
        'parent_id',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function gradeEntries()
    {
        return $this->hasMany(GradeEntry::class);
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

