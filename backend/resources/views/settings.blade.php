@extends('layouts')

@section('content')
    <h2>設定</h2>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <h4 class="mb-3">アカウント情報</h4>
                <p>アカウント: {{ auth()->user()->name }}</p>
                <p>メール: {{ auth()->user()->email }}</p>
                {{-- <form action="">
                    <h4 class="h4 mb-3">表示タイムゾーンの変更</h4>
                    <div class="d-flex align-items-end mb-3">
                        <div class="flex-grow-1">
                            <select name="timezone" class="form-select">
                                <option value="UTC">UTC</option>
                                <option value="Asia/Tokyo" selected>Asia/Tokyo</option>
                                <option value="America/New_York">America/New_York</option>
                                <option value="Europe/London">Europe/London</option>
                            </select>
                        </div>
                        <button type="submit" class="ms-3 btn btn-secondary">変更</button>
                    </div>
                </form> --}}
                <form action="{{ route('user.destroy') }}" method="POST" onsubmit="return confirm('本当にアカウントを削除しますか？この操作は元に戻せません。');">
                    @csrf
                    @method('DELETE')
                    <h4 class="h4 mb-3">アカウント削除</h4>
                    <p class="text-danger">アカウントを削除すると、関連するすべてのデータが失われます。この操作は元に戻せません。</p>
                    <button type="submit" class="btn btn-danger">上記の注意点を確認した上で削除する</button>
                </form>
            </div>
        </div>
    </div>
@endsection