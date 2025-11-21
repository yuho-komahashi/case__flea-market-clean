<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->hasProfile()->create();
        $this->item = Item::factory()->create();
    }

    public function test_user_can_purchase_item()//商品を購入できる
    {
        $response = $this->actingAs($this->user)->post(route('purchase.store', ['item_id' => $this->item->id]), [
            'payment_method' => 'card',
            'shipping_postcode' => '123-4567',
            'shipping_address' => '東京都渋谷区渋谷5-1',
            'shipping_building' => 'テストビル101',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $this->user->id,
            'item_id' => $this->item->id,
            'status' => 'paid'
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'item_status' => 'sold',
        ]);
    }

    public function test_purchased_item_is_marked_as_sold_in_index()//商品一覧にsold表示
    {
        $otherUser = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $otherUser->id,
            'item_status' => 'sold',
        ]);

        $response = $this->actingAs($this->user)->get('/?tab=recommend');

        $response->assertStatus(200);
        $response->assertSee($item->item_name);
        $response->assertSee('Sold');
    }

    public function test_purchased_item_appears_in_user_mypage()//購入した商品一覧に表示
    {
        $this->item->update(['item_status' => 'sold']);

        Order::factory()->create([
            'buyer_id' => $this->user->id,
            'item_id' => $this->item->id,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($this->item->item_name);
        $response->assertSee('Sold');
    }

    public function test_shipping_address_is_displayed_on_purchase_page()//配送先変更が購入画面に反映されているか
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区渋谷5-1',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'seller_id' => $user->id,
        ]);

        $updateData = [
            'shipping_postcode' => '323-4567',
            'shipping_address' => '埼玉県大宮市大宮3-33',
            'shipping_building' => 'テストマンション303',
        ];

        $response = $this->actingAs($user)
            ->withSession(['updateData' => $updateData])
            ->get(route('purchase.confirm', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('323-4567');
        $response->assertSee('埼玉県大宮市大宮3-33');
        $response->assertSee('テストマンション303');
    }

    public function test_order_saves_shipping_address()//購入した商品に住所が紐づいているか
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $formData = [
            'payment_method' => 'card',
            'shipping_postcode' => '323-4567',
            'shipping_address' => '埼玉県大宮市大宮3-33',
            'shipping_building' => 'テストマンション303',
        ];

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", $formData);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postcode' => '323-4567',
            'shipping_address' => '埼玉県大宮市大宮3-33',
            'shipping_building' => 'テストマンション303',
        ]);
    }

    //支払い方法の選択による小計表示の変更は、JavaScriptによってフロントエンドで動的に反映される仕様にしたため、支払い方法の選択肢の表示や初期状態の確認のみ行っています。
    public function test_purchase_page_displays_payment_options_and_subtotal()
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/item_image/test.jpg', 'dummy content');

        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区渋谷5-1',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'item_image' => 'test.jpg',
            'item_name' => 'テスト商品',
            'price' => 50000
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase.confirm', ['item_id' => $item->id]));
        $response->assertStatus(200);
        $response->assertSee('支払い方法');
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード払い');
        $response->assertSee('商品代金');
        $response->assertSee('¥');
        $response->assertSee(number_format($item->price));
        $response->assertSee('未選択');
    }
}