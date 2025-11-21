@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/list_common.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('title','マイページ')

@section('content')
<div class="content">
    <div class="item-list__wrapper">
        <div class="purchase__message">
            @if(session('message'))
            <div class="purchase__message--success">
                {{ session('message') }}
            </div>
            @endif
        </div>

        <div class="user-profile">
            <div class="user-profile__image--wrapper">
                <div class="user-profile__image--mypage">
                    <img class="user-profile__image--image" src="{{ asset('storage/images/user_image/'.$user->profile->profile_image) }}" alt="ユーザー画像" >
                    <p class="user-profile__image--name">{{ $user->name }}</p>
                </div>
                <div class="button-area__profile">
                    <button class="button__white" type="button" onclick="location.href='{{ route('mypage.profile.edit') }}'">プロフィールを編集</button>
                </div>
            </div>
        </div>

        <div class="list-tab">
            <a href="{{ route('mypage.show', ['page' => 'sell']) }}" class="tab-label {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage.show', ['page' => 'buy']) }}" class="tab-label {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>

        <div class="item-list">
            <div class="item-list__content">
            @if ($page === 'sell')
                @foreach($items as $item)
                <div class="item-list__group">
                    <div class="item-list__group--image">
                        <a href="{{ route('items.show', $item->id)}}">
                            <img class="item-list__image" src="{{ asset('storage/images/item_image/'.$item->item_image) }}" alt="{{ $item->item_name }}">
                        </a>
                    </div>
                    <div class="item-list__group--label">
                        <p class="item-list__label">{{ $item->item_name }}</p>
                    </div>
                    @if($item->item_status === 'sold')
                    <span class="sold-label">Sold</span>
                    @endif
                </div>
                @endforeach
            @elseif ($page === 'buy')
                @foreach($orders as $order)
                <div class="item-list__group">
                    <div class="item-list__group--image">
                        <a href="{{ route('items.show', $order->item->id)}}">
                            <img class="item-list__image" src="{{ asset('storage/images/item_image/'.$order->item->item_image) }}" alt="{{ $order->item->item_name }}">
                        </a>
                    </div>
                    <div class="item-list__group--label">
                        <p class="item-list__label">{{ $order->item->item_name }}</p>
                    </div>
                    @if($order->item->item_status === 'sold')
                        <span class="sold-label">Sold</span>
                    @endif
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection