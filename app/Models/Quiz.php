<?php

namespace App\Models;

class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Quizdetail::class, 'quiz_id');
    }

     
}
