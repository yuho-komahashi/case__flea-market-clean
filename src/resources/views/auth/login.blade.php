@extends('layouts.auth_common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
@endsection

@section('title','ログイン')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <div class="form__header">
            <h1 class="title">ログイン</h1>
        </div>

        <form class="login-form" action="{{ route('login.post') }}" method="post">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="email">メールアドレス</label>
                </div>
                <input class="form__input" type="email" name="email" id="email" value="{{ old('email')}}">
                <p class="form_error--message">
                    @error('email')
                    {{ $message }}
                    @enderror
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="password">パスワード</label>
                </div>
                <input class="form__input" type="password" name="password" id="password">
                <p class="form_error--message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="button-area">
                <button class="button__large" type="submit">ログイン</button>
            </div>
        </form>
        <div class="link__text">
            <a class="link__text--auth" href="{{ route('users.create') }}">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection