@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('title','プロフィール設定')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <div class="form-header">
            <h1 class="title">プロフィール設定</h1>
        </div>

        <form class="profile-form"
            action="{{ $mode === 'create' ? route('mypage.profile.store'):route('mypage.profile.update') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if($mode === 'edit')
                @method('PATCH')
            @endif
            <div class="user-profile__profile">
                <div class="user-profile_wrapper">
                    <div class="user-profile__image--profile">
                        @if($mode === 'edit' && $profile && $profile->profile_image)
                        <img class="user-profile__image--image" src="{{ asset('storage/images/user_image/'.$profile->profile_image) }}" alt="ユーザー画像" >
                        @else
                        <img class="user-profile__image--image" src="{{ asset('images/Ellipse1.png') }}" alt="ダミー画像">
                        @endif
                    </div>
                    <div class="image__upload">
                        <input class="button__white--mini" type="file" name="profile_image" required>
                    </div>
                </div>
                <p class="form_error--message">
                    @error('profile_image')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="name">ユーザー名</label>
                </div>
                <input class="form__input" type="text" name="name" id="name" placeholder="例：山田太郎" value="{{ old('name',optional($profile)->user->name ?? $user->name) }}">
                <p class="form_error--message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="postcode">郵便番号</label>
                </div>
                <input class="form__input" type="text" name="postcode" id="postcode" placeholder="例：123-4567" value="{{ old('postcode',optional($profile)->postcode) }}">
                <p class="form_error--message">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="address">住所</label>
                </div>
                <input class="form__input" type="text" name="address" id="address" placeholder="例：東京都中央区中央1-1" value="{{ old('address',optional($profile)->address) }}">
                <p class="form_error--message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="building">建物名</label>
                </div>
                <input class="form__input" type="text" name="building" id="building" placeholder="例：中央マンション101" value="{{ old('building',optional($profile)->building) }}">
            </div>
            <div class="button-area">
                <button class="button__large" type="submit">
                    {{ $mode === 'create' ? '登録する':'更新する' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection