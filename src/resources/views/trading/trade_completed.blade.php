@extends('layouts.auth_common')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form_common.css') }}">
@endsection

@section('title', 'お取引完了のご案内')

@section('content')
    <div class="content">
        <p class="information-text">
            {{ $order->buyer->name }} さんとの取引が完了しました。
        </p>

        <p class="information-text">商品名：{{ $order->item->item_name }}</p>
        <p class="information-text">購入者：{{ $order->buyer->name }}</p>

        <p class="information-text">取引ページから詳細を確認できます。</p>
    </div>
@endsection
