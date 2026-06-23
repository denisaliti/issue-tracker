<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $projects = [
            ['name' => 'E-Commerce Platform', 'description' => 'Build a full featured online store with payment integration and inventory management.'],
            ['name' => 'Mobile Banking App', 'description' => 'Develop a secure mobile banking application with real-time transactions.'],
            ['name' => 'HR Management System', 'description' => 'Internal tool for managing employees, payroll, and performance reviews.'],
            ['name' => 'Customer Support Portal', 'description' => 'A ticketing system for handling customer complaints and support requests.'],
            ['name' => 'Company Website Redesign', 'description' => 'Redesign the company website with modern UI/UX and improved SEO.'],
        ];

        $project = $this->faker->unique()->randomElement($projects);

        return [
            'name' => $project['name'],
            'description' => $project['description'],
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'deadline' => $this->faker->dateTimeBetween('+1 month', '+3 months'),
        ];
    }
}