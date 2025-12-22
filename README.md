# Thephprame — Lightweight PHP Microframework ⚡

**Thephprame** is a compact, easy-to-understand PHP microframework for building web apps and APIs. It keeps the surface area small while using separate Composer packages for the core framework and router so the app stays clean and easy to reason about.

---

## 🔧 Key Features

- Minimal routing powered by the `thephprame-router` package
- Small, focused framework core in `thephprame-core`
- Dependency Injection with PHP-DI for controller/service wiring
- Simple controller & model structure (`App/Controllers`, `App/Models`)
- Basic middleware support (`App/Middleware`)
- Config-driven (see `Config/app.php`, `Config/database.php`)

---

## ✅ Requirements

- PHP 7.2+ (or a current PHP 7.x runtime)
- Composer

---

## 🚀 Quick Start

1. Docker

```docker compose up --build -d```

## 📦 Packages

This repository is the application shell plus local Composer packages:

- `local-packages/thephprame-core` — framework core (controllers, helpers, exceptions, etc.)
- `local-packages/thephprame-router` — request/response objects and routing engine

These packages are wired via `composer.json` and loaded through Composer autoloading.

---

## 📁 Project Layout (important files)

- `Public/index.php` — front controller
- `Bootstrap/bootstrap.php` — framework bootstrap
- `Bootstrap/container.php` — DI container setup
- `Config/di.php` — DI definitions
- `Routes/web.php`, `Routes/api.php` — route declarations
- `App/Controllers/` — your HTTP controllers
- `App/Middleware/` — middleware classes
- `App/Models/` — lightweight models
- `Config/` — app and DB configuration
- `Views/` — view templates
- `Storage/` — runtime storage (sessions, etc.)

---

## 🧭 Routing & Controllers — Example

Add routes in `Routes/web.php`:

```php
Routes::get('/', [App\Controllers\HomeController::class, 'index']);
```

Controllers are resolved via the DI container, so dependencies are injected automatically:

```php
namespace App\Controllers;

use App\Services\ExampleService;

class HomeController
{
    public function __construct(private ExampleService $service) {}

    public function index()
    {
        return view('home');
    }
}
```

---

## 🧩 Dependency Injection (PHP-DI)

The container is built in `Bootstrap/container.php` and definitions live in `Config/di.php`. Autowiring is enabled, so you only need explicit definitions for interfaces, factories, or scalar config values.

If you add a service class under `App/Services`, it can be injected into controllers and other services automatically.

---

## 🔐 Middleware & Authentication

Middleware classes live in `App/Middleware/` (e.g., `WebAuthentication`, `ApiAuthentication`). Apply middleware to routes in `Routes/*` or in your router configuration depending on convention.

---

## 💾 Configuration

Edit `Config/app.php` and `Config/database.php` to adjust environment settings and database connection details. If your project uses `.env` values, set them in your environment or a `.env` file.

---

## 🧪 Tests & Development

This repository does not include a test suite by default. For local debugging, use the PHP built-in server and add unit/integration tests as needed.

---

## Contributing

Contributions and improvements are welcome. Please open issues or submit PRs with clear descriptions and tests where appropriate.

---
