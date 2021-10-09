<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quizresponse extends Model
{
    protected $table = 'quizresponses';
    protected $guarded = [];


    public function scopeQuizExists($query, $user_id) {

        $quizid = 1;
        return $query->where('quiz_id', $quizid)->where('user_id', $user_id)->exists();

    }
}
