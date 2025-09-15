<?php

namespace App\Http\Controllers;

use App\Models\SearchKeyword;
use Illuminate\Http\Request;

class SearchKeywordController extends Controller
{
    function store(Request $request)
    {
        $validatedData = $request->validate([
            'keyword_id' => 'required|exists:keywords,id',
            'search_keyword' => 'required|string|max:255',
        ]);

        SearchKeyword::create([
            'keyword_id' => $validatedData['keyword_id'],
            'search_keyword' => $validatedData['search_keyword'],
        ]);     
        return redirect()->back()->with('success', 'Search keyword added successfully.');
    }
}
