# POS System (TFA3): Making It Editable – Forms, Validation, and File Upload

A Point-of-Sale (POS) website built with CodeIgniter 4. This version extends TFA2 so records can be created and edited through validated forms, and user accounts can have a profile picture (avatar) uploaded, resized into a thumbnail, and displayed on the listing page.

## Course Information

- **Course:** IT0049 – Web System Technologies
- **Activity:** Technical Formative Assessment 3 – Making It Editable: Forms, Validation, and File Upload
- **Student:** Kyle Rianne Andrei Dionio (individual submission)
- **Section:** TC33
- **Professor:** Von Erick Magbitang
- **GitHub:** [ladiesman416/pos-system-tfa3](https://github.com/ladiesman416/pos-system-tfa3)
- **Previous activities:** [TFA1](https://github.com/ladiesman416/pos-system-tfa1) · [TFA2](https://github.com/ladiesman416/pos-system-tfa2)
- **Live Demo:** http://pos-kyle.gt.tc/tfa3/

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Pages and Routes](#pages-and-routes)
- [Validation Rules](#validation-rules)
- [Avatar Upload](#avatar-upload)
- [Database](#database)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Setup and Run](#setup-and-run)

## Overview

TFA2 only displayed data. TFA3 adds the other half of a useful application: creating and editing records, rejecting bad input with clear messages, and handling a file upload safely. Controllers validate every request with CodeIgniter's validation library before the Model touches the database.

## Features

- Create and edit forms for **customers** and **users**
- Server-side validation with per-field error messages
- Forms redisplay with the user's previously entered values after a failed submit
- Edit forms are pre-filled with the existing record
- Avatar upload on the user edit form, limited to JPG or PNG files up to 2MB
- Uploaded image is resized to a 150 x 150 thumbnail with CodeIgniter's Image service
- Only the filename is saved in the `avatar` column
- Users list shows each avatar, with a placeholder image when none was uploaded
- Success messages after saving (flash data)
- Output escaped with `esc()` and forms protected with `csrf_field()`

## Pages and Routes

| Method | URL                        | Route Target        | Description                            |
|--------|----------------------------|---------------------|----------------------------------------|
| GET    | `/`                        | `Pages::index`      | Landing page                           |
| GET    | `/about`                   | `Pages::about`      | About page                             |
| GET    | `/customers`               | `Customers::index`  | Customer list                          |
| GET    | `/customers/new`           | `Customers::new`    | New customer form                      |
| POST   | `/customers/create`        | `Customers::create` | Validate and insert a customer         |
| GET    | `/customers/edit/(:num)`   | `Customers::edit`   | Pre-filled edit form                   |
| POST   | `/customers/update/(:num)` | `Customers::update` | Validate and update a customer         |
| GET    | `/users`                   | `Users::index`      | User list with avatars                 |
| GET    | `/users/new`               | `Users::new`        | New user form                          |
| POST   | `/users/create`            | `Users::create`     | Validate and insert a user             |
| GET    | `/users/edit/(:num)`       | `Users::edit`       | Pre-filled edit form with avatar input |
| POST   | `/users/update/(:num)`     | `Users::update`     | Validate, upload avatar, update user   |

## Validation Rules

**Customers**

| Field       | Rules                               |
|-------------|-------------------------------------|
| `full_name` | required, min 2, max 100 characters |
| `email`     | required, valid email, max 100      |
| `phone`     | optional, max 20 characters         |

**Users**

| Field       | Rules                                                              |
|-------------|--------------------------------------------------------------------|
| `username`  | required, min 3, max 50, unique (ignores the current user on edit) |
| `full_name` | required, min 2, max 100 characters                                |
| `avatar`    | optional; must be a valid image, JPG or PNG, 2MB or smaller        |

## Avatar Upload

1. The file is validated by `is_image`, `mime_in`, `ext_in`, and `max_size[avatar,2048]`.
2. The original is moved to `writable/uploads/avatars/` (outside the public web root) under a random name.
3. A 150 x 150 thumbnail is created from it and saved in `public/uploads/avatars/` using `service('image')`.
4. Only the generated filename is saved in `users.avatar`.
5. When an avatar is replaced, the old files are deleted.
6. The listing falls back to `public/images/placeholder.svg` when `avatar` is empty.

## Database

Database name: `pos_tfa3`

**customers**

| Column       | Type         | Notes                       |
|--------------|--------------|-----------------------------|
| `id`         | INT          | Primary key, auto-increment |
| `full_name`  | VARCHAR(100) | Required                    |
| `email`      | VARCHAR(100) | Required                    |
| `phone`      | VARCHAR(20)  | Optional                    |
| `created_at` | DATETIME     | Required                    |

**users**

| Column       | Type         | Notes                              |
|--------------|--------------|------------------------------------|
| `id`         | INT          | Primary key, auto-increment        |
| `username`   | VARCHAR(50)  | Required, unique                   |
| `full_name`  | VARCHAR(100) | Required                           |
| `avatar`     | VARCHAR(255) | Optional, stores the filename only |
| `created_at` | DATETIME     | Required                           |

The full export is in [`database/pos_tfa3.sql`](database/pos_tfa3.sql).

## Tech Stack

- PHP 8.1 or higher (with the GD extension enabled for image processing)
- CodeIgniter 4 (installed through Composer)
- MySQL (via XAMPP / phpMyAdmin)
- HTML / CSS for the views
- Composer for dependency management

## Project Structure

Only the files relevant to this activity are listed:

```text
pos-system-tfa3/
├── app/
│   ├── Config/
│   │   └── Routes.php            # Route definitions
│   ├── Controllers/
│   │   ├── Pages.php
│   │   ├── Customers.php         # List, create, edit, update + validation
│   │   └── Users.php             # List, create, edit, update + avatar upload
│   ├── Models/
│   │   ├── CustomerModel.php
│   │   └── UserModel.php         # Includes the avatar field
│   └── Views/
│       ├── layout.php
│       ├── customers/
│       │   ├── index.php         # Customer list with Edit links
│       │   └── form.php          # Shared create/edit form
│       └── users/
│           ├── index.php         # User list with avatars
│           └── form.php          # Shared create/edit form with file input
├── database/
│   └── pos_tfa3.sql              # Database export (schema + sample data)
├── public/
│   ├── css/style.css
│   ├── images/placeholder.svg    # Fallback avatar
│   ├── uploads/avatars/          # Generated thumbnails
│   └── index.php
├── writable/uploads/avatars/     # Original uploaded files (not public)
├── env                           # Environment template (copy to .env)
├── composer.json
└── README.md
```

## Setup and Run

1. Clone the repository and install dependencies:
   ```
   git clone https://github.com/ladiesman416/pos-system-tfa3.git
   cd pos-system-tfa3
   composer install
   ```
2. Copy the environment template: `copy env .env` (Windows) or `cp env .env`.
3. Make sure PHP's GD extension is enabled (`php -m` should list `gd`).
4. Start **Apache** and **MySQL** (for example in the XAMPP Control Panel).
5. Open phpMyAdmin (`http://localhost/phpmyadmin`), create a database named `pos_tfa3`, then use the **Import** tab to import `database/pos_tfa3.sql`.
6. In `.env`, set the database values (remove any `#` at the start of these lines):
   ```
   database.default.hostname = localhost
   database.default.database = pos_tfa3
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```
7. Run the development server:
   ```
   php spark serve --port 8081
   ```
8. Open `http://localhost:8081` and try `/customers/new`, `/users/new`, and the **Edit** links.
