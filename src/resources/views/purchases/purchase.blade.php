@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('title','商品購入')

@section('content')
<div class="content">
    <div class="purchase__wrapper">
        <div class="purchase-item__wrapper">
            <form class="purchase-form" action="{{ route('purchase.store', ['item_id'=> $purchaseItem->id]) }}" method="post">
                @csrf
                <div class="purchase-item">
                    <img class="purchase-item__image" src="{{ asset('storage/images/item_image/'.$purchaseItem->item_image) }}" alt="">
                    <div class="purchase-item__item">
                        <h3 class="purchase-item__name">{{ $purchaseItem->item_name }}</h3>
                        <div class="purchase-item__price">
                            <span class="purchase-item__price--display">¥</span>
                            <p class="purchase-item__price--price">{{ number_format($purchaseItem->price) }}</p>
                        </div>
                    </div>
                </div>
                <div class="payment-method">
                    <h4 class="payment-method__header">支払い方法</h4>
                    <div class="payment-method__wrapper">
                        <div class="payment-method__select">
                            <select id="payment-method-select" class="payment-method__select--input" name="payment_method">
                                <option disabled selected value="">選択してください</option>
                                <option value="konbini">コンビニ払い</option>
                                <option value="card">カード払い</option>
                            </select>
                            <p class="form_error--message">
                                @error('payment_method')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                    </div>
                </div>
                <div class="shipping-address">
                    <div class="shipping-address__header-area">
                        <h4 class="shipping-address__header">配送先</h4>
                        <div class="link__text">
                            <a class="link__text--address" href="{{ route('purchase.address.edit', ['item_id' => $purchaseItem->id]) }}">変更する</a>
                        </div>
                    </div>
                    <div class="shipping-address__wrapper">
                        @if(session()->has('updateData'))
                        @php $updateData = session('updateData'); @endphp
                        <div class="shipping-address__input">
                            <span class="shipping-address__input--display">〒</span>
                            <input class="shipping-address__input--postcode" type="text" name="shipping_postcode" value="{{ $updateData['shipping_postcode'] }}" readonly>
                            <p class="form_error--message">
                                @error('shipping_postcode')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        <div class="shipping-address__input">
                            <input class="shipping-address__input--text" type="text" name="shipping_address" value="{{ $updateData['shipping_address'] }}" readonly>
                            <p class="form_error--message">
                                @error('shipping_address')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        <div class="shipping-address__input">
                            <input class="shipping-address__input--text" type="text" name="shipping_building" value="{{ $updateData['shipping_building'] }}" readonly>
                        </div>

                        @else
                        <div class="shipping-address__input">
                            <span class="shipping-address__input--display">〒</span>
                            <input class="shipping-address__input--postcode" type="text" name="shipping_postcode" value="{{ $shipping_address->postcode }}" readonly>
                            <p class="form_error--message">
                                @error('shipping_postcode')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        <div class="shipping-address__input">
                            <input class="shipping-address__input--text" type="text" name="shipping_address" value="{{ $shipping_address->address }}" readonly>
                            <p class="form_error--message">
                                @error('shipping_address')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        <div class="shipping-address__input">
                            <input class="shipping-address__input--text" type="text" name="shipping_building" value="{{ $shipping_address->building }}" readonly>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="payment-confirm__wrapper">
                    <table class="confirm-table">
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">商品代金</th>
                            <td class="confirm-table__price">
                                <span class="confirm-table__price--display">¥</span>{{ number_format($purchaseItem->price) }}
                            </td>
                        </tr>
                        <tr class="confirm-table__row">
                            <th class="confirm-table__header">支払い方法</th>
                            <td class="confirm-table__method" id="selected-payment-method">未選択</td>
                        </tr>
                    </table>
                <div class="button-area">
                    <button class="button__small" type="submit">購入する</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('payment-method-select');
        const methodDisplay = document.getElementById('selected-payment-method');

        select.addEventListener('change', function () {
            const selected = select.value;
            if (selected === 'konbini') {
                methodDisplay.textContent = 'コンビニ払い';
            } else if (selected === 'card') {
                methodDisplay.textContent = 'カード払い';
            } else {
                methodDisplay.textContent = '未選択';
            }
        });
    });
</script>
@endsection