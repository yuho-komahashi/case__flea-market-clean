@extends('layouts.auth_common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
@endsection

@section('title','会員登録')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <div class="form-header">
            <h1 class="title">会員登録</h1>
        </div>

        <form class="register-form" action="{{ route('users.store') }}" method="POST" novalidate>
            @csrf
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="name">ユーザー名</label>
                </div>
                <input class="form__input" type="text" name="name" id="name" placeholder="例：山田太郎" value="{{ old('name')}}">
                <p class="form_error--message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="email">メールアドレス</label>
                </div>
                <input class="form__input" type="email" name="email" id="email" placeholder="例：test@example.com" value="{{ old('email')}}">
                <p class="form_error--message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="password">パスワード（8文字以上）</label>
                </div>
                <input class="form__input" type="password" name="password" id="password" placeholder="例：coachtech1106">
                <p class="form_error--message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="password">確認用パスワード</label>
                </div>
                <input class="form__input" type="password" name="password_confirmation" id="password_confirmation">
                <p class="form_error--message">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="button-area">
                <button class="button__large" type="submit">登録する</button>
            </div>
        </form>
        <div class="link__text">
            <a class="link__text--auth" href="{{ url('/login') }}">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection