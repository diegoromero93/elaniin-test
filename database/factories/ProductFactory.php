<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->randomNumber(),
            'name' => $this->faker->word,
            'qty' => $this->faker->randomNumber(2),
            'amount' => $this->faker->randomNumber(2),
            'description' => $this->faker->sentence(6),
            'image' => 'https://picsum.photos/200/300'
        ];
    }
}
