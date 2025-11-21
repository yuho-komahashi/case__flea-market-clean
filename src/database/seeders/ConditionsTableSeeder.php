<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Condition;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $condition=[
            ['id'=> 1, 'level' => '良好', 'created_at' => $now, 'updated_at' => $now],
            ['id'=> 2, 'level' => '目立った傷や汚れなし', 'created_at' => $now, 'updated_at' => $now],
            ['id'=> 3, 'level' => 'やや傷や汚れあり', 'created_at' => $now, 'updated_at' => $now],
            ['id'=> 4, 'level' => '状態が悪い', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('conditions')->insert($condition);
    }
}
