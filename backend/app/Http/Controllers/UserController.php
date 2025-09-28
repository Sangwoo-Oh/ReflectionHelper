<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function destroy(Request $request)
    {
        $request->user()->delete();

        return redirect('/')->with('status', 'アカウントが削除されました。');
    }
}
