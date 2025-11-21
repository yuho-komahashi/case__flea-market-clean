<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">

    @yield('css')

    <title>@yield('title')</title>
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <a href="{{ route('items.index') }}">
                    <img class="logo" src="{{ asset('images/logo.svg') }}" alt="コーチテックフリーマーケット">
                </a>
            </div>
            <div class="header__utilities">
                <div class="header__search">
                    <form class="search-form" action="{{ route('items.search') }}" method="get">
                        @csrf
                        <input class="search-form__input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                        <input type="hidden" name="tab" value="{{ isset($tab) ? $tab : 'recommend' }}">
                    </form>
                </div>
                <div class="header__navigation">
                    <div class="header__nav--button">
                        @auth
                        <form action="/logout" method="POST" >
                            @csrf
                            <button class="header__button--text" type="submit">ログアウト</button>
                        </form>
                        @endauth

                        @guest
                        <button class="header__button--text" type="button" onclick="location.href='{{ url('/login') }}'">ログイン</button>
                        @endguest
                    </div>
                    <div class="header__nav--button">
                        <button class="header__button--text" type="button" onclick="location.href='{{ route('mypage.show') }}'">マイページ</button>
                    </div>
                    <div class="header__nav--button">
                        <button class="header__button--exhibit" type="button" onclick="location.href='{{ route('items.create') }}'">出品</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
        @yield('scripts')
    </main>
</body>
</html>