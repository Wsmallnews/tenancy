# CLAUDE.md — resourcedb

## Project Overview

Resource Database (资源库) — a standalone Laravel 12 + Filament 4 admin panel within the nhgrc-website monorepo. Manages germplasm resources, research outputs, and news articles for individual institutions (tenants), then syncs selected data to the main NHGRC API via HTTP.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+, Filament 4, Livewire
- **Frontend**: Vite 7, Tailwind CSS 4.1, Blade + Livewire components, Swiper
- **Database**: SQLite (dev default), MySQL (production)
- **Testing**: Pest 3.8
- **Auth/RBAC**: Spatie Permission + Filament Shield, 2FA support (wsmallnews/user)
- **Media**: Spatie Media Library
- **Key packages**: spatie/laravel-activitylog, spatie/laravel-tags, spatie/laravel-settings, kalnoy/nestedset, overtrue/laravel-pinyin
- **Custom packages**: wsmallnews/cms, wsmallnews/category, wsmallnews/user, wsmallnews/support, wsmallnews/filament-nestedset

## Development Commands

```bash
# Full dev environment (server + queue + vite concurrently)
composer dev

# Frontend only
npm run dev       # Vite dev server
npm run build     # Production build

# Database
php artisan migrate
php artisan migrate:fresh --seed

# Create super-admin (specify user ID and tenant ID)
php artisan shield:super-admin --user=1 --tenant=1

# Testing
php artisan test
php artisan test --coverage

# Code formatting
./vendor/bin/pint
```

## Architecture

### Two Admin Panels

| Panel | Path | Guard | Purpose |
|-------|------|-------|---------|
| Admin | `/admin` | `admin` | System administration, full resource management |
| Platform | `/platform` | `platform` | Tenant-scoped resource curation |

Access is controlled by `user_type` field on the User model (`'admin'` or `'platform'`).

### Multi-Tenancy

Team-based multi-tenancy. Each Team owns its own set of resources (appraises, posts, patents, etc.). Admin panel routes include tenant slug: `/admin/tenant/{tenant:slug}`.

### Panel Navigation Groups

**Admin panel:**
- 网站管理 (Website Management)
- 种质资源库(圃) (Germplasm Resource Library)
- 属性选项 (Property Options)
- 研究成果 (Research Results)
- 设置管理 (Settings Management)
- Permission Management

**Platform panel:**
- 资源库管理 (Resource Library Management)
- Permission Management

### Frontend (Livewire Pages)

Public routes:
- `GET /appraises/{id}` — Germplasm detail
- `GET /personnels` — Staff listing
- `GET /personnels/{id}` — Staff detail

Authenticated routes (middleware: `cms-auth`):
- `GET /user/appraise-applies` — User's germplasm applications
- `GET /user/appraise-applies/{id}` — Application detail

## Data Sync to NHGRC API

### Mechanism

Manual user-triggered actions (Filament table/bulk actions), not scheduled. HTTP POST via Guzzle with authentication headers.

### Authentication

Headers: `X-App-Key` and `X-App-Secret` (configured in `config/nhgrc.php` per environment).

### Endpoints

| Environment | Base URL |
|-------------|----------|
| Production | `http://www.nhgrc.cn/` |
| Test | `http://nhgrc.eepu.top/` |

API paths: `agricultural/external/api/*`

### Sync Operations

| Method | What it does |
|--------|-------------|
| `submitGermplasm($appraise)` | Submit single germplasm resource |
| `batchSubmitGermplasm($appraises)` | Batch submit germplasm |
| `submitArticle($article)` | Submit single article |
| `batchSubmitArticle($articles)` | Batch submit articles |
| `uploadImage($media)` | Upload cover image to NHGRC |
| `checkStatus($appraiseId)` | Query submission status |
| `getClassifications()` | Fetch category tree from NHGRC |

### Tracking Fields

- `nhgrc_pending_id` — NHGRC pending approval ID
- `nhgrc_external_ref` — External reference (format: `resourcedb-germplasm-{id}`)

### Key Files

- `app/Features/Nhgrc/Nhgrc.php` — Main sync service (data transformation + API calls)
- `app/Features/Nhgrc/Client.php` — HTTP client (auth headers, URL construction, response parsing)
- `config/nhgrc.php` — Environment config (app_key/app_secret per env)

## Key Models

| Model | Purpose |
|-------|---------|
| User | System users (admin/platform types) |
| Team | Multi-tenant teams |
| Appraise | Germplasm resources (core business entity) |
| Preserve | Preservation records (belongs to Appraise) |
| Patent | Patent records |
| Award | Award records |
| Thesis | Research papers |
| NewVariety | New variety records |
| AccurateIdentify | Identification records |
| Personnel | Staff members |
| Company | Organizations |
| Post | News articles (via wsmallnews/cms) |
| Category | Resource categories (nested set) |
| AppraiseApply | Germplasm application requests |

## Directory Structure

```
resourcedb/
├── app/
│   ├── Enums/              # Status enums, type enums
│   ├── Features/
│   │   └── Nhgrc/          # NHGRC API sync (Client.php, Nhgrc.php)
│   ├── Filament/
│   │   ├── Resources/      # Admin panel resources (~25)
│   │   └── Platform/       # Platform panel resources
│   ├── Http/Middleware/     # CheckTenant, IdentifyTenant
│   ├── Livewire/           # Frontend page components
│   ├── Models/             # Eloquent models (~28)
│   ├── Observers/          # Model observers
│   ├── Policies/           # Authorization policies
│   └── Providers/Filament/ # AdminPanelProvider, PlatformPanelProvider
├── config/
│   └── nhgrc.php           # NHGRC API credentials per environment
├── database/migrations/    # ~31 migration files
├── resources/views/        # Blade templates
├── routes/web.php          # Frontend routes
├── composer.json
└── package.json
```

## SSO Integration Status

Currently uses independent authentication (Laravel default + Filament panel guards). The User model has a `user_type` field controlling panel access. SSO integration with the main NHGRC API project is a planned requirement but not yet implemented.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan shield:super-admin --user=1 --tenant=1
composer dev
```

## Active Technologies
- PHP 8.2+ (后端), Laravel 12, Filament 4, Livewire, Blade + Tailwind CSS 4.1 (前端)
- SQLite (开发) / MySQL (生产), Spatie Permission + Filament Shield (RBAC)
- Vite 7, Pest 3.8, wsmallnews/* 自定义包
