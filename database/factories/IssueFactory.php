<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    public function definition(): array
    {
        $issues = [
            ['title' => 'Fix login page bug', 'description' => 'Users are unable to login with correct credentials on Safari browser.'],
            ['title' => 'Add payment gateway', 'description' => 'Integrate Stripe payment gateway for processing credit card payments.'],
            ['title' => 'Design dashboard UI', 'description' => 'Create a modern dashboard with charts and key metrics for admin users.'],
            ['title' => 'Optimize database queries', 'description' => 'Several pages are loading slowly due to unoptimized database queries.'],
            ['title' => 'Implement email notifications', 'description' => 'Send automated email notifications when order status changes.'],
            ['title' => 'Fix mobile responsiveness', 'description' => 'Several pages are broken on mobile devices, needs CSS fixes.'],
            ['title' => 'Add user roles and permissions', 'description' => 'Implement role based access control for admin, manager and user roles.'],
            ['title' => 'Write API documentation', 'description' => 'Document all REST API endpoints using Swagger or Postman.'],
            ['title' => 'Setup CI/CD pipeline', 'description' => 'Configure automated testing and deployment pipeline using GitHub Actions.'],
            ['title' => 'Fix security vulnerability', 'description' => 'SQL injection vulnerability found in the search endpoint, needs immediate fix.'],
        ];

        $issue = $this->faker->randomElement($issues);

        return [
            'title' => $issue['title'],
            'description' => $issue['description'],
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}