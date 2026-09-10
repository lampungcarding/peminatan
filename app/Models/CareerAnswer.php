<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerAnswer extends Model
{
    use HasFactory;

    protected $table = 'career_answers';

    protected $fillable = [
        'user_id',
        'question_id',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(CareerQuestion::class, 'question_id');
    }
}
