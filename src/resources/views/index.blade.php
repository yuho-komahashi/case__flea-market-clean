@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/list_common.css') }}">
@endsection

@section('title','商品一覧')

@section('content')
<div class="content">
    <div class="item-list__wrapper">
        <div class="list-tab">
            <a href="{{ route('items.index', ['tab' => 'recommend']) }}" class="tab-label {{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
            @auth
            <a href="{{ route('items.index', ['tab' => 'mylist']) }}" class="tab-label {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
            @else
            <a href="{{ url('/login') }}" class="tab-label">マイリスト</a>
            @endauth
        </div>

        <div class="item-list">
            <div class="item-list__content">
                @if($keyword)
                    @if($items->isEmpty())
                        <div class="search-result__wrapper">
                            <p class="search-result__text">
                                「{{ $keyword }}」に一致する商品は見つかりませんでした。
                            </p>
                        </div>
                    @else
                        @foreach($items as $item)
                            @include('components.item-card',['item'=> $item])
                        @endforeach
                    @endif
                @else
                    @if ($tab === 'recommend')
                        @foreach($items as $item)
                            @include('components.item-card',['item'=> $item])
                        @endforeach
                    @elseif ($tab === 'mylist')
                        @foreach($items as $item)
                            @include('components.item-card',['item'=> $item])
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection