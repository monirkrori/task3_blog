# Laravel Blog API

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![API](https://img.shields.io/badge/Type-API-only-green.svg)

A robust Laravel-based blog API with authentication, post scheduling, and CLI management.

## Features

- 🔐 **Sanctum Authentication** - Secure API token-based authentication
- ⏱️ **Post Scheduling** - Schedule posts for future publication
- 🤖 **Auto-Publishing** - Automated system for scheduled posts
- ✏️ **CRUD Operations** - Create, read, update, and delete posts
- 🛡️ **Custom Validation** - Tailored validation rules for content
- 💻 **CLI Interface** - Manage posts via Artisan commands
- 📅 **Scheduled Commands** - Built-in task scheduling

## Requirements

- PHP 8.2+
- Laravel 12
- Composer
- Database (MySQL/PostgreSQL/SQLite)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/monirkrori/task3_blog.git
   cd task3_blog
   ```
2. Install dependencies:
    ```bash 
    composer install 
    ```
3. Configure environment:
    ```bash 
   cp .env.example .env
   php artisan key:generate
   ```
4. Install Api 
   ```bash
   php artisan api:install
   ```
## API Documentation

### Authentication Endpoints

| Endpoint             | Method | Description                | Authentication Required |
|----------------------|--------|----------------------------|-------------------------|
| `/api/auth/register` | POST   | Register new user          | No                      |
| `/api/auth/login`    | POST   | Login user                 | No                      |
| `/api/auth/logout`   | POST   | Logout user                | Yes                     |

**Request Body for Register:**
```json
{
    "name": "user"
    "email": "user@example.com",
    "password": "yourpassword"
}
```
### posts

| Endpoint          | Method | Description                | Authentication Required |
|-------------------|--------|----------------------------|-------------------------|
| `/api/posts/  `   | Get    | Get all posts              | No                      |
| `/api/posts`      | POST   | Create post                | Yes                     |
| `/api/posts/{id}` | Get    | Get specific post          | No                      |
| `api/posts/{id}   | Put    | Update specific post       | Yes                     |
| `api/posts/{id}   | Delete | Delete specific post       | Yes                     |

**Request Body for create post :
```json
{
    
    "title": "My First Larvel Post",
    "body": "This is a detailed post about Laravel features and best practices. It contains more than 100 characters to meet validation requirements.",
    "publish_date": "2025-5-05 12:46 pm",
    "tags": ["laravel", "backend", "php"],
    "keywords": ["framework", "web", "development"]

}
```

## Post Scheduling System
1.Include publish_at parameter with future datetime when creating/updating posts
2.The system automatically publishes posts at the scheduled time

# Publish scheduled posts manually
```bash
php artisan posts:publish-scheduled
```

# List all scheduled posts
```bash
php artisan posts:list-scheduled
```

# Run the scheduler (for automatic publishing)
```bash
php artisan schedule:work
```

## Postman Collection

https://www.postman.com/payload-cosmologist-54490583/workspace/my-workspace/collection/38817975-882dd7b6-3fd5-4bf8-bdba-a2f6f2fbe467?action=share&creator=38817975
