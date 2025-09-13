<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        // Personal Info
        'nic', 'initial_name', 'full_name', 'address', 'dob', 'gender', 'nationality', 'religion', 'civil_status', 'district', 'province', 'gn_division', 'telephone_number',
        // Educational Info
        'reg_no', 'batch', 'faculty', 'department', 'course', 'year',
        // Parent/Guardian Info
        'guardian_name', 'guardian_relationship', 'guardian_dob', 'guardian_mobile', 'emergency_contact_name', 'emergency_contact_number',
        // Medical Info
        'medical_info',
    ];

    // A Student record belongs to a User for login
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A Student is assigned to one Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    //A Student can give many Feedback Responses
    public function feedbackResponses()
    {
        return $this->hasMany(FeedbackResponse::class);
    }
}