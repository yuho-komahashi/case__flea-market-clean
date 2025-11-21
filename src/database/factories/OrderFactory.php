<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'buyer_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => 'card',
            'shipping_postcode' => $this->faker->postcode(),
            'shipping_address' => $this->faker->address(),
            'shipping_building' => $this->faker->optional()->secondaryAddress(),
            'status' => 'paid',
        ];
    }
}
