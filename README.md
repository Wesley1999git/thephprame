# Thephprame — Lightweight PHP Microframework ⚡

**Thephprame** is a compact, easy-to-understand PHP microframework for building small web apps and APIs. It provides a minimal routing layer, controllers, middleware, and a simple model layer — all intentionally lightweight for clarity and learning.

---

## 🔧 Key Features

- Minimal routing (`Routes/web.php`, `Routes/api.php`)
- Simple controller & model structure (`App/Controllers`, `App/Models`)
- Basic middleware support (`App/Middleware`)
- Config-driven (see `Config/app.php`, `Config/database.php`)
- No heavy dependencies — easy to read and extend

---

## ✅ Requirements

- PHP 7.2+ (or a current PHP 7.x runtime)
- Composer

---

## 🚀 Quick Start

1. Install dependencies:

```bash
composer install
```

2. Start the built-in PHP server for local development:

```bash
php -S localhost:8000 -t Public
```

3. Open http://localhost:8000 in your browser.

---

## 📁 Project Layout (important files)

- `Public/index.php` — front controller
- `bootstrap.php` — framework bootstrap
- `Routes/web.php`, `Routes/api.php` — route declarations
- `App/Controllers/` — your HTTP controllers
- `App/Middleware/` — middleware classes
- `App/Models/` — Eloquent-style lightweight models
- `Config/` — app and DB configuration
- `Views/` — view templates
- `Storage/` — runtime storage (sessions, etc.)

---

## 🧭 Routing & Controllers — Example

Add routes in `Routes/web.php`:

```php
Routes::get('/', [App\Controllers\HomeController::class, 'index']);
```

A controller method might look like:

```php
namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // return a view, JSON, or Response object
        return view('home');
    }
}
```

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

## License

Add your license here (e.g., MIT).

---

If you'd like, I can add a short `CONTRIBUTING.md`, example routes, or a beginner tutorial page in `Docs/`. Let me know what you'd prefer! ✨
