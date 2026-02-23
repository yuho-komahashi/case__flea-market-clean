<div class="item-list__group">
    <div class="item-list__group--image">

        @php
            $user = auth()->user();
            $orderId = null;

            if ($user) {
                //出品者の場合
                if ($item->seller_id === $user->id) {
                    $orderId = optional($item->order)->id;
                }

                //購入者の場合
                if (optional($item->order)->buyer_id === $user->id) {
                    $orderId = optional($item->order)->id;
                }
            }

            //遷移先の決定
            $link = $orderId ? route('trading.show', $orderId) : route('items.show', $item->id);
        @endphp

        <a href="{{ $link }}">
            <img class="item-list__image" src="{{ asset('storage/images/item_image/' . $item->item_image) }}"
                alt="{{ $item->item_name }}">
        </a>
    </div>
    <div class="item-list__group--label">
        <p class="item-list__label">{{ $item->item_name }}</p>
    </div>
    @auth
        @if ($item->item_status === 'sold')
            <span class="sold-label">Sold</span>
        @endif
    @endauth
</div>
