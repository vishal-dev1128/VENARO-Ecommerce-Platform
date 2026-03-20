<div align="center">

<img src="docs/logo/1771487096721 (1).png" alt="VÉNARO Logo" width="180"/>

# V├ëNARO ΓÇö Premium E-Commerce Platform

**A full-stack, PHP-powered luxury e-commerce platform built for performance, security, and elegance.**

[![CI/CD Pipeline](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![CodeQL](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg?branch=main&event=push)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](./LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)

[≡ƒôû Documentation](#documentation) ┬╖ [≡ƒÜÇ Quick Start](#quick-start) ┬╖ [≡ƒ¢í∩╕Å Security](#security) ┬╖ [≡ƒñ¥ Contributing](#contributing)

---

</div>

## Γ£¿ Overview

**V├ëNARO** is a premium, full-featured e-commerce platform developed with PHP and MySQL. It is designed from the ground up with security, scalability, and a luxury shopping experience in mind. From product discovery to order management, every flow is crafted with care.

---

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
      <img src="docs/screenshots/new arrival page.png" alt="New Arrivals" width="100%"/>
      <br/><sub><b>✨ New Arrivals</b></sub>
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
      <img src="docs/screenshots/user dashboard.png" alt="User Dashboard" width="100%"/>
      <br/><sub><b>👤 User Dashboard</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/login and signup page.png" alt="Login & Signup" width="100%"/>
      <br/><sub><b>🔐 Login & Signup</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <img src="docs/screenshots/admin dashboard.png" alt="Admin Dashboard" width="80%"/>
      <br/><sub><b>⚙️ Admin Dashboard</b></sub>
    </td>
  </tr>
</table>

---

## 🏗️ Core Features

| Feature | Description |
|---|---|
| ≡ƒ¢ì∩╕Å **Product Catalog** | Full product listing with filters, categories & collections |
| ≡ƒöì **Smart Search** | Live search suggestions with AJAX |
| ≡ƒ¢Æ **Cart & Checkout** | Session-based cart with coupon support |
| ≡ƒÆ│ **Order Management** | End-to-end order flow with status tracking |
| ≡ƒæñ **User Accounts** | Registration, login, wishlist, and profile management |
| ≡ƒÄƒ∩╕Å **Coupon System** | Percent or flat discount coupons |
| Γ¡É **Reviews & Ratings** | Product reviews with star ratings |
| ≡ƒöö **Newsletter** | Email subscription system |
| ≡ƒöÆ **Admin Panel** | Full-featured dashboard for products, orders, customers |
| ≡ƒ¢í∩╕Å **Security-First** | PDO prepared statements, bcrypt passwords, input validation |

---

## ≡ƒùé∩╕Å Project Structure

```
VENARO-Ecommerce-Website/
Γö£ΓöÇΓöÇ .github/                # GitHub CI/CD, security, templates
Γöé   Γö£ΓöÇΓöÇ workflows/          # GitHub Actions (CI/CD + CodeQL)
Γöé   Γö£ΓöÇΓöÇ ISSUE_TEMPLATE/     # Bug report & feature request templates
Γöé   Γö£ΓöÇΓöÇ PULL_REQUEST_TEMPLATE.md
Γöé   Γö£ΓöÇΓöÇ SECURITY.md
Γöé   ΓööΓöÇΓöÇ dependabot.yml
Γö£ΓöÇΓöÇ admin/                  # Admin panel (dashboard, products, orders)
Γö£ΓöÇΓöÇ api/                    # AJAX API endpoints
Γö£ΓöÇΓöÇ assets/                 # CSS, JS, Images
Γöé   Γö£ΓöÇΓöÇ css/
Γöé   Γö£ΓöÇΓöÇ js/
Γöé   ΓööΓöÇΓöÇ images/
Γö£ΓöÇΓöÇ config.php              # Database & app configuration
Γö£ΓöÇΓöÇ database/               # SQL schema dump
Γö£ΓöÇΓöÇ docs/                   # Project documentation & wiki
Γöé   Γö£ΓöÇΓöÇ wiki/               # GitHub Wiki content
Γöé   Γö£ΓöÇΓöÇ screenshots/        # App screenshots
Γöé   ΓööΓöÇΓöÇ logo/               # Brand assets
Γö£ΓöÇΓöÇ includes/               # Shared PHP partials (header, footer, auth)
Γö£ΓöÇΓöÇ uploads/                # User-uploaded media (products, categories)
Γö£ΓöÇΓöÇ .gitignore
Γö£ΓöÇΓöÇ .htaccess               # Apache URL rewriting & security headers
Γö£ΓöÇΓöÇ LICENSE
ΓööΓöÇΓöÇ README.md
```

---

## ≡ƒÜÇ Quick Start

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

> ΓÜá∩╕Å **Change the admin password** immediately after first login in production.

---

## ≡ƒôû Documentation

Comprehensive documentation is available in the [`docs/wiki/`](./docs/wiki/) directory:

| Document | Description |
|---|---|
| [Architecture Overview](./docs/wiki/architecture.md) | System design and folder structure |
| [Feature Guide](./docs/wiki/features.md) | All features in detail |
| [API Reference](./docs/wiki/api.md) | AJAX endpoints |
| [Admin Manual](./docs/wiki/admin.md) | Admin panel guide |
| [Setup Guide](./docs/wiki/setup.md) | Full installation instructions |

---

## ≡ƒ¢í∩╕Å Security

Security is a first-class concern in V├ëNARO:

- **SQL Injection** prevention via PDO prepared statements
- **Password Security** via PHP `password_hash()` (bcrypt)
- **Session Protection** via `session_regenerate_id()`
- **Input Validation** on all user-facing forms
- **File Upload Restrictions** (JPEG, PNG, WEBP only; max 5MB)
- **HTTPS Enforcement** via `.htaccess`
- **Access Control** ΓÇö admin routes protected by role checks

To report a security vulnerability, see [SECURITY.md](.github/SECURITY.md).

---

## ≡ƒñ¥ Contributing

We welcome contributions! Please read our [contribution guide](.github/PULL_REQUEST_TEMPLATE.md) before submitting a PR.

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'feat: add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## ≡ƒô£ License

Distributed under the MIT License. See [LICENSE](./LICENSE) for details.

---

<div align="center">

Made with Γ¥ñ∩╕Å by **Vishal** | V├ëNARO Premium E-Commerce ┬⌐ 2025

</div>
