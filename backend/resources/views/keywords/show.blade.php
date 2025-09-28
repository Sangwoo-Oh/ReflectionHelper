@extends('layouts')

@section('title', 'キーワード詳細')

@section('content')
    <h1>キーワード詳細画面</h1>
        @csrf
        @method('PUT')
        <h2>キーワード</h2>
        <div class="mb-3 d-flex align-items-center">
            <div class="flex-grow-1">
                {{ $keyword->keyword }}
            </div>
            <form action="{{ route('keywords.destroy', $keyword->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger ms-3">キーワード削除</button>
            </form>
        </div>
        <h2>検索ワード</h2>
        @foreach ($keyword->searchKeywords as $searchKeyword)
        <div class="d-flex align-items-cehter">
            <div class="flex-grow-1">
                {{ $searchKeyword->search_keyword }}
            </div>
            <form action="{{ route('search-keywords.destroy', $searchKeyword->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger ms-3">削除</button>
            </form>
        </div>
        @endforeach

@endsection