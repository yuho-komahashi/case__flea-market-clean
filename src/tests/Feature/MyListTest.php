<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_liked_items_are_displayed_in_mylist()//いいね商品だけ表示
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create(['item_name' => 'いいね商品']);
        $unlikedItem = Item::factory()->create(['item_name' => '未いいね商品']);
        
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品');
        $response->assertDontSee('未いいね商品');
    }

    public function test_sold_items_display_sold_label_in_mylist()//購入済商品はsold表示あり
    {
        $user = User::factory()->create();
        $soldItem = Item::factory()->create([
            'item_name' => '売れた商品',
            'item_status' => 'sold',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('売れた商品');
        $response->assertSee('Sold');
    }

    public function test_unverified_user_sees_empty_mylist()//未認証時は表示なし
    {
        $user = User::factory()->create([
        'email_verified_at' => null,
        ]);

        $item = Item::factory()->create(['item_name' => 'テスト商品']);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('マイリスト');
        $response->assertDontSee('テスト商品');
    }
}