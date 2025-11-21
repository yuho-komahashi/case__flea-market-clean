<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header.css')}}">
    @yield('css')

    <title>@yield('title')</title>
    
</head>

<body>
    <header class="header">
        <div class="header__inner--auth">
            <div class="header__logo">
                <a href="{{ route('items.index') }}">
                    <img class="logo" src="{{ asset('images/logo.svg') }}" alt="コーチテックフリーマーケット">
                </a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>