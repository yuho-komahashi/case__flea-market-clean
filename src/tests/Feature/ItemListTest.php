<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_index_displays_items()//全商品取得
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach($items as $item){
            $response->assertSee($item->name);
        }
    }

    public function test_items_index_displays_sold_label_for_purchased_items()//購入済商品はsold表示あり
    {
        $user = User::factory()->create();
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'item_name' => '購入済み商品',
            'item_status' =>'sold'
        ]);

        Order::factory()->create([
            'buyer_id'=> $buyer->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($buyer);
        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertSee('Sold');
        $response->assertSee('購入済み商品');
    }

    public function test_items_index_does_not_display_own_items()//自分で出品した商品は非表示
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ownItem = Item::factory()->create([
            'seller_id' => $user->id,
            'item_name' => '自分の商品'
        ]);

        $otherItem = Item::factory()->create([
            'item_name' => '他人の商品'
        ]);

        $response = $this->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}