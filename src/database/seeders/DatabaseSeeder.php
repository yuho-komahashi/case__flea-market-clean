<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\ProfilesTableSeeder;
use Database\Seeders\LikesTableSeeder;
use Database\Seeders\CommentsTableSeeder;
use Database\Seeders\OrdersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Users
        $userIds = (new UsersTableSeeder())->run();

        // 2. Profiles（ユーザーの基本情報）
        (new ProfilesTableSeeder())->run($userIds);

        // 3. Categories & Conditions（Items より先）
        $this->call([
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);

        // 4. Items（condition_id を使うので後）
        $itemIds = (new ItemsTableSeeder())->run($userIds);

        // 5. Orders（1回だけ）（item_id を使うので後）
        $orderIds = (new OrdersTableSeeder())->run($userIds, $itemIds);

        // 6. Likes & Comments（item_id を使う）
        (new LikesTableSeeder())->run($userIds,$itemIds);
        (new CommentsTableSeeder())->run($userIds,$itemIds);

        // 7. Messages（order_id を使う）
        (new MessagesTableSeeder())->run($userIds, $orderIds);//itemIds→orderIesに修正

        // 8. Reviews（order_id を使う）
        (new ReviewsTableSeeder())->run($userIds, $orderIds);//itemIds→orderIesに修正
    }
}
