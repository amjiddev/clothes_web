<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 500);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'short_description' => $this->faker->sentence(10),
            'full_description' => $this->faker->paragraph(),
            'price' => $price,
            'regular_price' => $price,
            'sale_price' => $this->faker->optional()->randomFloat(2, 5, $price * 0.8),
            'discount_price' => $this->faker->optional()->randomFloat(2, 5, $price * 0.7),
            'sku' => $this->faker->unique()->numerify('SKU-####-####'),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'brand' => $this->faker->optional()->word(),
            'color' => $this->faker->safeColorName(),
            'material' => $this->faker->optional()->word(),
            'size' => $this->faker->optional()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'available_sizes' => json_encode(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'available_colors' => json_encode(['Red', 'Blue', 'Green', 'Black', 'White']),
            'fabric_type' => $this->faker->optional()->randomElement(['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen']),
            'is_active' => true,
        ];
    }
}
