# Candidate Categorization Platform

Live demo: https://your-live-demo-url.example.com

Replace the URL above with your deployed URL. If running locally, the app is typically served at http://localhost:8000 after starting the dev server.


## Project Overview
A Laravel-based platform to register candidates and automatically assign them to tiers (T0 – T4) based on a brief skill assessment. Includes a public candidates list, detail pages, and an Admin panel powered by Filament where you can view stats.


## Technologies Used
- PHP 8.2.12
- Laravel 12
- Livewire 3
- Filament 4 (Admin panel widgets/pages)
- Tailwind CSS 4
- Pest 3 (tests)
- Laravel Sail (optional, for Docker-based local dev)
- Laravel Pint (code style)
- Caching: Laravel Cache (file/redis compatible)


## Requirements
- PHP >= 8.2
- Composer
- Node.js 20+ and npm 10+
- A MySQL/MariaDB or PostgresSQL database (SQLite also works for quick starts)


## Setup Instructions
1. Clone the repository
   - git clone <repo-url>
   - cd candidate_cat_platform

2. Copy and configure environment
   - cp .env.example .env
   - Update DB_*, CACHE_DRIVER, QUEUE_CONNECTION, and APP_URL in .env

3. Install dependencies
   - composer install
   - npm install

4. Generate application key
   - php artisan key:generate

5. Set up the database
   - Create an empty database locally
   - php artisan migrate
   - Optionally seed sample data: php artisan db:seed --class=CandidateSeeder

6. Build frontend assets
   - For development: npm run dev
   - For production: npm run build

7. Start the application
   - php artisan serve
   - Visit http://localhost:8000


## How to Run Locally (Quick Start)
- Copy .env and set DB connection
- Run: composer install && npm install
- Run: php artisan key:generate
- Run DB migrations: php artisan migrate
- Start servers: npm run dev and php artisan serve
- Visit: http://localhost:8000

If assets don’t show correctly, run npm run build or composer run dev (if defined). If you see a Vite manifest error, rebuild assets.


## Admin Panel (Filament)
- URL: /admin
- Authentication is required. If you don’t have a user yet, create one:
  - php artisan make:filament-user --name="Admin User" --email=admin@example.com
  - Then set a password when prompted.
- If using a fresh DB, ensure users' table exists (Laravel’s default migrations) and then create your first admin using the command above.

Credentials (example)
- Email: admin@example.com
- Password: the password you set when creating the Filament user


## Database Setup Details
- Tables are created via migrations. Key ones:
  - candidates: id, name, email (unique), phone, assessment (JSON), tier (tinyint), timestamps
- Performance indexes:
  - Indexes on tier, created_at, name, and phone to speed filtering/search/sort
- Apply migrations:
  - php artisan migrate


## Caching & Performance
- Tier stats and total candidates are cached for 5 minutes to reduce repeated COUNT(*) queries.
- Cache keys:
  - candidates:tier-stats (invalidated after creating a candidate)
  - candidates:total-count
- To clear caches during development:
  - php artisan optimize:clear


## Brief Documentation
### Tier Assignment Logic
The tier is computed when a candidate is created based on the following assessment fields:
- knows_html_css_js: boolean
- knows_react_next: none | basic | advanced
- can_build_crud_with_db: boolean
- can_auth_password_google: boolean
- knows_express_hono_or_laravel: none | basic | proficient
- knows_golang: boolean

Rules (from the highest matched down):
- Tier 4: knows_golang AND (knows_express_hono_or_laravel in [basic, proficient]) AND can_auth_password_google
- Tier 3: !knows_golang AND can_auth_password_google AND (knows_express_hono_or_laravel in [basic, proficient])
- Tier 2: can_auth_password_google AND (knows_react_next != none)
- Tier 1: can_build_crud_with_db AND NOT can_auth_password_google
- Tier 0: knows_html_css_js AND (knows_react_next in [none, basic])
- Fallback: Tier 0

Note: The UI shows tiers T0 – T5 for completeness, but current logic assigns only T0 – T4. T5 is reserved for future use and will appear as 0 unless added.

### Assumptions
- Phone is optional.
- Assessment answers are trusted as self-reported and stored in a JSON column.
- The public candidates list is readable without authentication; admin analytics require login.
- Sorting and filtering are limited to columns shown in the list for performance.

### Challenges & How They Were Solved
- Performance: Initial slowness was traced to a broken static count() override and N+1-style repeated COUNT queries per tier.
  - Fixed by removing the override, selecting only necessary columns, grouping counts in a single query, and caching results for 5 minutes.
  - Added DB indexes on tier, created_at, name, and phone for faster filtering and sorting.
- Consistency of stats after writing:
  - Cache invalidated on candidate creation to ensure widgets and list stats stay fresh.


## Running Tests
- php artisan test
- Filter by file or name as needed, e.g.: php artisan test tests/Feature --compact


## Common Commands
- php artisan migrate
- php artisan db:seed --class=CandidateSeeder
- php artisan optimize:clear && php artisan optimize
- vendor\bin\pint (code style)


## Environment Notes
- Configure APP_URL in .env (e.g., http://localhost:8000) so generated URLs are correct.

### Connecting to Neon (PostgreSQL)
- Preferred: set a single DATABASE_URL env var provided by Neon. Example:
  - DATABASE_URL="postgresql://neondb_owner:YOUR%21ENCODED%3APASSWORD@ep-your-neon-endpoint.aws.neon.tech:5432/neondb?sslmode=require&pgbouncer=true&connect_timeout=10"
- Important:
  - If your password contains special characters like! : = @, you must URL-encode it in the URL. For example, ! becomes %21.
  - Do not paste values like "endpoint=...;npg_..." into DB_PASSWORD. That string is not a raw password. Use the exact password value Neon shows, or use the full DATABASE_URL from Neon.
  - On many hosts, setting DATABASE_URL is the easiest way. This app now prefers DB_URL/DATABASE_URL automatically when present.
  - Keep sslmode=require for Neon.
- Alternatively, set the split DB_* vars (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD). Ensure DB_PASSWORD is only the password (no endpoint= prefix).

- For Docker users, consider Laravel Sail (optional):
  - cp .env.example .env; set SAIL_ variables
  - composer require laravel/sail --dev && php artisan sail:install
  - ./vendor/bin/sail up -d


## License
This project is open-sourced software licensed under the MIT license.
