# AGENTS.md — resourcedb

## Version Reality

`CLAUDE.md` lists Laravel 12 / Filament 4 / Pest 3.8. **Ignore those — `composer.json` is canonical.** Actual versions: PHP ^8.3, Laravel ^13, Filament ^5, Pest ^4, Vite ^7, Tailwind ^4.1.

## Commands

```bash
composer dev            # Full dev: artisan serve + queue:listen + vite (concurrently)
php artisan test --compact --filter=testName   # Run specific test
vendor/bin/pint --dirty --format agent         # Format changed PHP files (required after edits)
```

Do NOT run `php artisan serve` — the app is served by Laravel Herd at `https?://tenancyv4.test`.

## Architecture

Two Filament panels with separate guards:

| Panel | Path | Guard | Purpose |
|-------|------|-------|---------|
| Admin | `/admin` | `admin` | Full resource management |
| Platform | `/platform` | `platform` | Tenant-scoped curation |

User access is controlled by `user_type` field (`'admin'` or `'platform'`).

**Team-based multi-tenancy.** Each Team owns its resources. Admin routes include tenant slug: `/admin/tenant/{tenant:slug}`.

## Key Gotchas

- **`Model::unguard()`** is called in `AppServiceProvider::boot()` — all models are unguarded.
- **Filament SPA mode** is enabled (`FilamentView::spa(true)`).
- **`RefreshDatabase` is commented out** in `tests/Pest.php` — tests use the real database.
- **Filament 5 namespace changes**: Layout components are `Filament\Schemas\Components\`, actions are `Filament\Actions\` (never `Filament\Tables\Actions\`). Use `Select::make()->relationship()` not `BelongsToSelect`.
- **`wsmallnews/*` packages** dominate the architecture — `support`, `cms`, `category`, `comment`, `preference`, `filament-nestedset`, `user`. Read package docs before modifying related code.
- **NHGRC sync** is manual (Filament table/bulk actions), not scheduled. Config in `config/nhgrc.php`.
- **SSO** with external service is implemented but session persistence across domains requires careful `.env` config (see `DIAGNOSIS.md`).

## Testing

Tests are minimal (2 feature tests). When writing new tests:
- Use Pest syntax: `php artisan make:test --pest {Name}`
- `$this->actingAs(User::factory()->create())` before panel tests
- Edit pages: `->call('save')` not `->call('create')`, no `->assertRedirect()`
- Livewire: `use function Pest\Livewire\livewire`

## Code Style

- Follow existing conventions — check sibling files before creating new ones
- Use descriptive method names (`isRegisteredForDiscounts` not `discount()`)
- PHP 8 constructor property promotion, explicit return types, curly braces always
- Run `vendor/bin/pint --dirty --format agent` after any PHP file change
