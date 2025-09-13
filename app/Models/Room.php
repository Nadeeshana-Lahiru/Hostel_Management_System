<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['hostel_id', 'room_number', 'floor', 'capacity'];

    // A Room belongs to one Hostel
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    // A Room can have many Students
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}