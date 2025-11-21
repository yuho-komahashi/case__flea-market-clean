<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds, array $itemIds)
    {
        DB::table('comments')->insert([
            [
                'user_id' => $userIds[0],
                'item_id' => $itemIds[1],
                'comment_content' => 'HDDの容量を教えてください',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[1],
                'item_id' => $itemIds[9],
                'comment_content' => '使いよさそうなセットですね！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[2],
                'item_id' => $itemIds[2],
                'comment_content' => 'おいしそうな玉ねぎだ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[3],
                'item_id' => $itemIds[0],
                'comment_content' => 'とてもすてきですね！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[3],
                'item_id' => $itemIds[4],
                'comment_content' => 'メーカーと製造年を教えてください',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
