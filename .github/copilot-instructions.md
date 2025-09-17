# Copilot Instructions for tihd

## Project Overview
This is a modular Laravel application with custom modules under `Modules/`. It uses Laravel's service provider architecture and supports multi-language, user management, subscriptions, and media features. The codebase is organized for extensibility and maintainability.

## Key Architectural Patterns
- **Modules:** Each feature (e.g., Banner, User, Video) is a self-contained module in `Modules/`, with its own controllers, models, migrations, and views.
- **Service Providers:** Core services are registered in `app/Providers/` and `Modules/*/Providers/`.
- **Config Files:** Project-wide and module-specific configs are in `config/` and `Modules/*/Config/`.
- **Routes:** Main routes are in `routes/` (web, api, console, etc.), while modules may have their own route files.
- **Views:** Blade templates are in `resources/views/` and `Modules/*/Resources/views/`.
- **Helpers:** Shared helper functions are in `app/helpers.php` and `app/Helpers/`.

## Developer Workflows
- **Build:** Use `npm run dev` or `npm run prod` for asset compilation (see `webpack.mix.js`).
- **Test:** Run `php artisan test` or `vendor/bin/phpunit` for backend tests. Feature and unit tests are in `tests/Feature/` and `tests/Unit/`.
- **Debug:** Use Laravel's built-in logging (`storage/logs/`), and debugbar if enabled.
- **Migrations:** Run `php artisan migrate` for DB schema updates. Module migrations are auto-discovered.
- **Seeding:** Use `php artisan db:seed` for populating test data.

## Project-Specific Conventions
- **Module Naming:** Modules use PascalCase and are registered in `modules_statuses.json`.
- **Blade Components:** Custom Blade components are in `Modules/Frontend/Resources/views/components/`.
- **Language Support:** Translations are in `lang/` and `Modules/Language/`.
- **Media Handling:** Media files are stored in `public/storage/` and managed via `media-library` config.
- **Permissions:** Role and permission logic is in `config/permission.php` and related modules.

## Integration Points
- **External Packages:** Uses packages like `yajra/laravel-datatables`, `spatie/laravel-permission`, and `spatie/laravel-media-library`.
- **Frontend:** Vue.js components are in `public/vue/` and `resources/js/`.
- **API:** API endpoints are defined in `routes/api.php` and module-specific routes.

## Examples
- To add a new feature, create a module in `Modules/`, register it, and add its service provider.
- For a new Blade component, place it in `Modules/Frontend/Resources/views/components/` and reference it in views.
- To support a new language, add files to `lang/` and update translation logic.

## References
- `README.md`: Project intro
- `webpack.mix.js`: Asset build config
- `modules_statuses.json`: Enabled modules
- `config/`: Core and package configs
- `routes/`: Main route files

---
For questions or unclear conventions, review module structure or ask for clarification. Update this file as new patterns emerge.