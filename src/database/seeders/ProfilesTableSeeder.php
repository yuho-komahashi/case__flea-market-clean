<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use App\Models\User;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $userIds)
    {
        $profiles=[
            [
                'user_id'=> $userIds[0],
                'profile_image' => 'img_user_A.png',
                'postcode' => '123-4567',
                'address' => '東京都渋谷区千駄ヶ谷1-2-3',
                'building' => '千駄ヶ谷マンション1101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'=> $userIds[1],
                'profile_image' => 'img_user_B.png',
                'postcode' => '987-6543',
                'address' => '大阪府北区梅田1-2-3',
                'building' => '梅田タワー202',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'=> $userIds[2],
                'profile_image' => 'img_user_C.png',
                'postcode' => '112-7890',
                'address' => '東京都荒川区東日暮里10-11',
                'building' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'=> $userIds[3],
                'profile_image' => 'img_user_D.png',
                'postcode' => '813-8500',
                'address' => '福岡県福岡市東区松香2-3-1',
                'building' => '松香ハイム301',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach($profiles as $profile){
            DB::table('profiles')->insert($profile);
        }
    }
}
