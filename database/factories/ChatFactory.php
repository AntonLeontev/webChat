<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			'first_name' => $this->faker->firstName(),
			'username' => $this->faker->word(),
			'type' => $this->faker->word(),
			// 'small_chat_photo' => ,
			// 'last_message',
        ];
    }
}
