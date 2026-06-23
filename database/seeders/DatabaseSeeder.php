<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $tags = Tag::factory(10)->create();

        Project::factory(5)->create()->each(function ($project) use ($tags) {
            $issues = Issue::factory(5)->create(['project_id' => $project->id]);

            $issues->each(function ($issue) use ($tags) {
                $issue->tags()->attach($tags->random(2)->pluck('id'));
                Comment::factory(3)->create(['issue_id' => $issue->id]);
            });
        });
    }
}   