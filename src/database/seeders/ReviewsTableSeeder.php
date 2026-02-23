<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Order;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds, array $orderIds)
    {
        // 例として最初の注文を取得
        $order = Order::find($orderIds[0]);

        Review::create([
            'reviewer_id' => $order->buyer_id, // 評価する側（購入者）
            'reviewee_id' => $order->item->seller_id, // 評価される側（出品者）
            'order_id'     => $order->id, // 取引に紐づく
            'score'       => 5,
        ]);
    }
}