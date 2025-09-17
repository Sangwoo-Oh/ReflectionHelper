@extends('layouts')

@section('content')
    <h1>キーワード管理ページ</h1>
    @if(session('success'))
        @component('components.alert', ['type' => 'success'])
            {{ session('success') }}
        @endcomponent
    @endif
    @if($errors->any())
        @component('components.alert', ['type' => 'danger'])
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endcomponent
    @endif
    <form action="{{ route('keywords.store') }}" method="POST">
        <div class="d-flex align-items-end mb-3">
         @csrf
        <div class="flex-grow-1">
            <label for="keyword" class="form-label">キーワード追加</label>
            <input type="text" class="form-control" id="keyword" name="keyword">
        </div>
        <button type="submit" class="ms-3 btn btn-primary">追加</button>
        </div>
    </form>
    <form action="{{ route('search-keywords.store') }}" method="POST">
        <div class="d-flex align-items-end mb-3">
            @csrf
            <div class="flex-grow-1">
                <label for="new_keyword" class="form-label">検索ワード追加</label>
                <select class="form-select" name="keyword_id" id="keyword_id">
                    <option selected>キーワードを選択してください</option>
                    @foreach ($keywords as $keyword)
                        <option value="{{ $keyword->id }}">{{ $keyword->keyword }}</option>
                    @endforeach
                </select>
            </div>
            に
            <div class="flex-grow-1">
                <input type="text" class="form-control" id="search_keyword" name="search_keyword">
            </div>
            を
            <button type="submit" class="ms-3 btn btn-primary">追加</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">キーワード</th>
                <th scope="col">検索ワード</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keywords as $keyword)
            <tr>
                <th scope="row">{{ $loop->index + 1 }}</th>
                <td>{{ $keyword->keyword }}</td>
                <td>
                    @if (isset($searchKeywords[$keyword->id]))
                        {{ $searchKeywords[$keyword->id]->implode('search_keyword', ', ') }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('keywords.show', $keyword->id) }}" class="btn btn-secondary">詳細</a>
                    </div>
                </td>
                <td>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('keywords.edit', $keyword->id) }}" class="btn btn-secondary">編集</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection