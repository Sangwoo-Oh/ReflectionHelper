<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function calendarSyncToken()
    {
        return $this->hasOne(CalendarSyncToken::class);
    }
    protected $fillable = [
        'user_id',
        'calendar_id',
        'summary',
        'description',
        'time_zone',
        'calendar_etag',
        'list_events_etag',
    ];
}
