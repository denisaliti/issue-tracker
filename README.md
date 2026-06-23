# Issue Tracker

A mini issue tracker built with Laravel 13, where teams can manage projects, issues, tags, and comments.

I built this as a technical task — it took me a full night but I learned a lot from it.

## What it does

- Create and manage projects with start dates and deadlines
- Add issues to projects with status (open, in progress, closed), priority (low, medium, high), due dates and assigned team members
- Filter issues by status, priority, or tag
- Search issues by title or description (with debounce)
- Attach tags to issues via AJAX without page reload
- Add, edit and delete comments on issues via AJAX
- Register and login with authentication
- Only the project creator can edit or delete their project (authorization policy)

## Tech Stack

- Laravel 13
- PHP 8.5
- MySQL
- Blade templates
- Bootstrap 5
- JavaScript / AJAX
- Laravel Breeze (authentication)

## How to run it locally

1. Clone the repo
```bash
git clone https://github.com/denisaliti/issue-tracker.git
cd issue-tracker
```

2. Install dependencies
```bash
composer install
npm install
npm run build
```

3. Copy the env file and generate app key
```bash
copy .env.example .env
php artisan key:generate
```

4. Set up your database in `.env`

DB_DATABASE=issue_tracker

DB_USERNAME=root

DB_PASSWORD=

5. Run migrations and seed demo data
```bash
php artisan migrate --seed
```

6. Start the server
```bash
php artisan serve
```

## Demo accounts

After seeding you can login with:

| Email | Password |
|-------|----------|
| denis@test.com | password |
| manager@test.com | password |
| test@test.com | password |

## Features breakdown

**Projects** — full CRUD, only the owner can edit or delete their project

**Issues** — full CRUD with filters by status, priority and tag. Assign team members when creating an issue

**Tags** — create tags with custom colors, attach and detach from issues via AJAX modal

**Comments** — loaded via AJAX with pagination, add and edit comments without page reload

**Search & Filters** — two filtering approaches: dropdown filters (status, priority, tag) apply on button click, and a live text search that filters instantly with 400ms debounce as you type

**Auth** — register, login, logout via Laravel Breeze