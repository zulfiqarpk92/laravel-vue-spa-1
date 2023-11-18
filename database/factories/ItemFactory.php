<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Item::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    $cost = $this->faker->randomNumber(3);
    return [
      'item_name'   => $this->faker->text('15'),
      // 'supplier_id' => Collect(['Brush', 'Powder', 'Food'])->random(),
      // 'category_id' => Collect(['Brush', 'Powder', 'Food'])->random(),
      'item_type'   => $this->faker->randomElement([0, 1]),
      'cost_price'  => $cost,
      'sale_price'  => $cost + round($cost * 0.30),
      'bulk_price'  => $cost + round($cost * 0.15)
    ];
  }
}
