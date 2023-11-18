<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = User::class;

  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $role = $this->faker->randomElement(['user', 'employee']);
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'phone' => $this->faker->phoneNumber,
      'address' => $this->faker->streetAddress,
      'city' => $this->faker->city,
      'province' => $this->faker->city,
      'role' => $role,
      'is_customer' => $role == 'user' ? $this->faker->randomElement([0, 1]) : 0,
      'is_supplier' => $role == 'user' ? $this->faker->randomElement([0, 1]) : 0,
      'comments' => $this->faker->text,
      'email_verified_at' => now(),
      'password' => static::$password ??= Hash::make('password'),
      'remember_token' => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}
