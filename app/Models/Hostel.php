<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'number_of_rooms', 'warden_id'];

    // A Hostel is managed by one Warden
    public function warden()
    {
        return $this->belongsTo(Warden::class);
    }

    // A Hostel has many Rooms
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function students()
    {
        // This is a powerful Eloquent relationship that connects Hostel to Student via the Room model.
        return $this->hasManyThrough(Student::class, Room::class);
    }
}