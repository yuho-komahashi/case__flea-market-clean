<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessagesTableSeeder extends Seeder
{
    public function run(array $userIds, array $orderIds)
    {
        // 1件目のメッセージの基準時間（ランダム）にして、それ以降はその時間に数分足すようにする
        $baseTime = now()->subMinutes(rand(200, 1000));
        //200〜1000分前のどこかに“基準時間”をランダムで作る

        //腕時計の取引（orderIds[0]）
        // 購入者 → 出品者
        Message::create([
            'user_id' => $userIds[1],// ユーザーB
            'order_id' => $orderIds[0],// A の商品（腕時計）との取引
            'message' => '先ほど購入しました。よろしくお願いします。',
            'is_read' => true,//読まれて返信が来ている想定
            'created_at' => $baseTime,
        ]);

        // 出品者 → 購入者（10分後）
        Message::create([
            'user_id' => $userIds[0], // ユーザーA
            'order_id' => $orderIds[0],
            'message' => 'ありがとうございます。明日発送します。',
            'is_read' => false,//送った直後で B がまだ未読の想定
            'created_at' => $baseTime->copy()->addMinutes(10),

        ]);

        // 別の取引の基準時間
        $baseTime2 = now()->subMinutes(rand(200, 1000));

        // マイクの取引（orderIds[2]）
        // 購入者 → 出品者
        Message::create([
            'user_id' => $userIds[0],// ユーザーA
            'order_id' => $orderIds[2],// B の商品（マイク）との取引
            'message' => 'いつ頃送ってもらえそうですか。',
            'is_read' => true,//読まれて返信が来ている想定
            'created_at' => $baseTime2,
        ]);

        // 出品者 → 購入者（15分後）
        Message::create([
            'user_id' => $userIds[1], // ユーザーB
            'order_id' => $orderIds[2],
            'message' => '遅くなって申し訳ありません。今日発送したので、もうすぐ届くと思います。',
            'is_read' => false,//送った直後で A がまだ未読の想定
            'created_at' => $baseTime2->copy()->addMinutes(15),
        ]);
    }
}
