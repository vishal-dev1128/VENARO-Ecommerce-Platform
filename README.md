<div align="center">

<img src="docs/logo/1771487096721 (1).png" alt="VÉNARO Logo" width="160" style="margin-bottom: 16px"/>

<h1>VÉNARO — Premium E-Commerce Platform</h1>

<p><strong>A full-stack, PHP-powered luxury e-commerce platform built for performance, security, and elegance.</strong></p>

[![CI/CD Pipeline](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-gold.svg)](./LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)
[![Security](https://img.shields.io/badge/Security-CodeQL-green?logo=github)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/security)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/pulls)

<br/>

[📖 Documentation](#-documentation) &nbsp;·&nbsp; [🚀 Quick Start](#-quick-start) &nbsp;·&nbsp; [🛡️ Security](#-security) &nbsp;·&nbsp; [🤝 Contributing](#-contributing)

---

</div>

## 📸 Screenshots

<table>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/main page.png" alt="Home Page" width="100%"/>
      <br/><sub><b>🏠 Home Page</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/products page.png" alt="Products Page" width="100%"/>
      <br/><sub><b>🛍️ Products / Shop</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/product detail page.png" alt="Product Detail" width="100%"/>
      <br/><sub><b>🔍 Product Detail</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/category page.png" alt="Category Page" width="100%"/>
      <br/><sub><b>📂 Category Page</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/new arrival page.png" alt="New Arrivals" width="100%"/>
      <br/><sub><b>✨ New Arrivals</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/about page.png" alt="About Page" width="100%"/>
      <br/><sub><b>ℹ️ About Page</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/cart page.png" alt="Cart" width="100%"/>
      <br/><sub><b>🛒 Shopping Cart</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/checkout page.png" alt="Checkout" width="100%"/>
      <br/><sub><b>💳 Checkout</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/order confirm page.png" alt="Order Confirmation" width="100%"/>
      <br/><sub><b>✅ Order Confirmation</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/user dashboard.png" alt="User Dashboard" width="100%"/>
      <br/><sub><b>👤 User Dashboard</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/login and signup page.png" alt="Login & Signup" width="100%"/>
      <br/><sub><b>🔐 Login & Signup</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/contact page.png" alt="Contact Page" width="100%"/>
      <br/><sub><b>📬 Contact Page</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="100%" colspan="2">
      <img src="docs/screenshots/admin dashboard.png" alt="Admin Dashboard" width="80%"/>
      <br/><sub><b>⚙️ Admin Dashboard</b></sub>
    </td>
  </tr>
</table>

---

## ✨ Overview

**VÉNARO** is a premium, full-featured e-commerce platform developed with PHP and MySQL. It is designed from the ground up with security, scalability, and a luxury shopping experience in mind. Every page — from product discovery to order confirmation — is crafted with care.

---

## 🏗️ Core Features

| Feature | Description |
|---|---|
| 🛍️ **Product Catalog** | Full listing with filters, categories & collections |
| 🔍 **Smart Search** | Live AJAX search suggestions |
| 🛒 **Cart & Checkout** | Session-based cart with coupon support |
| 💳 **Order Management** | End-to-end order flow with status tracking |
| 👤 **User Accounts** | Registration, login, wishlist, profile management |
| 🎟️ **Coupon System** | Percent or flat discount coupons |
| ⭐ **Reviews & Ratings** | Verified product reviews with star ratings |
| 🔔 **Newsletter** | Email subscription system |
| 🔒 **Admin Panel** | Full-featured dashboard for products, orders, customers |
| 🛡️ **Security-First** | PDO prepared statements, bcrypt passwords, input validation |

---

## 🗂️ Project Structure

```
VENARO-Ecommerce-Website/
├── .github/                # CI/CD, security policy, templates
│   ├── workflows/          # GitHub Actions (PHP lint + CodeQL)
│   ├── ISSUE_TEMPLATE/     # Bug & feature request templates
│   ├── PULL_REQUEST_TEMPLATE.md
│   ├── SECURITY.md
│   └── dependabot.yml
├── admin/                  # Admin panel (dashboard, products, orders)
├── api/                    # AJAX API endpoints
├── assets/                 # CSS, JS, Images
├── config.php              # Database & app configuration
├── database/               # SQL schema dump
├── docs/
│   ├── logo/               # Brand assets
│   ├── screenshots/        # App screenshots
│   └── wiki/               # Documentation
├── includes/               # Shared PHP partials (header, footer)
├── uploads/                # User-uploaded media
├── .gitignore
├── .htaccess               # URL rewriting & security headers
├── LICENSE
└── README.md
```

---

## 🚀 Quick Start

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (PHP 8.2+ / MySQL 8.0+)
- PHP 8.2+
- MySQL 8.0+

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/vishal-dev1128/VENARO-Ecommerce-Website.git
cd VENARO-Ecommerce-Website
```

**2. Set up the database**
- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Create a database: `venaro_db`
- Import `database/venaro_db.sql`

**3. Configure the application**

Open `config.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'venaro_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

**4. Start XAMPP** (Apache + MySQL), then visit:
```
http://localhost/VENARO-Ecommerce-Website/
```

### Admin Access

| Field    | Value              |
|----------|--------------------|
| URL      | `/admin/`          |
| Email    | `admin@venaro.com` |
| Password | `admin123`         |

> ⚠️ **Change the admin password** immediately in a production environment.

---

## 📖 Documentation

| Document | Description |
|---|---|
| [Architecture Overview](./docs/wiki/architecture.md) | System design and folder structure |
| [API Reference](./docs/wiki/api.md) | AJAX endpoint documentation |
| [Admin Manual](./docs/wiki/admin.md) | Admin panel user guide |

---

## 🛡️ Security

Security is a first-class concern in VÉNARO:

- **SQL Injection** prevention via PDO prepared statements
- **Password Security** via `password_hash()` (bcrypt)
- **Session Protection** via `session_regenerate_id()`
- **Input Validation** on all user-facing forms
- **File Upload Restrictions** — JPEG, PNG, WEBP only; max 5MB
- **HTTPS Enforcement** via `.htaccess`

To report a vulnerability, see [SECURITY.md](.github/SECURITY.md).

---

## 🤝 Contributing

We welcome contributions!

1. Fork the repository
2. Create your branch: `git checkout -b feature/your-feature`
3. Commit: `git commit -m 'feat: add your feature'`
4. Push: `git push origin feature/your-feature`
5. Open a Pull Request

---

## 📜 License

Distributed under the **MIT License**. See [LICENSE](./LICENSE) for details.

---

<div align="center">

Made with ❤️ by **Vishal** &nbsp;|&nbsp; VÉNARO Premium E-Commerce © 2025

</div>
