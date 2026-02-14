<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds, array $itemIds)
    {
        Review::create([
            'reviewer_id' => $userIds[1],// B が
            'reviewee_id' => $userIds[0],// A を評価
            'item_id'     => $itemIds[1],// A の商品(sold)
            'score'       => 5,
        ]);
    }
}
