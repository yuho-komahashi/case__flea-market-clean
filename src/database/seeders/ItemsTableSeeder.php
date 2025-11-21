<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds)
    {
        $itemIds = [];

        $itemData=[   //$itemIds[0]
            'seller_id'=> $userIds[0],
            'item_image' => 'MensClock.jpg',
            'condition_id' => 1,
            'item_name' => '腕時計',
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => '15000',
            'item_status' => 'sold',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([1,5]);//ファッション・メンズ
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[1]
            'seller_id'=> $userIds[1],
            'item_image' => 'HardDisk.jpg',
            'condition_id' => 2,
            'item_name' => 'HDD',
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => '5000',
            'item_status' => 'sold',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([15]);//パソコン
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[2]
            'seller_id'=> $userIds[3],
            'item_image' => 'Onions.jpg',
            'condition_id' => 3,
            'item_name' => '玉ねぎ3束',
            'brand' => null,
            'description' => '新鮮な玉ねぎ3束のセット',
            'price' => '300',
            'item_status' => 'available',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([16]);//食品
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[3]
            'seller_id'=> $userIds[2],
            'item_image' => 'LeatherShoes.jpg',
            'condition_id' => 4,
            'item_name' => '革靴',
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'price' => '4000',
            'item_status' => 'available',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([1,5]);//ファッション・メンズ
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[4]
            'seller_id'=> $userIds[1],
            'item_image' => 'Laptop.jpg',
            'condition_id' => 1,
            'item_name' => 'ノートPC',
            'brand' => null,
            'description' => '高性能なノートパソコン',
            'price' => '45000',
            'item_status' => 'available',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([15]);//パソコン
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[5]
            'seller_id'=> $userIds[0],
            'item_image' => 'MusicMic.jpg',
            'condition_id' => 2,
            'item_name' => 'マイク',
            'brand' => null,
            'description' => '高音質のレコーディング用マイク',
            'price' => '8000',
            'item_status' => 'available',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([2]);//家電
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[6]
            'seller_id'=> $userIds[3],
            'item_image' => 'ShoulderBag.jpg',
            'condition_id' => 3,
            'item_name' => 'ショルダーバッグ',
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'price' => '3500',
            'item_status' => 'sold',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([1,4]);//ファッション・レディース
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[7]
            'seller_id'=> $userIds[1],
            'item_image' => 'Tumbler.jpg',
            'condition_id' => 4,
            'item_name' => 'タンブラー',
            'brand' => null,
            'description' => '使いやすいタンブラー',
            'price' => '500',
            'item_status' => 'sold',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([10]);//キッチン
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[8]
            'seller_id'=> $userIds[2],
            'item_image' => 'CoffeeGrinder.jpg',
            'condition_id' => 1,
            'item_name' => 'コーヒーミル',
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'price' => '4000',
            'item_status' => 'sold',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([10]);//キッチン
        $itemIds[] = $item->id;

        $itemData=[    //$itemIds[9]
            'seller_id'=> $userIds[3],
            'item_image' => 'MakeUpSet.jpg',
            'condition_id' => 2,
            'item_name' => 'メイクセット',
            'brand' =>  null,
            'description' => '便利なメイクアップセット',
            'price' => '2500',
            'item_status' => 'available',
        ];
        $item = Item::create($itemData);
        $item->categories()->attach([4,6]);//レディース・コスメ
        $itemIds[] = $item->id;

        return $itemIds;
    }
}
