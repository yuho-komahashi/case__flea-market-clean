@extends('layouts.auth_common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
@endsection

@section('title','メール認証ご案内')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <p class="information-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール内の「Verify Email Address」ボタンをクリックして<br>メール認証を完了してください。<br>
            「認証はこちらから」ボタンを押すとメールボックスが開きます。
        </p>
        <div class="button-area__gray">
            <form action="{{ route('verification.notice') }}" method="GET">
                @csrf
                <button class="button_mail-auth" type="submit" onclick="window.open('http://localhost:8025')">認証はこちらから</button>
            </form>
        </div>
        <div class="link__text">
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button class="button_mail--resend">認証メールを再送する</button>
            </form>
            @if (session('status') == 'verification-link-sent')
            <p class="success-message">※認証メールを再送しました。</p>
            @endif
        </div>
    </div>
</div>
@endsection