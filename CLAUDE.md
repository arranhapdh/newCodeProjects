# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A PHP meal planning web application with Claude AI-powered meal suggestions. Uses SQLite for storage and PHP's built-in server for development. Supports individual and household meal planning with dietary preferences.

## Development Commands

```bash
# Start the dev server
php -S localhost:8000 -t meal-planner/public

# Initialize/reset the database
php meal-planner/database/migrate.php

# Initialize with seed data (creates admin/admin123 user)
php meal-planner/database/migrate.php --seed
```

The app requires the `CLAUDE_API_KEY` environment variable set to an Anthropic API key for AI meal suggestions.

## Architecture

**No framework, no Composer, no autoloader.** Everything is manually `require`d in `meal-planner/public/index.php`, which is the single entry point.

### Routing

Query-string based routing: `?page=dashboard`, `?page=calendar`, etc. Routes map to files in `meal-planner/pages/`. The route table is defined in `public/index.php`.

### Directory Structure

- `config/` — App constants (`app.php`), DB connection (`database.php`), AI config (`ai.php`)
- `src/` — Core logic: auth functions, helpers, middleware, models (procedural, not OOP), AI services
- `pages/` — Route handlers (controllers). Each file handles both GET display and POST form submission.
- `templates/` — PHP template files rendered by page handlers. `layout.php` wraps all pages.
- `database/` — SQLite DB file (`meal_planner.db`), `schema.sql`, `seed.sql`, `migrate.php`
- `public/` — Web root with `index.php` entry point and `assets/` (CSS/JS)

### Key Patterns

- **Models are procedural functions**, not classes (e.g., `meal_save()`, `mealplan_get_or_create_for_user()`, `household_create()`). All in `src/models/`.
- **Database**: SQLite via PDO singleton from `get_db()`. WAL mode and foreign keys enabled.
- **Auth**: Session-based. `current_user()` returns the logged-in user array or null. `require_login()` and `require_admin()` guard pages.
- **CSRF**: All POST forms must include `<?= csrf_field() ?>` and handlers must call `verify_csrf()`.
- **Flash messages**: Use `flash($message, $type)` to set, rendered by `templates/partials/flash.php`.
- **Output escaping**: Use `e($value)` (wraps `htmlspecialchars`) for all user data in templates.

### AI Integration

- `ClaudeService.php` — Low-level API wrapper calling the Anthropic Messages API. Logs all requests to `ai_requests` table.
- `MealSuggester.php` — Builds prompts for meal suggestions/modifications. Expects JSON array responses from Claude. Handles parsing and validation.
- AI features: generate a full week of meals, modify existing plans via natural language.

### Meal Plans

Meal plans belong to either a user OR a household (mutually exclusive, enforced by CHECK constraint). Each plan covers one week (keyed by `week_start` date, always a Monday). Meals have a day (1-7), slot (breakfast/lunch/dinner), and optional time/calories.

### Households

Users can create/join households via invite codes. Household meal plans aggregate dietary preferences from all members. Membership roles: owner and member.
