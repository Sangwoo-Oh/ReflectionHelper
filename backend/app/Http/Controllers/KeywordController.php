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

    public function edit(Keyword $keyword)
    {
        $keyword = Keyword::with('searchKeywords')->findOrFail($keyword->id);
        return view('keywords.edit', compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword)
    {
        $validatedData = $request->validate([
            'keyword' => 'required|string|max:255',
            'search_words' => 'array',
            'search_words.*' => 'string|max:255',
        ]);

        // Update the keyword
        $keyword->update([
            'keyword' => $validatedData['keyword'],
        ]);

        // Update keyword
        if (isset($validatedData['search_words'])) {
            foreach ($validatedData['search_words'] as $id => $searchWord) {
                $searchKeyword = $keyword->searchKeywords()->where('id', $id)->first();
                if ($searchKeyword) {
                    $searchKeyword->update(['search_keyword' => $searchWord]);
                }
            }
        }

        return redirect()->route('keywords.index')->with('success', 'Keyword and search keywords updated successfully.');
    }

    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
        return redirect()->route('keywords.index')->with('success', 'Keyword ' . $keyword->keyword . ' deleted successfully.');
    }

    public function create()
    {
        //
    }

}
