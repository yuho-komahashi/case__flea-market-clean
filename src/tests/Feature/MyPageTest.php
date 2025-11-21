<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;

class MyPageTest extends TestCase
{
    public function test_mypage_displays_user_information()//ユーザー情報取得
    {
        $user = User::factory()->create(['name' => 'テスト三郎']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
        ]);

        $item = Item::factory()->create([
            'seller_id' => $user->id,
            'item_name' => '出品商品A',
            'item_image' => 'sample.jpg',
        ]);

        $order = Order::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'paid',
        ]);

        // 出品タブの表示確認
        $response = $this->actingAs($user)->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertSee('テスト三郎');
        $response->assertSee('出品商品A');
        $response->assertSee('sample.jpg');
        $response->assertSee('test.jpg');

        // 購入タブの表示確認
        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('出品商品A');
    }

    public function test_profile_edit_form_displays_initial_values()//ユーザー情報変更
    {
        $user = User::factory()->create(['name' => 'テスト三郎']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
            'postcode' => '123-4567',
            'address' => '東京都新宿区新宿10-11',
            'building' => 'テストマンション101',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テスト三郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区新宿10-11');
        $response->assertSee('テストマンション101');
        $response->assertSee('test.jpg');
    }
}
