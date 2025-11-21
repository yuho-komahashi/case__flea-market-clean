<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;
    protected $category1;
    protected $category2;
    protected $condition;

    /*セットアップ*/
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasProfile()->create();

        $this->category1 = Category::factory()->create(['category_name'=>'メンズ']);
        $this->category2 = Category::factory()->create(['category_name'=>'ファッション']);
        $this->condition = Condition::factory()->create(['level'=>'新品']);

        $this->item = Item::factory()->create([
            'item_name' => 'スニーカー',
            'brand' => 'ナイキ',
            'price' => '10000',
            'description' => 'とても履きやすいスニーカーです。',
            'condition_id' => $this->condition->id,
            'item_image'=> 'sample.jpg',
        ]);

        $this->item->categories()->attach([$this->category1->id, $this->category2->id]);
    }

    /*商品詳細情報取得*/
    public function test_detail_page_displays_all_required_information()//詳細ページに必要な情報が表示される
    {
        $this->item->likedByUsers()->attach($this->user->id);
        Comment::factory()->create([
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'comment_content' => 'この商品、気になります！',
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response->assertStatus(200);
        $response->assertSee('スニーカー');
        $response->assertSee('ナイキ');
        $response->assertSee(number_format($this->item->price));
        $response->assertSee('とても履きやすいスニーカーです。');
        $response->assertSee('新品');
        $response->assertSee('メンズ');
        $response->assertSee('ファッション');
        $response->assertSee((string) $this->item->likedByUsers->count());
        $response->assertSee((string) $this->item->comments->count());
        $response->assertSee($this->user->name);
        $response->assertSee('この商品、気になります！');
    }

    public function test_detail_page_displays_multiple_categories()//複数選択されたカテゴリが表示される
    {
        $this->item->categories()->attach([$this->category1->id, $this->category2->id]);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);

        $response->assertStatus(200);
        $response->assertSee('メンズ');
        $response->assertSee('ファッション');
    }

    /*いいね機能*/
    public function test_user_can_like_an_item()//いいねした商品として登録できる
    {
        $response = $this->actingAs($this->user)->post(route('items.like',$this->item->id));
        $response->assertRedirect();

        $this->assertTrue($this->item->likedByUsers()->where('user_id', $this->user->id)->exists());
    }

    public function test_liked_icon_changes_after_liking()//いいね済アイコンが切り替わる
    {
        $this->item->likedByUsers()->attach($this->user->id);

        $response = $this->actingAs($this->user)->get('/item/' . $this->item->id);
        $response->assertSee('icon_liked_active.png');
    }

    public function test_user_can_unlike_an_item()//いいねが解除できる
    {
        $this->item->likedByUsers()->attach($this->user->id);

        $response = $this->actingAs($this->user)->post(route('items.like', $this->item->id));
        $response->assertRedirect();

        $this->assertFalse($this->item->likedByUsers()->where('user_id', $this->user->id)->exists());
    }

    /*コメント送信機能*/
    public function test_logged_in_user_can_post_comment()//ログインユーザーがコメントを送信できる
    {
        $response = $this->actingAs($this->user)->post(route('comments.store', ['item' => $this->item->id]),[
            'comment_content' => 'すてきな時計ですね！',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments',[
            'item_id' => $this->item->id,
            'user_id' => $this->user->id,
            'comment_content' => 'すてきな時計ですね！',
        ]);
    }

    public function test_guest_user_cannot_post_comment()//未ログインユーザーはコメント送信できない
    {
        $response = $this->post(route('comments.store', ['item' => $this->item->id]),[
            'comment_content' => 'ログインしていないけどコメントしたい'
        ]);

        $response->assertRedirect('/login');
    }

    public function test_comment_validation_error_when_empty()//コメント空欄の場合バリデーションエラー表示
    {
        $response = $this->actingAs($this->user)->post(route('comments.store', ['item' => $this->item->id]),[
            'comment_content' => '',
        ]);

        $response->assertSessionHasErrors('comment_content');
    }

    public function test_comment_validation_error_when_exceeds_max_length()//コメント255文字以上の場合バリデーションエラー表示
    {
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($this->user)->post(route('comments.store', ['item' => $this->item->id]), [
            'comment_content' => $longComment,
        ]);

        $response->assertSessionHasErrors('comment_content');
    }
}