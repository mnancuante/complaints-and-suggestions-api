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

---

## Technologies

- PHP Vanilla
- MySQL / MariaDB
- PDO
- Apache
- XAMPP

---

## Project Structure

```text
complaints-and-suggestions-api/
│
├── api/
│   └── index.php
│
├── config/
│   ├── config.example.php
│   └── config.php
│
├── database/
│   ├── Database.php
│   ├──sql/
│   └── db.sql
│
├── src/
│   ├── Controllers/
│   ├── Services/
│   ├── Repositories/
│   ├── Validators/
│   ├── Models/
│   └── Http/
│
├── .gitignore
└── README.md
```
---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/mnancuante/complaints-and-suggestions-api.git
```

---

### 2. Move the project into your web server directory

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

### 3. Create the database

Import the SQL files located in the `sql/` directory using:

- phpMyAdmin
- SequelAce
- DBeaver
- or any SQL client

---

### 4. Configure database credentials

Create a `config.php` file inside the `config/` directory based on `config.example.php`.

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

---

### 5. Start Apache and MySQL

Start both services from the XAMPP control panel.

---

## API Endpoints

### Get all complaints

```http
GET /api/index.php/complaints
```

---

### Get complaint by ID

```http
GET /api/index.php/complaints?id=1
```

---

### Create complaint

```http
POST /api/index.php/complaints
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
PUT /api/index.php/complaints
```

ID is required. Example body:

```json
{
  "id": 1,
  "title": "Updated title",
  "description": "Updated description",
  "status": "in_progress"
}
```

---

### Delete complaint (Soft Delete)

```http
DELETE /api/index.php/complaints?id=1
```
ID is required

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

- JWT Authentication
- Composer autoloading
- Docker support
- Pagination
- PATCH support
- Unit testing
- Environment variables using `.env`
- RESTful route parameters (`/complaints/1`)

---

## Purpose of the Project

The main goal of this project is educational and portfolio-oriented: revisiting backend fundamentals, reinforcing architecture concepts, and practicing clean code organization without relying on frameworks.