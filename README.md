# Empapy Coffee ☕

**Empapy Coffee** is a full-stack e-commerce application for a coffee business, built with Laravel. It brings together a customer storefront, configurable coffee products, order processing, loyalty rewards, and tools for store administrators and staff.

[![CI](https://github.com/MohamedSayedAbdelrazek/Empapy_Caffee/actions/workflows/ci.yml/badge.svg)](https://github.com/MohamedSayedAbdelrazek/Empapy_Caffee/actions/workflows/ci.yml)

## What it does

The application covers the shopping journey from product discovery through checkout and order tracking, plus back-office workflows for managing products, orders, customers, staff permissions, and promotions.

### Customer-facing features

- Coffee catalog with categories, product details, and configurable options (such as weight, roast level, and additives)
- Shopping cart, checkout, coupons, and governorate-based shipping calculations
- Order tracking, order history, and order cancellation workflows
- Customer registration, sign-in, password reset, and account management
- Wishlists and product reviews
- Loyalty points, reward redemption, referral flows, and loyalty tiers

### Store management

- Admin dashboard and product/category management
- Order management, including a Kanban-style order view and payment/order statuses
- Role- and permission-based access for administrators and staff
- Coupon, announcement, shipping-zone, and site-setting management
- Staff/customer management and administrative notifications

### Engineering highlights

- Laravel service classes for order, cart, shipping, product, and loyalty logic
- Server-side price calculation and database transactions for important order flows
- Access-control checks and rate limiting on selected routes
- Automated feature/unit tests and a GitHub Actions CI workflow
- Docker, Nginx, and MySQL configuration for deployment workflows

## Tech stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 11 |
| Frontend | Laravel Blade, JavaScript, Tailwind CSS 3, Vite |
| Database | MySQL 8 |
| Testing | PHPUnit, Laravel feature/unit tests |
| CI | GitHub Actions |
| Infrastructure | Docker, Nginx |

## Getting started (local development)

### Prerequisites

- PHP 8.2+ with the extensions required by Laravel
- Composer
- Node.js and npm
- MySQL 8

### Install and run

1. Clone the repository and install dependencies:

   ```bash
   git clone https://github.com/MohamedSayedAbdelrazek/Empapy_Caffee.git
   cd Empapy_Caffee
   composer install
   npm ci
   ```

2. Create a local environment file:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Create a **local development database** in MySQL and update the database settings in `.env`:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=empapy_caffee
   DB_USERNAME=your_local_username
   DB_PASSWORD=your_local_password
   ```

   The example environment file defaults to SQLite, but this project's database schema and CI setup use MySQL-specific behavior. MySQL is recommended for local development.

4. Create the database tables and public storage symlink:

   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. Start the development servers in separate terminals:

   ```bash
   php artisan serve
   ```

   ```bash
   npm run dev
   ```

   Visit **http://127.0.0.1:8000**.

> **Seed-data caution:** Review `database/seeders/DatabaseSeeder.php` before running `php artisan db:seed`. It includes example account credentials; do not use those credentials in a publicly accessible environment.

## Testing

The test configuration in `phpunit.xml` expects a **separate MySQL test database** named `empapy_caffee2_test`.

Create that database locally, configure your test-database connection credentials, and run:

```bash
php artisan test
```

GitHub Actions also runs the test suite against MySQL 8 and checks formatting for the test/factory code. See [the CI workflow](.github/workflows/ci.yml).

## Project structure

```text
app/
  Http/Controllers/   # Storefront, account, checkout, and admin endpoints
  Models/             # Products, orders, users, rewards, and related entities
  Services/           # Business logic and third-party integrations
database/
  migrations/         # Database schema
  seeders/            # Sample and configuration data
resources/
  views/              # Blade templates
  css/ and js/        # Frontend assets
routes/
  web.php             # Application routes
tests/
  Feature/ and Unit/  # Automated tests
.github/workflows/
  ci.yml              # Continuous integration
```

## AI-assisted development

AI coding assistance has been used in developing this project. The repository tracks the implementation and subsequent improvements, including security fixes, refactoring, and automated tests.

## Maintainer

[@MohamedSayedAbdelrazek](https://github.com/MohamedSayedAbdelrazek)
