<div class="item-list__group">
    <div class="item-list__group--image">
        <a href="{{ route('items.show', $item->id)}}">
            <img class="item-list__image" src="{{ asset('storage/images/item_image/'.$item->item_image) }}" alt="{{ $item->item_name }}">
        </a>
    </div>
    <div class="item-list__group--label">
        <p class="item-list__label">{{ $item->item_name }}</p>
    </div>
    @auth
        @if($item->item_status === 'sold')
        <span class="sold-label">Sold</span>
        @endif
    @endauth
</div>