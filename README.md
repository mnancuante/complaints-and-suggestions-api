# Complaints and Suggestions API

Backend practice project focused on core API design concepts using PHP Vanilla and MySQL.

This project was built to revisit and reinforce backend fundamentals without using frameworks, applying concepts such as layered architecture, CRUD operations, validation, custom routing, HTTP responses, and soft delete logic.

---

## Features

- CRUD operations for complaints and suggestions
- Layered architecture
- Custom router implementation
- Request validation and data normalization
- HTTP status code handling
- JSON responses
- PDO prepared statements
- Soft delete support using `deleted_at` flag
- Input validation for IDs and request body data
- Separation of concerns between Controllers, Services, Repositories and Validators
- Apache URL rewriting with .htaccess
- Clean API endpoints
- RESTful route parameters (`/complaints/1`)
- Composer PSR-4 autoloading
- JWT Authentication
- PATCH support

---

## Requirements

- PHP
- MySQL / MariaDB
- Composer
- Apache
- Recomended: XAMPP (or similar local environment)

---

## Project Structure

```text
complaints-and-suggestions-api/
│
├── api/
├── config/
├── public/
├── src/
│   ├── Controllers/
│   ├── Database/
│   │   └──migrations/
│   ├── Exceptions/
│   ├── Services/
│   ├── Repository/
│   ├── Validator/
│   ├── Model/
│   ├── Middleware/
│   └── Http/
│
├── vendor/
├── .gitignore
├── composer.json
└── README.md
```

The `/vendor` directory is ignored in Git and must be installed using Composer.

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/mnancuante/complaints-and-suggestions-api.git
```

---

### 2. Install dependencies

```bash
composer install
```

---

### 3. Move the project into your web server directory

Example using XAMPP:

For iOS:
```text
/Applications/XAMPP/xamppfiles/htdocs
```

For Windows:
```text
C:\xampp\htdocs
```

---

### 4. Database setup

- Create a `config.php` file inside the `config/` directory based on `config.example.php`.

Example:

```php
return [
    'db' => [
        'host' => 'localhost',
        'user' => 'your_user',
        'password' => 'your_password',
        'dbname' => 'your_database',
        'port' => 3306
    ]
];
```

- Run the migrations:

```bash
php scripts/migrate.php
```

- (Optional) Load sample data inside src/Database/seed.sql

- Database schema changes are versioned through SQL migrations located in:

```text
database/migrations/
```

---

### 5. Start Apache and MySQL

Start both services from the XAMPP control panel.
Make sure Apache `mod_rewrite` is enabled.

---

## API Endpoints

### Get all complaints

```http
GET /api/complaints
```

---

### Get complaint by ID

```http
GET /api/complaints/1
```

---

### Create complaint

```http
POST /api/complaints
```
JSON body is required. Example body:

```json
{
  "title": "Late delivery",
  "description": "The order arrived two days late",
  "status": "open"
}
```

---

### Update complaint

```http
PUT /api/complaints/1
```

ID is required. The complaint ID must be provided in the URL only.
Sending an `id` field in the request body is not allowed.
Example body:

```json
{
  "title": "Updated title",
  "description": "Updated description",
  "status": "in_progress"
}
```

---

```http
PATCH /api/complaints/1
```

ID is required.
At least 1 field is required.
Example body:

```json
{
  "description": "Updated description"
}
```

---

### Delete complaint (Soft Delete)

```http
DELETE /api/complaints/1
```

ID is required.

---

## Architecture

This project follows a simple layered architecture approach:

- Controllers → Handle HTTP requests and responses
- Services → Handle business logic
- Repositories → Handle database queries
- Validators → Handle input validation
- Models → Domain-related classes and enums

---

## Future Improvements

- Docker support
- Pagination
- Unit testing
- Environment variables using `.env`
- Roles (admin, user)

---

## Purpose of the Project

The main goal of this project is educational and portfolio-oriented: revisiting backend fundamentals, reinforcing architecture concepts, and practicing clean code organization without relying on frameworks.