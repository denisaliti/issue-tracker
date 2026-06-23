<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $tags = [
            ['name' => 'bug', 'color' => '#fc8181'],
            ['name' => 'feature', 'color' => '#68d391'],
            ['name' => 'urgent', 'color' => '#fc2121'],
            ['name' => 'design', 'color' => '#76e4f7'],
            ['name' => 'backend', 'color' => '#b794f4'],
            ['name' => 'frontend', 'color' => '#f6ad55'],
            ['name' => 'security', 'color' => '#fc8181'],
            ['name' => 'performance', 'color' => '#68d391'],
            ['name' => 'documentation', 'color' => '#a0aec0'],
            ['name' => 'testing', 'color' => '#76e4f7'],
        ];

        $tag = $this->faker->unique()->randomElement($tags);

        return [
            'name' => $tag['name'],
            'color' => $tag['color'],
        ];
    }
}