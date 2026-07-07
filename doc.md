# ERP Admin UI Migration — Tabler v1.4.0

## Overview

Migrated the admin UI from the old custom theme to **Tabler v1.4.0** (full HTML/CSS/JS migration). All backend PHP/Blade logic, routes, permissions, and sections are preserved — purely a frontend update.

---

## Files Added

| File | Description |
|------|-------------|
| `public/tabler/` | Tabler v1.4.0 built dist (css/, js/, img/, libs/) — copied from `core/dist/` |

---

## Files Modified

### Layout & Includes

| File | Changes |
|------|---------|
| `resources/views/admin/master.blade.php` | New structure: `.page` → sidebar → topbar → `.page-wrapper` → `.page-body` → `container-xl`. Kept all `@include`, `@yield` directives. |
| `resources/views/admin/includes/header.blade.php` | Removed all old CSS (`style.min.css`, `flot/float-chart.css`, etc.). Added Tabler CSS (`tabler.min.css`, `tabler-vendors.min.css`), Tabler theme JS in `<head>`, Font Awesome, jQuery, DataTables, toastr, select2. Added **lds-ripple spinner CSS** (was lost after old theme removal). Added **Bootstrap 5 jQuery compatibility bridge** — patches `$.fn.modal()` so all existing `$('#modal').modal('show')` calls work with Bootstrap 5 via polling. |
| `resources/views/admin/includes/js.blade.php` | Removed old JS (`waves.js`, `sidebarmenu.js`, `custom.min.js`, flot, `chart-page-init.js`). Added `tabler.min.js` (removed `defer` — unnecessary at body bottom). Kept DataTables, toastr, select2, sweetalert2, mousetrap, jquery-validation, CKEditor. |
| `resources/views/admin/includes/topbar.blade.php` | Converted to Tabler `<header class="navbar navbar-expand-md">`. Kept company name, user avatar dropdown with logout. Added loading spinner div. |
| `resources/views/admin/includes/sidebar.blade.php` | Converted to Tabler `<aside class="navbar navbar-vertical">`. All menu items use `.nav-item`/`.dropdown`/`.dropdown-menu` with `data-bs-toggle="dropdown"`. Preserved ALL PHP permission checks (`@can`), routes, URLs. Nested dropdowns use `.dropend`. Change Password modal converted to Bootstrap 5 syntax (`btn-close`, `form-select`, etc.). |

### Dashboard

| File | Changes |
|------|---------|
| `resources/views/admin/includes/dashboard.blade.php` | Rewrote all stat cards from old `card card-hover` + `box bg-*` pattern to Tabler's modern `card card-sm` + colored avatar with icon pattern. Cards show icon, bold number, and secondary label. Used `g-3` gutters. |

### Authentication

| File | Changes |
|------|---------|
| `resources/views/auth/login.blade.php` | Rewrote from old theme to Tabler's sign-in design (`.page.page-center`, `.container-tight`, `.card.card-md`). Added show/hide password toggle. Added **Demo Login** section with 3 one-click buttons. Loading `tabler.min.css`, Font Awesome, Inter font. |
| `app/Http/Controllers/Auth/LoginController.php` | Cleaned up commented-out code (removed unused `authenticate()` method and `CompanySetting` imports). |

---

## Key Design Decisions

| Decision | Reasoning |
|----------|-----------|
| **Kept Font Awesome icons** instead of migrating to Tabler SVG icons | Too many views reference FA icons; would break existing UI |
| **Used Bootstrap 5 dropdown-based sidebar** (Tabler native) | Instead of old collapse-based sidebar |
| **Kept jQuery in `<head>`** | Existing app JS (AJAX, select2, DataTables) depends on it |
| **Added Bootstrap 5 jQuery compatibility bridge** | Hundreds of `$('#modal').modal('show')` calls across ~40+ view files would break with BS5; bridge patches `$.fn.modal()` to use native `bootstrap.Modal` API |
| **No npm in Laravel project** | Tabler dist is copied directly to `public/tabler/` |

---

## Demo Login

Three one-click demo login buttons on the login page. Each fills the credentials and submits the form:

| Button | Email | Password |
|--------|-------|----------|
| Super Admin | `super.admin@gmail.com` | `12345678` |
| Manager | `manager@demo.com` | `demo1234` |
| Sales Man | `salesman@demo.com` | `demo1234` |

> **Note:** Only `super.admin@gmail.com` is pre-seeded. The other two demo accounts (`manager@demo.com`, `salesman@demo.com`) do not exist in the database and will fail login. To use them, create those users via the admin panel or a seeder first, or use the Super Admin button which works out of the box.

---

## Asset Paths

| Asset | Path |
|-------|------|
| Tabler CSS | `public/tabler/css/tabler.min.css` |
| Tabler Vendors CSS | `public/tabler/css/tabler-vendors.min.css` |
| Tabler JS | `public/tabler/js/tabler.min.js` |
| Tabler Theme JS | `public/tabler/js/tabler-theme.min.js` |
| Font Awesome | `public/backend/dist/css/icons/font-awesome/css/fontawesome-all.min.css` |

---

---

## 2026-07-07 — Laravel Pint Installed & Run

| Action | Detail |
|--------|--------|
| **Installed** | `laravel/pint` v1.29.3 as a dev dependency via `composer require --dev` |
| **Command** | `./vendor/bin/pint` (no config — used default Laravel preset) |
| **Result** | **327 files fixed**, 242 style issues corrected across controllers, models, migrations, seeders, routes, config, and Blade components |
| **Key fixes** | `class_attributes_separation`, `indentation_type`, `single_space_around_construct`, `function_declaration`, `method_argument_space`, `ordered_imports`, `no_unused_imports`, `no_extra_blank_lines`, `binary_operator_spaces`, `fully_qualified_strict_types`, `trailing_comma_in_multiline`, etc. |

---

## Known Issues / Gotchas

1. **Individual page views** loaded via `@yield('content')` still use old theme HTML classes and may look broken. Each page view needs individual migration (card wrappers, table classes, button styles, etc.).
2. **`dropend` nested dropdowns** in the sidebar may need custom JS in Tabler — Bootstrap 5 doesn't support nested dropdowns natively. Tabler may handle this, but verify in browser.
3. **Demo Manager/Sales Man** users don't exist by default — only Super Admin is seeded.
4. **Some old theme CSS classes** (`.bg-cyan`, `.bg-xlg-*`, etc.) may still be referenced in individual views — those won't render until each view is migrated.
