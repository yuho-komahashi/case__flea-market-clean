<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessagesTableSeeder extends Seeder
{
    public function run(array $userIds, array $itemIds)
    {
        // 購入者 → 出品者
        Message::create([
            'user_id' => $userIds[1],// ユーザーB
            'item_id' => $itemIds[0],// A の商品（腕時計）
            'body' => '購入しました。よろしくお願いします。',
            'is_read' => true,//読まれて返信が来ている想定
            'created_at' => now()->subMinutes(rand(1, 1000)),//取引中商品ページ並べ替えのために日付をランダムに

        ]);

        // 出品者 → 購入者
        Message::create([
            'user_id' => $userIds[0], // ユーザーA
            'item_id' => $itemIds[0],
            'body' => 'ありがとうございます。明日発送します。',
            'is_read' => false,//送った直後で B がまだ未読の想定
            'created_at' => now()->subMinutes(rand(1, 1000)),

        ]);

        // 購入者 → 出品者
        Message::create([
            'user_id' => $userIds[0],// ユーザーA
            'item_id' => $itemIds[5],// B の商品（マイク）
            'body' => 'いつ頃送ってもらえそうですか。',
            'is_read' => true,//読まれて返信が来ている想定
            'created_at' => now()->subMinutes(rand(1, 1000)),
        ]);

        // 出品者 → 購入者
        Message::create([
            'user_id' => $userIds[1], // ユーザーB
            'item_id' => $itemIds[5],
            'body' => '遅くなって申し訳ありません。今日発送したので、もうすぐ届くと思います。',
            'is_read' => false,//送った直後で A がまだ未読の想定
            'created_at' => now()->subMinutes(rand(1, 1000)),
        ]);
    }
}
