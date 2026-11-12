# Harmony Spa — Backend API

RESTful API for the Harmony Spa management system, developed with **Laravel 11** and **PHP 8.x**.

## 📌 Project Overview

Harmony Spa Backend is a REST API responsible for managing the core business operations of a spa, including authentication, user roles, appointments, treatments, sales, and inventory.

The system provides role-based access control to ensure that each user can only access the resources and functionalities assigned to their role.

## 🛠️ Tech Stack

| Technology              | Description                   |
| ----------------------- | ----------------------------- |
| **Framework**           | Laravel 11                    |
| **Language**            | PHP 8.x                       |
| **Database**            | MySQL                         |
| **Authentication**      | Laravel Sanctum (Token-based) |
| **Local Environment**   | Laravel Herd                  |
| **Database Management** | DBngin, TablePlus             |

## 👥 Roles & Permissions

The system defines four main user roles:

| Role            | Permissions                                                                                               |
| --------------- | --------------------------------------------------------------------------------------------------------- |
| **SUPER_ADMIN** | Full system access. Manages users, employees, catalog, and reports.                                       |
| **ADMIN**       | Manages daily operations, including appointments, clients, and sales. Cannot manage employees.            |
| **ESTETICISTA** | Accesses assigned appointments, registers session notes, and records product consumption.                 |
| **CLIENTE**     | Registers an account, manages their profile, books or cancels appointments, and rates completed sessions. |

## 🚦 API Endpoints (v1)

**Base URL:** `/api/v1`

### 🔐 Authentication

| Method | Endpoint         | Description                  | Access        |
| ------ | ---------------- | ---------------------------- | ------------- |
| POST   | `/auth/register` | Register a new client        | Public        |
| POST   | `/auth/login`    | Authenticate user            | Public        |
| POST   | `/auth/logout`   | Log out current user         | Authenticated |
| POST   | `/auth/refresh`  | Refresh authentication token | Authenticated |

### 👤 Client Profile

| Method | Endpoint       | Description          | Access |
| ------ | -------------- | -------------------- | ------ |
| GET    | `/clientes/me` | Retrieve own profile | Client |
| PATCH  | `/clientes/me` | Update own profile   | Client |

### ⚙️ Administration & Management

| Method | Endpoint          | Description               | Access              |
| ------ | ----------------- | ------------------------- | ------------------- |
| GET    | `/clientes`       | List registered clients   | SUPER_ADMIN / ADMIN |
| POST   | `/empleados`      | Create a new employee     | SUPER_ADMIN         |
| GET    | `/procedimientos` | List available procedures | Public              |
| POST   | `/citas`          | Book an appointment       | CLIENTE             |
| GET    | `/citas/mias`     | Retrieve own appointments | CLIENTE             |

> **Note:** This is a partial endpoint overview. For the complete list of routes, permissions, request parameters, and responses, refer to the project's API documentation matrix.

## ⚙️ Installation & Setup

### 1. Clone the repository

```bash
git clone <repository-url>
cd harmony_spa_backend
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment variables

Copy the example environment file:

```bash
cp .env.example .env
```

Configure your database connection in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=harmony_spa
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run migrations and seeders

```bash
php artisan migrate
```

### 6. Start the development server

If you are using Laravel Herd, the local environment can be managed automatically.

Alternatively, run:

```bash
php artisan serve
```

## 🔑 Creating the First Super Admin

For security reasons, the system does not expose a public endpoint for creating Super Admin accounts.

Instead, use the custom Artisan command:

```bash
php artisan create:superadmin
```

This command initializes the first Super Admin account.

## 🧪 Testing

Run the automated test suite using:

```bash
php artisan test
```

## 📁 Project Structure

The application follows Laravel's standard project architecture, separating business logic, HTTP controllers, models, database migrations, and configuration.

## 📝 Development Notes

- API versioning is handled through the `/api/v1` prefix.
- Authentication is managed using Laravel Sanctum.
- Access control is based on user roles.
- Database operations are handled through MySQL.
- The API is designed to support the operational needs of the Harmony Spa management system.

---

**Harmony Spa Backend API**  
_Developed with Laravel & PHP._
