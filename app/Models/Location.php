<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'latitude',
        'longitude',
        'radius',
        'in_time',
        'out_time',
        'early_buffer',
        'late_buffer',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
