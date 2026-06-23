<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        $comments = [
            'I will start working on this today.',
            'This is blocking our release, needs urgent attention.',
            'I have investigated the issue, it seems to be a caching problem.',
            'Fixed in the latest commit, please review.',
            'Needs more information before we can proceed.',
            'I have assigned this to the frontend team.',
            'This is related to the issue we had last sprint.',
            'Please add test cases before merging.',
            'Deployed to staging, please test.',
            'Waiting for design approval before implementing.',
        ];

        return [
            'author_name' => $this->faker->name(),
            'body' => $this->faker->randomElement($comments),
        ];
    }
}