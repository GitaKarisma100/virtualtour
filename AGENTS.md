# AGENTS.md — virtualtour

## Stack
- **Laravel 12** (PHP 8.2+) + **Jetstream** (Livewire stack) + **Sanctum** + **Fortify** (2FA, passkeys)
- **Vite 7** for assets, **Tailwind CSS 3.4** with custom design tokens
- **MySQL** (Laragon default: `virtualTour` db, root, no password)
- **PHPUnit 11** (not Pest, despite `pestphp/pest-plugin` being allowed in composer.json)

## Developer Commands
| Task | Command |
|---|---|
| Full dev (server + queue + logs + vite) | `composer run dev` |
| Run all tests | `composer run test` or `php artisan test` |
| Run a single test file | `php artisan test tests/Feature/ExampleTest.php` |
| Run a single test method | `php artisan test --filter=testName` |
| Lint / format PHP | `vendor/bin/pint` |
| Vite dev server only | `npm run dev` |
| Vite production build | `npm run build` |
| Fresh setup | `composer run setup` |

## Architecture
- **Entry point**: `bootstrap/app.php` — registers `routes/web.php`, `routes/api.php`, `routes/console.php`
- **Health check**: `/up`
- **Providers**: `AppServiceProvider`, `FortifyServiceProvider`, `JetstreamServiceProvider`
- **Auth**: Sanctum guard, Jetstream Livewire components, 2FA + passkeys enabled
- **Jetstream features**: only `accountDeletion` is active (API, teams, profile photos, terms/privacy are commented out in `config/jetstream.php`)
- **Admin UI**: `resources/views/admin/` contains static HTML mockups using **CDN Tailwind** (not wired to routes or Vite). These are design templates, not functional pages.

## Database
- Migrations exist for: users (with 2FA columns), cache, jobs, passkeys, personal_access_tokens
- Default DB in `.env`: MySQL `virtualTour` on `127.0.0.1:3306` (Laragon convention)
- Tests use SQLite `:memory:` (configured in `phpunit.xml`)

## Testing
- PHPUnit config at `phpunit.xml` — Unit (`tests/Unit`) and Feature (`tests/Feature`) suites
- Tests ship with Jetstream scaffolding (auth, profile, API tokens, 2FA)
- No custom application tests written yet

## Gotchas
- `.env` is gitignored; copy from `.env.example` if missing
- Admin views use CDN Tailwind — changes there won't go through Vite build
- No CI workflows, pre-commit hooks, or custom linter rules beyond Laravel Pint defaults
