<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Like;
use App\Models\User;
use App\Models\Item;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds, array $itemIds)
    {
        DB::table('likes')->insert([
            [
                'user_id' => $userIds[0], //山口さんが
                'item_id' => $itemIds[3], //革靴にいいね
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[1], //伊藤さんが
                'item_id' => $itemIds[0], //腕時計にいいね
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[1], //伊藤さんが
                'item_id' => $itemIds[5], //マイクにいいね
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[2], //坂本さんが
                'item_id' => $itemIds[1], //HDDにいいね
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[3], //木村さんが
                'item_id' => $itemIds[9], //メイクセットにいいね
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
