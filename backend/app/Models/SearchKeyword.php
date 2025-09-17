<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchKeyword extends Model
{
    /** @use HasFactory<\Database\Factories\SearchKeywordFactory> */
    use HasFactory;

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }

    protected $fillable = ['search_keyword', 'keyword_id'];
}
