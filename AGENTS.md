# AGENTS.md — virtualtour

## Stack
- **Laravel 12** (PHP 8.2+) + **Jetstream** (Livewire stack, Sanctum guard) + **Fortify** (2FA, passkeys)
- **Vite 7** for Jetstream assets (`resources/css/app.css`, `resources/js/app.js`)
- **Tailwind CSS 3.4** via PostCSS + `tailwind.config.js` (v3 style); `@tailwindcss/vite` (v4) is in devDeps but **unused**
- **PHPUnit 11** (not Pest; `pestphp/pest-plugin` is allowed but unused)
- **Session/Cache/Queue default to `database` driver** in `.env` — requires `php artisan migrate`
- DB default in `.env.example`: SQLite. Laragon devs switch to MySQL (`virtualTour`, root, no password)

## Developer Commands
| Task | Command |
|---|---|
| Full dev (server + queue + logs + vite) | `composer run dev` |
| Run all tests | `composer run test` (clears config cache first, then `php artisan test`) |
| Single test file | `php artisan test tests/Feature/ExampleTest.php` |
| Single test method | `php artisan test --filter=testName` |
| Lint PHP | `vendor/bin/pint` |
| Vite dev server only | `npm run dev` |
| Vite production build | `npm run build` |
| Fresh setup | `composer run setup` (installs, migrates, builds assets) |
| Create storage symlink | `php artisan storage:link` |

## Two separate frontend stacks
1. **Vite-processed** — Jetstream auth/profile/API-token pages (`resources/views/{auth,profile,api}/`, welcome, dashboard). Tailwind purged by PostCSS. Run `npm run dev` or `npm run build`.
2. **CDN-only** — Admin CRUD (`resources/views/admin/`) and public tour pages (`resources/views/tour/`). Use CDN Tailwind, Alpine.js, Material Symbols, Photo Sphere Viewer. Changes do NOT go through Vite; refresh the browser to see them.

## Architecture
- **Models**: `Building` → hasMany `Location` → hasMany `Hotspot`. `Hotspot.target_location_id` self-references `locations` for navigation links.
- **Public routes** (`routes/web.php`): `GET /` (building index), `GET /explore` (tour index), `GET /tour/{building}` (360° viewer).
- **Admin routes** (`routes/web.php`, under `auth:sanctum` + `verified`): `admin/`, `admin/virtual-tour/buildings`, nested locations and hotspots. Preview routes: `buildings/{building}/preview`, `buildings/{building}/locations/{location}/preview`.
- **Images** stored on `public` disk (`storage/app/public/`). `TourController` prefixes with `asset('storage/...')`. Requires `php artisan storage:link`.
- **Fortify** home path is `/admin`. Features: registration, password reset, profile info/password update, 2FA, passkeys. Email verification off.
- **Jetstream features**: only `accountDeletion`. API tokens, teams, profile photos, terms/privacy disabled.

## Database
- 14 migrations: users (with 2FA columns), cache, jobs, passkeys, personal_access_tokens, + 3 core custom (buildings, locations, hotspots), + incremental (description, thumbnail, lat/lng dropped from buildings, map coordinates added to locations)
- Tests use SQLite `:memory:` (configured in `phpunit.xml`)

## Testing
- PHPUnit suites: `tests/Unit` (standalone, no Laravel) and `tests/Feature` (with app boot)
- All existing tests are Jetstream-scaffolded (auth, profile, 2FA, API tokens). No tests exist for custom models/controllers yet.
- Feature tests use `RefreshDatabase` trait.

## Gotchas
- `.env` is gitignored; copy from `.env.example` if missing
- Admin + tour views use CDN assets — no Vite build needed for changes
- `@tailwindcss/vite ^4.0.0` in devDeps is unused; do not import it (Tailwind v3 + PostCSS pipeline is active)
- No CI, no pre-commit hooks, no custom Pint rules
- `composer run test` clears config cache before running tests — don't skip this step
- `TourController::generateLocationsJson()` has unreachable code after the `return json_encode(...)` — a second `return view(...)` at line 110 is dead code
