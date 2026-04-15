# Chapati Sales Management System

A lightweight PHP + MySQL application for tracking daily chapati orders, calculating totals automatically, and reviewing monthly performance.

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [Business Rules](#business-rules)
- [Project Structure](#project-structure)
- [Security Practices](#security-practices)
- [Troubleshooting](#troubleshooting)
- [Roadmap](#roadmap)

## Overview

This system helps you maintain a clean daily sales log with consistent pricing rules.
It supports account-based access, order status handling, and monthly summaries in a simple, responsive interface.

### Screenshots

![Main Preview](preview.png)

## Key Features

### Authentication

- User registration
- Secure login/logout flow
- Password hashing for stored credentials

### Sales Tracking

- Create daily chapati entries
- Auto-calculate total amount
- Edit and delete entries
- Automatic status handling for no-order days

### Reporting

- Monthly filtering
- Dashboard-level monthly summary
- Date-wise table display

### Usability

- Mobile-friendly responsive layout
- Clear validation and error messages

## Tech Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP (PDO)
- Database: MySQL
- Local Development: XAMPP

## Prerequisites

- PHP 8.0 or above
- MySQL 5.7+ (or MariaDB equivalent)
- XAMPP (Apache + MySQL)

## Quick Start

1. Place the project folder inside XAMPP htdocs.
2. Start Apache and MySQL from the XAMPP control panel.
3. Create database objects by importing database/schema.sql in phpMyAdmin.
4. Open the app at:

```text
http://localhost/new/register.php
```

5. Register a new user account and then login.

## Configuration

Database connection is configured in config/database.php.

Default values:

- Host: 127.0.0.1
- Port: 3306
- Database: chapati_sales_db
- User: root
- Password: (empty)

Supported environment variable overrides:

- DB_HOST
- DB_PORT
- DB_NAME
- DB_USER
- DB_PASS

Application constants are defined in config/constants.php:

- APP_NAME = Chapati Sales Management System
- CHAPATI_RATE = 12
- DEFAULT_TIMEZONE = Asia/Kolkata

## Business Rules

- Chapati rate is fixed at 12.
- If quantity is empty:
  - status = No Order
  - total = 0
- If quantity is provided:
  - status = Completed
  - total = quantity * 12

## Project Structure

```text
new/
|- index.php
|- register.php
|- login.php
|- logout.php
|- save_entry.php
|- edit_entry.php
|- delete_entry.php
|- assets/
|  |- css/style.css
|  |- js/app.js
|- config/
|  |- constants.php
|  |- database.php
|- database/
|  |- schema.sql
|- includes/
|  |- auth.php
|  |- functions.php
|  |- header.php
|  |- footer.php
```

## Security Practices

- Uses PDO prepared statements to reduce SQL injection risk.
- Stores user passwords as hashes (not plain text).
- Uses session-based authentication for protected pages.

## Troubleshooting

- Database connection failed:
  - Verify MySQL is running.
  - Check database name and credentials in config/database.php.
  - Confirm the schema import completed successfully.
- Login fails after registration:
  - Confirm you are using the same username and password created at registration.

## Roadmap

- Customer-wise sales tracking
- Expense and profit modules
- Export to Excel/PDF
- Multi-user scoped analytics
