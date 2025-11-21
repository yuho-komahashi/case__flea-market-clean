<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_match_search_by_item_name()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'item_name' => 'スニーカー',
            'seller_id'=> $otherUser->id,
        ]);

        Item::factory()->create([
            'item_name' => 'スカート',
            'seller_id'=> $otherUser->id,
        ]);

        $response = $this->actingAs($user)->get('/item/search?keyword=スニ&tab=recommend');

        $response->assertStatus(200);
        $response->assertSee('スニーカー');
        $response->assertDontSee('スカート');
    }

    public function test_keep_search_status_in_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['item_name'=>'スニーカー']);
        $user->likedItems()->attach($item->id);

        $response = $this->actingAs($user)->get('/item/search?tab=mylist&keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSee('スニーカー');
        $this->assertStringContainsString('value="スニーカー"', $response->getContent());
    }
}