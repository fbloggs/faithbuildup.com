<?php

namespace App\Models;

class Timelineresponse extends Model
{
    protected $table = 'timelineresponses';
    protected $guarded = [];

    public function scopeTimeLineExists($query, $user_id) {
        return $query->where('user_id', $user_id)->exists();

    }

}
