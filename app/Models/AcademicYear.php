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
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}
