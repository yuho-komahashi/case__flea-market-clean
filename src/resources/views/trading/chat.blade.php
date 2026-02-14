@extends('layouts.chat_header')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('title', '取引画面')

@section('content')
    <div class="content">
        <div class="trading__wrapper">
            <aside class='side-bar'>
                <p class="side-bar__heading">その他の取引</p>
                @if ($otherOrders->isNotEmpty())
                    <ul class='trading-list'>
                        @foreach ($otherOrders as $o)
                            <li class="trading-list__item">
                                <button type="button" class="button__trading-item"
                                    onclick="saveDraftAndGo('{{ route('trading.show', $o->id) }}', {{ $order->id }})">
                                    {{ $o->item->item_name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </aside>

            <div class="chat__wrapper">
                <div class="trading-header">
                    <div class="trading-title">
                        <div class="trading-title__wrapper">
                            <img class="chat-user__image--large"
                                src="{{ asset('storage/images/user_image/' . $partner->profile->profile_image) }}"
                                alt="取引ユーザー画像">
                            <h1 class="trading-title__heading">「{{ $partner->name }}」さんとの取引画面</h1>
                        </div>
                        @if (auth()->id() === $order->buyer_id && $order->status !== 'completed')
                            <form action="{{ route('trading.complete', $order->id) }}" method="POST">
                                @csrf
                                <button class="button__trading-complete" type="submit">取引を完了する</button>
                            </form>
                        @endif
                    </div>
                    <div class="trading-item">
                        <div class="trading-item__image">
                            <img class="trading-item__image--image"
                                src="{{ asset('storage/images/item_image/' . $item->item_image) }}"
                                alt="{{ $item->item_name }}">
                        </div>
                        <div class="trading-item__detail">
                            <h2 class="trading-item__name">{{ $item->item_name }}</h2>
                            <div class="trading-item__price">
                                <span class="trading-item__price--display">¥</span>
                                <p class="trading-item__price--price">{{ number_format($item->price) }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="message-area">
                    <div class="sent-message__area">
                        <div class="sent-message">
                            @foreach ($messages as $message)
                                {{-- 自分のメッセージ（右側） --}}
                                @if ($message->user_id === $me->id)
                                    <div class="chat-group__me" data-message-id="{{ $message->id }}">
                                        <div class="chat-label">
                                            <label class="chat-user__name" for="message">{{ $me->name }}</label>
                                            <img class="chat-user__image--small"
                                                src="{{ asset('storage/images/user_image/' . $me->profile->profile_image) }}"
                                                alt="{{ $me->name }}">
                                        </div>
                                        {{-- 通常表示 --}}
                                        <div class="sent-message__message message-view">{{ $message->body }}</div>

                                        {{-- 編集モード（最初は非表示） --}}
                                        <form class="message-edit-form"
                                            action="{{ route('message.update', $message->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            @if ($errors->any())
                                                <div class="form_error--message">
                                                    {{ $errors->first('body') }}
                                                </div>
                                            @endif

                                            <textarea name="body" class="edit-textarea">{{ $message->body }}</textarea>

                                            <div class="edit-buttons">
                                                <button type="submit">保存</button>
                                                <button type="button" class="edit-cancel">キャンセル</button>
                                            </div>
                                        </form>

                                        <div class="sent-message__under">
                                            @if ($message->image)
                                                <img class="chat-image"
                                                    src="{{ asset('storage/images/chat_image/' . $message->image) }}"
                                                    alt="添付画像">
                                            @endif

                                            <div class="button__edit">
                                                <button class="message-edit" type="button">編集</button>

                                                <form action="{{ route('message.destroy', $message->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="message-delete" type="submit">削除</button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- 相手のメッセージ（左側） --}}
                                @else
                                    <div class="chat-group__other">
                                        <div class="chat-label">
                                            <img class="chat-user__image--small"
                                                src="{{ asset('storage/images/user_image/' . $partner->profile->profile_image) }}"
                                                alt="{{ $partner->name }}">
                                            <label class="chat-user__name" for="message">{{ $partner->name }}</label>
                                        </div>
                                        <div class="sent-message__message">{{ $message->body }}</div>
                                        @if ($message->image)
                                            <img class="chat-image"
                                                src="{{ asset('storage/images/chat_image/' . $message->image) }}"
                                                alt="添付画像">
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="input-message__area">
                        <p class="form_error--message">
                            @error('message')
                                {{ $message }}
                            @enderror
                        </p>
                        <p class="form_error--message">
                            @error('image')
                                {{ $message }}
                            @enderror
                        </p>

                        <form class="input-form" action="{{ route('message.store', $order->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <textarea class="input-message" name="message" placeholder="取引メッセージを記入してください" maxlength="400">{{ old('message', $messageDraft) }}</textarea>

                            <div class="preview-wrapper">
                                <img id="preview" class="preview-image">
                            </div>
                            {{-- 隠しファイル入力 --}}
                            <input class="file-input-hidden" type="file" name="image" id="imageInput" accept="image/*"
                                style="display:none;">

                            {{-- 画像追加ボタン --}}
                            <button class="button__add-image" type="button"
                                onclick="document.getElementById('imageInput').click()">
                                画像を追加
                            </button>

                            {{-- メッセージ送信ボタン --}}
                            <button class="button__sent-message">
                                <img class="message" src="{{ asset('images/sending-button.png') }}" alt="送信ボタン">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 評価用モーダル --}}
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>

            <h2 class="modal-heading">取引が完了しました。</h2>
            <p class="modal-text">今回の取引相手はどうでしたか？</p>

            <div class="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <img class="star" data-value="{{ $i }}" src="{{ asset('images/empty_star.png') }}"
                        alt="☆">
                @endfor
            </div>

            <form class="rating-form" action="{{ route('trading.rating', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="score" id="score" value="5">

                <button class="button__rating-send" type=submit>送信する</button>
            </form>
        </div>
    </div>

    {{-- 購入者（完了ボタンを押した直後だけ開く） --}}
    @if (auth()->id() === $order->buyer_id && session('completed'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                document.getElementById('ratingModal').style.display = 'flex';
            });
        </script>

        @php
            session()->forget('completed');
        @endphp
    @endif

    {{-- 出品者（購入者が完了した商品を開いたときだけ開く） --}}
    @if (auth()->id() === $order->item->seller_id && $order->status === 'completed')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                document.getElementById('ratingModal').style.display = 'flex';
            });
        </script>
    @endif


    <script>
        //画像プレビュー
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // hidden解除
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        //メッセージ入力欄の下書き保存 入力が変わる度にhiddenを更新
        const textarea = document.querySelector('textarea[name="message"]');

        function syncDraft() {
            document.querySelectorAll('.draftMessage').forEach(input => {
                input.value = textarea.value;
            });
        }

        // 入力のたびに hidden を更新
        textarea.addEventListener('input', syncDraft);

        // ページ読み込み時にも同期
        syncDraft();
    </script>
    <script>
        //サイドバー遷移用
        function saveDraftAndGo(url, orderId) {
            fetch("{{ route('message.draft') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    key: "message_draft_" + orderId,
                    message: document.querySelector('textarea[name="message"]').value
                })
            }).then(() => {
                window.location.href = url;
            });
        }
    </script>
    <script>
        //送付済メッセージ編集モード切替用
        document.querySelectorAll('.message-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const parent = this.closest('[data-message-id]');
                parent.querySelector('.message-view').style.display = 'none';
                parent.querySelector('.message-edit-form').style.display = 'block';
            });
        });

        document.querySelectorAll('.edit-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                const parent = this.closest('[data-message-id]');
                parent.querySelector('.message-view').style.display = 'block';
                parent.querySelector('.message-edit-form').style.display = 'none';
            });
        });
    </script>
    <script>
        //評価モーダル用
        const modal = document.getElementById('ratingModal');
        const closeBtn = document.querySelector('.modal-close');

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    </script>
    <script>
        //★をクリックしたらスコアを更新する
        const stars = document.querySelectorAll('.star');
        const scoreInput = document.getElementById('score');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.dataset.value;

                // hidden に値をセット
                scoreInput.value = value;

                // 星の見た目を更新
                stars.forEach(s => {
                    if (s.dataset.value <= value) {
                        s.src = "{{ asset('images/filled_star.png') }}";
                    } else {
                        s.src = "{{ asset('images/empty_star.png') }}";
                    }
                });
            });
        });
    </script>


@endsection
