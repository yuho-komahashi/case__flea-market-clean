<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;

class OrdersTableSeeder extends Seeder
{
    public function run(array $userIds, array $itemIds)
    {
        DB::table('orders')->insert([
            [
                'buyer_id'=> $userIds[1],//B
                'item_id' => $itemIds[0],//Aの商品(腕時計)
                'payment_method' => 'card',
                'shipping_postcode' => '987-6543',
                'shipping_address' => '大阪府北区梅田1-2-3',
                'shipping_building' => '梅田タワー202',
                'status' => 'trading',//paid→tradingに変更
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'buyer_id'=> $userIds[0],//A
                'item_id' => $itemIds[8],//Bの商品（コーヒーミル）
                'payment_method' => 'card',
                'shipping_postcode' => '123-4567',
                'shipping_address' => '東京都渋谷区千駄ヶ谷1-2-3',
                'shipping_building' => '千駄ヶ谷マンション1101',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'buyer_id'=> $userIds[0],//A
                'item_id' => $itemIds[5],//Bの商品（マイク）
                'payment_method' => 'konbini',
                'shipping_postcode' => '134-6788',
                'shipping_address' => '東京都港区芝浦4-10-8',
                'shipping_building' => 'オフィス芝浦1201',
                'status' => 'trading',//paid→tradingに変更
                'created_at' => now(),
                'updated_at' => now(),
            ],
            /*
            [
                'buyer_id'=> $userIds[2],
                'item_id' => $itemIds[0],
                'payment_method' => 'konbini',
                'shipping_postcode' => '112-7890',
                'shipping_address' => '東京都荒川区東日暮里10-11',
                'shipping_building' => null,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'buyer_id'=> $userIds[3],
                'item_id' => $itemIds[7],
                'payment_method' => 'konbini',
                'shipping_postcode' => '813-8500',
                'shipping_address' => '福岡県福岡市東区松香2-3-1',
                'shipping_building' => '松香ハイム301',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            */
        ]);
    }
}
