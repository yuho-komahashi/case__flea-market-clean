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

                    <script>
                        document.getElementById('logoButton').addEventListener('click', function(e) {
                            e.preventDefault(); // 重要（ブラウザのデフォルト動作を止める）

                            //下書き保存（POST）
                            fetch("{{ route('message.draft') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    message: document.querySelector('textarea[name="message"]').value
                                })
                            }).then(() => {
                                //保存が終わったらマイページへ遷移
                                window.location.href = "{{ route('mypage.show') }}";
                            })
                        });
                    </script>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </body>

</html>
