<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Item::class;
    
    public function definition()
    {
        return [
            'seller_id' => User::factory(),
            'item_image' => 'storage/images/item_image/sample.jpg',
            'condition_id' => Condition::factory(),
            'item_name'=> $this->faker->words(2,true),
            'description' => $this->faker->text(100),
            'price'=> $this->faker->numberBetween(1000,10000),
            'item_status' => 'available',
        ];
    }
}
