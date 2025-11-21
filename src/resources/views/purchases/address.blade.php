@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
@endsection

@section('title','配送先住所変更')

@section('content')
<div class="content">
    <div class="input-form__wrapper">
        <div class="form__header">
            <h1 class="title">住所の変更</h1>
        </div>

        <form class="address-form" action="{{ route('purchase.address.update', ['item_id' => $purchaseItem->id]) }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="shipping_postcode">郵便番号</label>
                </div>
                <input class="form__input" type="text" name="shipping_postcode" id="shipping_postcode" placeholder="例：123-4567" value="{{ old('shipping_postcode')}}">
                <p class="form_error--message">
                    @error('shipping_postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="shipping_address">住所</label>
                </div>
                <input class="form__input" type="text" name="shipping_address" id="shipping_address" placeholder="例：東京都中央区中央1-1" value="{{ old('shipping_address')}}">
                <p class="form_error--message">
                    @error('shipping_address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form__group">
                <div class="form__title">
                    <label class="form__label" for="shipping_building">建物名</label>
                </div>
                <input class="form__input" type="text" name="shipping_building" id="shipping_building" placeholder="例：中央マンション101" value="{{ old('shipping_building')}}">
            </div>
            <div class="button-area">
                <button class="button__large" type="submit">変更する</button>
            </div>
        </form>
    </div>
</div>
@endsection