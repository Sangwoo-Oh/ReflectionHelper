<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    protected $fillable = [
        'calendar_id',
        'event_id',
        'summary',
        'description',
        'start_time',
        'end_time',
    ];

}
