@component('components.header')
@endcomponent
    <h1>キーワード管理ページ</h1>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">キーワード</th>
            <th scope="col">検索ワード</th>
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
            </tr>
            @endforeach
        </tbody>
        </table>
@component('components.footer')
@endcomponent
