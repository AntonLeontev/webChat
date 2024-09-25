<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_id' => Chat::inRandomOrder()->first()->id,
            'message_id' => $this->faker->randomDigitNotZero(),
            'text' => $this->faker->text($this->faker->numberBetween(5, 300)),
            'from' => $this->faker->optional(50, 5866110181)->numberBetween(1111111),
        ];
    }
}
