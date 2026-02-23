<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        @yield('css')

        <title>@yield('title')</title>

    </head>

    <body>
        <header class="header">
            <div class="header__inner--logo-only">
                <div class="header__logo">
                    {{-- ロゴボタン（クリックで下書き保存 → マイページへ遷移） --}}
                    <button class="button__header-logo" id="logoButton">
                        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="コーチテックフリーマーケット">
                    </button>
                </div>
            </div>
        </header>

        <main class="content @yield('content-class')">
            @yield('content')
        </main>

        @yield('scripts')

    </body>

</html>