@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('title','商品の出品')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <div class="form__header">
            <h1 class="title">商品の出品</h1>
        </div>

        <form class="sell-form" action="{{ route('items.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">商品画像</h3>
                </div>
                <div class="item-image__upload__wrapper">
                    <input class="button__white--mini" type="file" name="item_image" id="item_image" value="{{ old('item_image')}}">
                </div>
                <p class="form_error--message">
                    @error('item_image')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__header--sub">
                <h2 class="subtitle">商品の詳細</h2>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">カテゴリー</h3>
                </div>
                <div class="category-area__wrapper">
                    <div class="category-button__wrapper">
                        @foreach($categories as $category)
                        <label class="category-button">
                            <input class="category-input" type="checkbox" name="category_id[]" value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                            <span class="category-display">{{ $category->category_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="form_error--message">
                    @error('category_id')
                    {{ $message }}
                    @enderror
                </p>
                </div>

            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">商品の状態</h3>
                </div>
                <div class="condition__wrapper">
                    <select class="condition-input" name="condition_id">
                        <option disabled selected value="">選択してください</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id')==$condition->id ? 'selected': '' }} >{{ $condition->level }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="form_error--message">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__header--sub">
                <h2 class="subtitle">商品名と説明</h2>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">商品名</h3>
                </div>
                <input class="form-input" type="text" name="item_name" placeholder="商品名を入力してください" value="{{ old('item_name') }}" >
                <p class="form_error--message">
                    @error('item_name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">ブランド名</h3>
                </div>
                <input class="form-input" type="text" name="brand" placeholder="ブランド名を入力してください（任意）" value="{{ old('brand') }}">
            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">商品の説明</h3>
                </div>
                <textarea class="form-input__description" name="description" placeholder="商品説明を入力してください（255文字以内）" cols="46" rows="7">{{ old('description') }}</textarea>
                <p class="form_error--message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <h3 class="sell-form__header">販売価格</h3>
                </div>
                <span class="sell-form__price--display">¥</span>
                <input class="form-input__price" type="text" name="price" placeholder="販売価格を入力してください" value="{{ old('price')}}" >
                <p class="form_error--message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="button-area">
                <button class="button__large" type="submit">出品する</button>
            </div>
        </form>
    </div>
</div>
@endsection