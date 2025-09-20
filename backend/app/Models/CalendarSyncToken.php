<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarSyncToken extends Model
{
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    protected $fillable = [
        'calendar_id',
        'sync_token',
    ];
}
