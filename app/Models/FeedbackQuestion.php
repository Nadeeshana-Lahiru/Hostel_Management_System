<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['question_text', 'is_active'];

    public function responses()
    {
        return $this->hasMany(FeedbackResponse::class);
    }
}