<div align="center">

<img src="docs/logo/1771487096721 (1).png" alt="VÉNARO Logo" width="180"/>

# VÉNARO — Premium E-Commerce Platform

**A full-stack, PHP-powered luxury e-commerce platform built for performance, security, and elegance.**

[![CI/CD Pipeline](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![CodeQL](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg?branch=main&event=push)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](./LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)

[📖 Documentation](#documentation) · [🚀 Quick Start](#quick-start) · [🛡️ Security](#security) · [🤝 Contributing](#contributing)

---

</div>

## ✨ Overview

**VÉNARO** is a premium, full-featured e-commerce platform developed with PHP and MySQL. It is designed from the ground up with security, scalability, and a luxury shopping experience in mind. From product discovery to order management, every flow is crafted with care.

---

## 🏗️ Core Features

| Feature | Description |
|---|---|
| 🛍️ **Product Catalog** | Full product listing with filters, categories & collections |
| 🔍 **Smart Search** | Live search suggestions with AJAX |
| 🛒 **Cart & Checkout** | Session-based cart with coupon support |
| 💳 **Order Management** | End-to-end order flow with status tracking |
| 👤 **User Accounts** | Registration, login, wishlist, and profile management |
| 🎟️ **Coupon System** | Percent or flat discount coupons |
| ⭐ **Reviews & Ratings** | Product reviews with star ratings |
| 🔔 **Newsletter** | Email subscription system |
| 🔒 **Admin Panel** | Full-featured dashboard for products, orders, customers |
| 🛡️ **Security-First** | PDO prepared statements, bcrypt passwords, input validation |

---

## 🗂️ Project Structure

```
VENARO-Ecommerce-Website/
├── .github/                # GitHub CI/CD, security, templates
│   ├── workflows/          # GitHub Actions (CI/CD + CodeQL)
│   ├── ISSUE_TEMPLATE/     # Bug report & feature request templates
│   ├── PULL_REQUEST_TEMPLATE.md
│   ├── SECURITY.md
│   └── dependabot.yml
├── admin/                  # Admin panel (dashboard, products, orders)
├── api/                    # AJAX API endpoints
├── assets/                 # CSS, JS, Images
│   ├── css/
│   ├── js/
│   └── images/
├── config.php              # Database & app configuration
├── database/               # SQL schema dump
├── docs/                   # Project documentation & wiki
│   ├── wiki/               # GitHub Wiki content
│   ├── screenshots/        # App screenshots
│   └── logo/               # Brand assets
├── includes/               # Shared PHP partials (header, footer, auth)
├── uploads/                # User-uploaded media (products, categories)
├── .gitignore
├── .htaccess               # Apache URL rewriting & security headers
├── LICENSE
└── README.md
```

---

## 🚀 Quick Start

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any PHP 8.2+ / MySQL 8.0+ stack)
- PHP 8.2+
- MySQL 8.0+

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/vishal-dev1128/VENARO-Ecommerce-Website.git
   cd VENARO-Ecommerce-Website
   ```

2. **Set up the database**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database: `venaro_db`
   - Import `database/venaro_db.sql`

3. **Configure the application**
   - Open `config.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'venaro_db');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Start Apache & MySQL** in XAMPP, then visit:
   ```
   http://localhost/new-venaro/
   ```

### Admin Access

| Field    | Value              |
|----------|--------------------|
| URL      | `/admin/`          |
| Email    | `admin@venaro.com` |
| Password | `admin123`         |

> ⚠️ **Change the admin password** immediately after first login in production.

---

## 📖 Documentation

Comprehensive documentation is available in the [`docs/wiki/`](./docs/wiki/) directory:

| Document | Description |
|---|---|
| [Architecture Overview](./docs/wiki/architecture.md) | System design and folder structure |
| [Feature Guide](./docs/wiki/features.md) | All features in detail |
| [API Reference](./docs/wiki/api.md) | AJAX endpoints |
| [Admin Manual](./docs/wiki/admin.md) | Admin panel guide |
| [Setup Guide](./docs/wiki/setup.md) | Full installation instructions |

---

## 🛡️ Security

Security is a first-class concern in VÉNARO:

- **SQL Injection** prevention via PDO prepared statements
- **Password Security** via PHP `password_hash()` (bcrypt)
- **Session Protection** via `session_regenerate_id()`
- **Input Validation** on all user-facing forms
- **File Upload Restrictions** (JPEG, PNG, WEBP only; max 5MB)
- **HTTPS Enforcement** via `.htaccess`
- **Access Control** — admin routes protected by role checks

To report a security vulnerability, see [SECURITY.md](.github/SECURITY.md).

---

## 🤝 Contributing

We welcome contributions! Please read our [contribution guide](.github/PULL_REQUEST_TEMPLATE.md) before submitting a PR.

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'feat: add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📜 License

Distributed under the MIT License. See [LICENSE](./LICENSE) for details.

---

<div align="center">

Made with ❤️ by **Vishal** | VÉNARO Premium E-Commerce © 2025

</div>
