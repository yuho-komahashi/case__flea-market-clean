<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => '山口一郎',
                'email' => 'user_a@example.com',
                'password' => Hash::make('passyama11'),
                'email_verified_at'=> now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '伊藤雅子',
                'email' => 'user_b@example.com',
                'password' => Hash::make('passito22'),
                'email_verified_at'=> now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '坂本太郎',
                'email' => 'user_c@example.com',
                'password' => Hash::make('sakapass33'),
                'email_verified_at'=> now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '木村陽子',
                'email' => 'user_d@example.com',
                'password' => Hash::make('yokopass44'),
                'email_verified_at'=> now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $userIds=[];

        foreach($users as $u){
            $userIds[]= DB::table('users')->insertGetId($u);
        }

        return $userIds;
    }
}