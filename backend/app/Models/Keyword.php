<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    /** @use HasFactory<\Database\Factories\KeywordFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function searchKeywords()
    {
        return $this->hasMany(SearchKeyword::class);
    }

    protected $fillable = ['keyword', 'user_id'];
}
