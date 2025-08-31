<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::all();
        // dd($keywords);
        // return view('keywords.index', compact('keywords'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Keyword $keyword)
    {
        //
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
