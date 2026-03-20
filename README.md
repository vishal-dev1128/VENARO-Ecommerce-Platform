<div align="center">

<img src="docs/logo/1771487096721 (1).png" alt="VÉNARO Logo" width="180" style="margin-bottom: 20px"/>

# VÉNARO — Premium E-Commerce Platform

**A full-stack, PHP-powered luxury e-commerce platform built for performance, security, and elegance.**

[![CI/CD Pipeline](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![CodeQL Security](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml/badge.svg?branch=main&event=push)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions/workflows/main.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-gold.svg)](./LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)

[📖 Documentation](#-documentation) &nbsp;·&nbsp; [🚀 Quick Start](#-quick-start) &nbsp;·&nbsp; [🛡️ Security](#-security) &nbsp;·&nbsp; [🤝 Contributing](#-contributing)

---

</div>

## 📸 Project Showcase

<table border="0">
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/main page.png" alt="Home Page" width="100%"/>
      <br/><sub><b>🏠 Elegant Home Page</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/products page.png" alt="Shop Page" width="100%"/>
      <br/><sub><b>🛍️ Premium Product Listing</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/product detail page.png" alt="Product Detail" width="100%"/>
      <br/><sub><b>🔍 Immersive Product Detail</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/category page.png" alt="Category" width="100%"/>
      <br/><sub><b>📂 Category Browsing</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/cart page.png" alt="Cart" width="100%"/>
      <br/><sub><b>🛒 Seamless Shopping Cart</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/checkout page.png" alt="Checkout" width="100%"/>
      <br/><sub><b>💳 Secure Checkout Flow</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/user dashboard.png" alt="User Dashboard" width="100%"/>
      <br/><sub><b>👤 Personal User Dashboard</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/order confirm page.png" alt="Order Success" width="100%"/>
      <br/><sub><b>✅ Order Success Experience</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2" width="100%">
      <img src="docs/screenshots/admin dashboard.png" alt="Admin Dashboard" width="90%"/>
      <br/><sub><b>⚙️ Powerful Administrative Dashboard</b></sub>
    </td>
  </tr>
</table>

---

## ✨ Experience VÉNARO

**VÉNARO** is more than just a template; it's a complete, professional-grade e-commerce engine. Built with a focus on **Visual Excellence** and **Technical Rigor**, it provides a high-end shopping experience out of the box.

### 🏗️ Core Features

- 🛍️ **Intelligent Catalog**: Dynamic product listings with advanced filtering and taxonomy.
- 🔍 **Predictive Search**: Real-time AJAX search suggestions for instant discovery.
- 🛒 **Advanced Cart**: Session-persistent cart with real-time tax/discount calculations.
- 👤 **Customer Lifecycle**: From registration and wishlist to order history and profile management.
- 🔒 **Command Center**: A robust admin panel to manage products, orders, inventory, and analytics.
- 🛡️ **Hardened Security**: PDO prepared statements, bcrypt hashing, and CSRF protection.

---

## 🗂️ Project Architecture

```text
VENARO-Ecommerce-Website/
├── .github/                # Automation & Security
│   ├── workflows/          # CI/CD (PHP Lint + CodeQL)
│   └── templates/          # Issue & PR Templates
├── admin/                  # Business Management Portal
├── api/                    # AJAX JSON Service Layer
├── assets/                 # Frontend Resources (CSS, Vanilla JS)
├── config.php              # Centralized System Configuration
├── database/               # Relational SQL Schema
├── docs/                   # Brand Assets & Screenshots
├── includes/               # Reusable Logic & UI Components
├── uploads/                # Dynamic Media Storage
├── .htaccess               # Performance & Security Headers
├── LICENSE                 # MIT License
└── README.md               # You are here
```

---

## 🚀 Quick Start

### 📋 Prerequisites
- **Web Server**: Apache 2.4+ (XAMPP recommended)
- **Engine**: PHP 8.2+
- **Storage**: MySQL 8.0+

### 🛠️ Installation

1. **Clone & Enter**
   ```bash
   git clone https://github.com/vishal-dev1128/VENARO-Ecommerce-Website.git
   cd VENARO-Ecommerce-Website
   ```

2. **Database Setup**
   - Create a database `venaro_db` in [phpMyAdmin](http://localhost/phpmyadmin).
   - Import the schema from `database/venaro_db.sql`.

3. **Configuration**
   Update `config.php` with your local credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'venaro_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Launch**
   Open your browser and navigate to `http://localhost/VENARO-Ecommerce-Website/`

---

## 🛡️ Security Posture

Security is woven into the fabric of VÉNARO. We follow industry best practices to protect your data and customers:

- **Data Integrity**: Every database interaction is protected by **PDO Prepared Statements**.
- **Password Security**: Credentials are encrypted using industry-standard **Bcrypt** hashing.
- **Session Safety**: Anti-fixation measures and role-based access control (RBAC) are standard.
- **Automated Scanning**: Continuous security analysis via **GitHub CodeQL**.

---

## 📖 Extended Documentation

Dive deeper into our technical documentation and guides:

| Guide | Description |
|---|---|
| [🏗️ Architecture](./docs/wiki/architecture.md) | In-depth look at system design & data flow. |
| [🔌 API Reference](./docs/wiki/api.md) | Technical specs for all AJAX endpoints. |
| [🛠️ Admin Manual](./docs/wiki/admin.md) | Guide for managing your store effectively. |

---

<div align="center">

Made with ❤️ by **Vishal** | VÉNARO Premium E-Commerce © 2025

</div>
