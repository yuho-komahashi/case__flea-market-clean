<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_exhibition_form_saves_data()//商品が出品できるか
    {
        Storage::fake('public');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::factory()->create(['category_name' => '家電']);
        $condition = Condition::factory()->create(['level' => '新品']);

        $image = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $image->store('images/item_image', 'public');

        $formData = [
            'category_id' => [$category->id],
            'condition_id' => $condition->id,
            'item_name' => 'テスト冷蔵庫',
            'brand' => 'Panasonic',
            'description' => '大容量の冷蔵庫です',
            'price' => 50000,
            'item_image' => $image,
        ];

        $response = $this->actingAs($user)->post(route('items.store'), $formData);

        $response->assertRedirect(route('mypage.show'));

        $this->assertDatabaseHas('items', [
            'item_name' => 'テスト冷蔵庫',
            'brand' => 'Panasonic',
            'price' => 50000,
            'condition_id' => $condition->id,
            'seller_id' => $user->id,
        ]);

        $item = Item::where('item_name', 'テスト冷蔵庫')->first();
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->assertExists('images/item_image/' . $image->hashName());
    }
}