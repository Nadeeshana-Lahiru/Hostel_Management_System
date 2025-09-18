<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // The fields that are allowed to be mass-assigned.
    protected $fillable = [
        'user_id',
        'initial_name',
        'full_name',
        'nic',
        'email',
        'gender',
        'address',
        'dob',
        'nationality',
        'civil_status',
        'district',
        'province',
        'telephone',
    ];

    // Defines the relationship: an Admin profile belongs to a User.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}