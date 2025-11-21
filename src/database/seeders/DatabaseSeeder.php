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
        $this->call([
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);

        $userIds = (new UsersTableSeeder())->run();
        $itemIds = (new ItemsTableSeeder())->run($userIds);
        (new ProfilesTableSeeder())->run($userIds);
        (new LikesTableSeeder())->run($userIds,$itemIds);
        (new CommentsTableSeeder())->run($userIds,$itemIds);
        (new OrdersTableSeeder())->run($userIds, $itemIds);

    }
}
