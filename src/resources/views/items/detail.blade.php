@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('title','商品詳細')

@section('content')
<div class="content">
    <div class="item-detail__wrapper">
        <div class="item-image__wrapper">
            <div class="detail__image">
                <img class="item-detail__image" src="{{ asset('storage/images/item_image/'.$item->item_image) }}" alt="{{ $item->item_name }}">
            </div>
        </div>
        <div class="item-info_wrapper">
            <div class="item-info__header">
                <h2 class="item-info__name">{{ $item->item_name }}</h2>
                <p class="item-info__brand">{{ $item->brand }}</p>
                <div class="item-info__price-area">
                    <span class="item-info__price--display">¥</span>
                    <p class="item-info__price">{{ number_format($item->price) }}</p>
                    <span class="item-info__price--display">（税込）</span>
                </div>
                <!--いいねエリア-->
                <div class="item-info__icon-area">
                    @php
                    $liked = auth()->check() && $item->likedByUsers->contains(auth()->user());
                    @endphp
                    <div class="item-info__icon">
                        @if(auth()->check())
                        <form class="liked-form" action="{{ route('items.like', $item->id) }}" method="post">
                            @csrf
                            <button class="item-info__button--liked" type="submit">
                                <img class="item-info__icon--icon"
                                src="{{ asset($liked ? 'images/icon_liked_active.png' : 'images/icon_liked.png') }}"
                                alt="いいね">
                            </button>
                        </form>
                        @else
                            <a href="{{ url('/login') }}">
                                <img class="item-info__icon--icon"
                                src="{{ asset('images/icon_liked.png') }}"
                                alt="ログインしてからいいね">
                            </a>
                        @endif
                        <span class="item-info__icon--count">{{ $item->likedByUsers->count() }}</span>
                    </div>
                <!--コメントエリア-->
                @php
                    $commentCount = $item->comments->count();
                @endphp
                    <div class="item-info__icon">
                        <img class="item-info__icon--icon"
                        src="{{ asset($commentCount > 0 ? 'images/icon_comment_active.png' : 'images/icon_comment.png') }}" alt="コメント">
                        <span class="item-info__icon--count" id="comment-count">{{ $commentCount }}</span>
                    </div>
                </div>
                <div class="button-area">
                    @auth
                    <button class="button__medium" type="button" onclick="location.href='{{ route('purchase.confirm', ['item_id' => $item->id]) }}'">購入手続きへ</button>
                    @else
                    <form class="to-login-form" action="{{ route('redirect.to.login', ['itemId' => $item->id]) }}" method="post">
                        @csrf
                        <button class="button__medium" type="submit">購入手続きへ</button>
                    </form>
                    @endauth
                </div>
            </div>
            <div class="item-info__detail">
                <h3 class="item-info__detail--header">商品説明</h3>
                <p class="item-info__detail--description">{{ $item->description }}</p>
                <h3 class="item-info__detail--header">商品情報</h3>
                <div class="item-info__information">
                    <p class="item-info__information--header">カテゴリー</p>
                    @foreach($item->categories as $category)
                    <div class="item-info__icon">
                        <p class="item-info__icon--gray">{{ $category->category_name }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="item-info__information">
                    <p class="item-info__information--header">商品の状態</p>
                    <div class="item-info__icon">
                        <p class="item-info__icon--white">{{ $item->condition->level }}</p>
                    </div>
                </div>
            </div>
            <div class="item-info__comment">
                    <h3 class="item-info__comment--header">コメント（{{ $commentCount  }}）</h3>
                    @foreach($item->comments as $comment)
                    <div class="item-info__comment--user">
                        <img class="item-info__comment--image" src="{{ asset('storage/images/user_image/'.$comment->user->profile->profile_image) }}" alt="ユーザー画像">
                        <p class="item-info__comment--name">{{ $comment->user->name}}</p>
                    </div>
                    <div class="item-info__comment--comment">
                        <p class="item-info__comment--content">{{ $comment->comment_content }}</p>
                    </div>
                    @endforeach
                    <form class="comment-form" action="{{ route('comments.store', ['item' => $item->id]) }}" method="post">
                        @csrf
                        <label class="comment-form__label" for="comment_content">商品へのコメント</label>
                        <div class="comment-form__input">
                            <textarea class="comment-form__input--textarea" name="comment_content" id="comment_content" placeholder="コメントを入力してください" cols="46" rows="10"></textarea>
                        </div>
                        <p class="form_error--message">
                            @error('comment_content')
                            {{ $message }}
                            @enderror
                        </p>
                        <div class="button-area">
                            <button class="button__medium" type="submit">コメントを送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
