# VÉNARO — Setup Instructions

**Version:** 2.0 &nbsp;|&nbsp; **Date:** February 2026

---

## Step 1: Start XAMPP

1. Open **XAMPP Control Panel**
2. Start **Apache** (click Start)
3. Start **MySQL** (click Start)
4. Both should show green **"Running"** status

---

## Step 2: Import the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **"Import"** tab at the top
3. Click **"Choose File"**
4. Navigate to: `c:\xampp\htdocs\new-venaro\database\venaro_db.sql`
5. Click **"Go"** at the bottom
6. Wait for the **"Import has been successfully finished"** message

### Verify
- In phpMyAdmin left sidebar, you should see **`venaro_db`**
- Expand it — you should see **23 tables** (users, products, orders, categories, etc.)

---

## Step 3: Configure Database Credentials

Open `c:\xampp\htdocs\new-venaro\config.php` and confirm:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // XAMPP default — no password
define('DB_NAME', 'venaro_db');
```

---

## Step 4: Create Upload Directories

Create these folders if they don't already exist:

```
new-venaro/uploads/
├── products/
├── categories/
├── collections/
└── profiles/
```

Ensure the `uploads/` folder is **writable** by Apache (right-click → Properties → Security on Windows, or `chmod 775 uploads/` on Linux/Mac).

---

## Step 5: Open the Website

| Access | URL |
| :--- | :--- |
| **Frontend (Homepage)** | `http://localhost/new-venaro/` |
| **Admin Panel** | `http://localhost/new-venaro/admin/` |

---

## Default Login Credentials

### Admin Panel
| Field | Value |
| :--- | :--- |
| Email | `admin@venaro.com` |
| Password | `Admin@123` |

> ⚠️ Change this password immediately after first login via **Admin → Settings**.

### Customer Account
Register a new user at: `http://localhost/new-venaro/register.php`

---

## Admin Panel — Quick Guide

Once logged in at `http://localhost/new-venaro/admin/`:

| Section | What You Can Do |
| :--- | :--- |
| **Dashboard** | View KPI cards — Products, Orders, Customers, Revenue |
| **Products** | Add / Edit / Delete products with variants, images, SEO |
| **Categories** | Manage hierarchical categories (parent/child) |
| **Collections** | Create and feature collections |
| **Orders** | Update order status, add tracking numbers |
| **Customers** | View customer list, block/unblock accounts |
| **Coupons** | Create discount codes (%, flat, free shipping) |
| **Reviews** | Approve or reject product reviews |
| **Messages** | Read contact form submissions |
| **Settings** | Configure SMTP, payment keys, maintenance mode |

---

## Common Issues & Fixes

### ❌ "Database connection failed"
**Fix:** Make sure **MySQL** is running in XAMPP Control Panel.

### ❌ "Unknown database 'venaro_db'"
**Fix:** Import the database schema (see Step 2).

### ❌ "Access denied for user 'root'"
**Fix:** In `config.php`, confirm:
- `DB_HOST` = `localhost`
- `DB_USER` = `root`
- `DB_PASS` = `` (empty)
- `DB_NAME` = `venaro_db`

### ❌ Images not showing
**Fix:** Create the upload directories listed in Step 4 and ensure they are writable.

### ❌ "Page Not Found" on any page
**Fix:** Make sure **Apache mod_rewrite** is enabled in XAMPP and the project is inside `C:\xampp\htdocs\new-venaro\`.

### ❌ Admin login not working
**Fix:** Run the database diagnostic at `http://localhost/new-venaro/db-test.php` to verify the `admin_users` table exists and is populated.

---

## Diagnostic Tool

If you're still having issues, run:

```
http://localhost/new-venaro/db-test.php
```

This checks your database connection and shows specific errors with fix instructions.

---

## After Setup — First Steps

1. **Log into Admin Panel** at `http://localhost/new-venaro/admin/`
2. **Add Categories** — T-Shirts, Sweatshirts, Hoodies, Sweatpants, Varsity Jackets
3. **Add Products** — Use the product editor with variants (size/color), images, and SEO fields
4. **Browse Frontend** — Visit `http://localhost/new-venaro/` as a customer
5. **Review Product Requirements** — Check out `PRD.md` or `PRD.txt` for the full feature list and project scope.

---

*VÉNARO — Redefining Modern Fashion*
