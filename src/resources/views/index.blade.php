@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/list_common.css') }}">
@endsection

@section('title','商品一覧')

@section('content')
<div class="content content__list">
    <div class="item-list__wrapper">
        {{-- タブ --}}
        <div class="list-tab">
            <a href="{{ route('items.index', ['tab' => 'recommend', 'keyword' => $keyword]) }}" class="tab-label {{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>{{-- タブ切り替え時に keyword を引き継ぐ --}}
            <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="tab-label {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>{{-- タブ切り替え時に keyword を引き継ぐ --}}
        </div>

        {{-- 商品一覧 --}}
        <div class="item-list">
            <div class="item-list__content">
                @if($items->isEmpty()){{-- $itemsの有無を判定（検索か否かはindexが判断） --}}
                    @if($keyword){{-- keywordの有無を判断 --}}
                        <div class="search-result__wrapper">
                            <p class="search-result__text">
                                「{{ $keyword }}」に一致する商品は見つかりませんでした。
                            </p>
                        </div>
                    @else
                        <p class="search-result__text">
                            商品がありません。
                        </p>
                    @endif
                @else
                    @foreach($items as $item)
                        @include('components.item-card',['item'=> $item])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection