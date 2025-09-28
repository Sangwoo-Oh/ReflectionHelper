@extends('layouts')

@section('title', 'キーワード編集')

@section('content')
    <h1>キーワード編集画面</h1>
    <form action="{{ route('keywords.update', $keyword->id) }}" method="POST">
        @csrf
        @method('PUT')
        <h2>キーワード</h2>
        <div class="mb-3 d-flex align-items-end">
            <div class="flex-grow-1">
                <input type="text" class="form-control" id="keyword" name="keyword" value="{{ $keyword->keyword }}">
            </div>
        </div>
        <h2>検索ワード</h2>
        @foreach ($keyword->searchKeywords as $searchKeyword)
        <div class="d-flex align-items-end">
            <div class="flex-grow-1">
                <input type="text" class="mb-3 form-control" id="search_words" name="search_words[{{ $searchKeyword->id }}]" value="{{ $searchKeyword->search_keyword }}" aria-label="検索ワード">
            </div>
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">変更を保存</button>
    </form>

@endsection