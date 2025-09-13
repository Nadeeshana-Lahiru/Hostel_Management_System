<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'message',
        'image_path',
        'status',
        'admin_reply',
    ];

    // A complaint belongs to a single student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}