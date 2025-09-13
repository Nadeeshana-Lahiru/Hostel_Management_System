<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warden extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'initial_name',
        'full_name',
        'nic',
        'gender',
        'address',
        'dob',
        'nationality',
        'civil_status',
        'district',
        'province',
        'telephone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A Warden is assigned to one Hostel
    public function hostel()
    {
        return $this->hasOne(Hostel::class);
    }
}