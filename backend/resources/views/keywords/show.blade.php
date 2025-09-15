@component('components.header')
@endcomponent
    <h1>キーワード詳細ページ</h1>
    <h2>キーワード: {{ $keyword->keyword }}</h2>
    <h2>関連する検索ワード</h2>
    <ul>
        @foreach ($keyword->searchKeywords as $searchKeyword)
            <li>{{ $searchKeyword->search_keyword }}</li>
        @endforeach
    </ul>
@component('components.footer')
@endcomponent
