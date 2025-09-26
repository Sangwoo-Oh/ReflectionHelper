<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->timezone(auth()->user()->calendar->time_zone);
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->timezone(auth()->user()->calendar->time_zone);
    }
}
