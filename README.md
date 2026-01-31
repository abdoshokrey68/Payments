# Payments — Laravel Modular API

A Laravel 12 API built with a **modular architecture** using [nwidart/laravel-modules](https://github.com/nwidart/laravel-modules). It provides authentication, product catalog, order management, and an extensible payment gateway system.

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a>
</p>

---

## Table of Contents

- [Payments — Laravel Modular API](#payments--laravel-modular-api)
  - [Table of Contents](#table-of-contents)
  - [Requirements](#requirements)
  - [Installation](#installation)
    - [1. Clone and install dependencies](#1-clone-and-install-dependencies)
    - [2. Environment configuration](#2-environment-configuration)
    - [3. Database setup](#3-database-setup)
    - [4. Module discovery](#4-module-discovery)
    - [5. Serve the application](#5-serve-the-application)
  - [Database Diagram](#database-diagram)
  - [API Testing (Postman)](#api-testing-postman)
  - [Payment Gateway Extensibility](#payment-gateway-extensibility)
    - [Design](#design)
  - [Running Tests](#running-tests)
  - [License](#license)

---

## Requirements

- **PHP** 8.2+
- **Composer** 2.x
- **Laravel** 12.x

---

## Installation

### 1. Clone and install dependencies

```bash
git clone git@github.com:abdoshokrey68/Payments.git
cd Payments
composer install
```

### 2. Environment configuration

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=payments
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database setup

Run migrations to create all tables (application + modules):

And seed the products with sample data:

```bash
php artisan migrate --seed
```

### 4. Module discovery

Modules are auto-discovered via `config/modules.php`. Ensure `Modules` is listed in the `modules` path. No extra command is required for Laravel Modules v12.

### 5. Serve the application

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`. Use the base URL for all API requests (e.g. `http://localhost:8000/api/...`).

---

## Database Diagram

The full database schema (tables and relationships) is documented in DbDiagram:

**🔗 [View Database Diagram](https://dbdiagram.io/d/6977e595bd82f5fce2acc26a)**

Use this diagram to understand entities (users, products, orders, order_items, payments) and their relationships.

---

## API Testing (Postman)

A Postman collection is provided in the **project root** for testing all API endpoints.

**File:** `Payment_API.postman_collection.json`

**How to use:**

1. Open [Postman](https://www.postman.com/).
2. Click **Import** and choose **Upload Files** or drag-and-drop.
3. Select `Payment_API.postman_collection.json` from the project root directory.
4. After import, set the collection/base URL variable to your app URL (e.g. `http://localhost:8000`) if needed.
5. Use the collection to call Auth, Products, Orders, and Payment endpoints.

The collection includes the main API routes; you can extend it with more examples or environments as needed.

---

## Payment Gateway Extensibility

The payment system is designed so that new gateways can be added without changing core payment logic.

### Design

1. **Interface**  
   All gateways implement `Modules\Payment\Interfaces\PaymentGatewayInterface`:

   ```php
   interface PaymentGatewayInterface
   {
       public function process($order_id, $amount): bool;
   }
   ```

2. **Factory**  
   `PaymentGatewayFactory` resolves the correct gateway by **payment method** (e.g. enum value). The service receives the method from the request and asks the factory for the right gateway, then calls `process()`.

3. **Registration**  
   In `PaymentServiceProvider`, each gateway is registered as a singleton under a key such as `payment.gateway.{method}`. The factory uses that key to resolve the gateway from the container.

4. **Adding a new gateway**  
   - Create a new class (e.g. `StripeGateway`) that implements `PaymentGatewayInterface`.
   - Register it in `PaymentServiceProvider::registerPaymentGateways()` with a new key (e.g. `payment.gateway.{StripeEnumValue}`).
   - Add the corresponding value to `PaymentMethodsEnum` (and any validation/routing that uses it).

No changes are required inside `PaymentService::pay()` or the controller beyond supporting the new method value; the factory and interface handle the rest.

---

## Running Tests

```bash
composer test
# or
php artisan test
```

---

## License

The Laravel framework is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
