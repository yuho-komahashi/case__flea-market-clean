<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
    // 認証処理（Fortifyの代わりに）
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $redirectTo = $request->input('redirect_to');
            if (!$redirectTo || $redirectTo === '' || $redirectTo === '/login'){
                $redirectTo = route('items.index');
            }
            return redirect($redirectTo);
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません'
        ])->withInput();
    }

    public function create()
    {
        return view('auth.login');
    }
}
