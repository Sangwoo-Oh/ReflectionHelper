<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::all();
        $searchKeywords = [];
        foreach ($keywords as $keyword) {
            $searchKeywords[$keyword->id] = $keyword->searchKeywords;
        }
        return view('keywords.index', compact('keywords', 'searchKeywords'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        // dd($validatedData);

        // dd(auth()->id());
        $keyword = Keyword::create([
            'keyword' => $validatedData['keyword'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('keywords.index')->with('success', 'Keyword created successfully.');
    }

    public function show(Keyword $keyword)
    {
        $keyword = Keyword::with('searchKeywords')->findOrFail($keyword->id);
        return view('keywords.show', compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword)
    {
        //
    }

    public function destroy(Keyword $keyword)
    {
        //
    }

    public function create()
    {
        //
    }

    public function edit(Keyword $keyword)
    {
        //
    }
}
