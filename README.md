<div align="center">

<img src="docs/logo/1771487096721 (1).png" alt="VÉNARO Logo" width="220" style="margin-bottom: 20px"/>

# 💎 VÉNARO — The Ultimate Premium E-Commerce Platform

**A masterclass in full-stack engineering. Built with PHP 8.2 & MySQL 8.0 for the modern luxury market.**

[![Version](https://img.shields.io/badge/Version-1.0.0--stable-gold.svg?style=for-the-badge)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website)
[![PHP](https://img.shields.io/badge/PHP-8.2+-8892BF?logo=php&logoColor=white&style=for-the-badge)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql&logoColor=white&style=for-the-badge)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](./LICENSE)

[![CI/CD Pipeline](https://img.shields.io/github/actions/workflow/status/vishal-dev1128/VENARO-Ecommerce-Website/main.yml?branch=main&label=CI%2FCD&logo=github&style=flat-square)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/actions)
[![Security Scan](https://img.shields.io/github/actions/workflow/status/vishal-dev1128/VENARO-Ecommerce-Website/main.yml?branch=main&label=Security%20(CodeQL)&logo=github-actions&style=flat-square)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/security)
[![Stars](https://img.shields.io/github/stars/vishal-dev1128/VENARO-Ecommerce-Website?style=flat-square)](https://github.com/vishal-dev1128/VENARO-Ecommerce-Website/stargazers)

<br/>

[🚀 Quick Start](#-quick-start) &nbsp;·&nbsp; [📸 Gallery](#-visual-showcase) &nbsp;·&nbsp; [🛠️ Features](#-feature-ecosystem) &nbsp;·&nbsp; [🛡️ Security](#-security-architecture) &nbsp;·&nbsp; [📖 Docs](#-documentation)

---

</div>

## 🚀 Quick Start

### 📋 Prerequisites
Ensure your local environment meets these requirements:
- **Web Server**: Apache 2.4.x (XAMPP 8.2+ recommended)
- **Language**: PHP 8.2.0 or higher
- **Database**: MySQL/MariaDB 8.0.x
- **Extensions**: `pdo_mysql`, `mbstring`, `gd` (for image processing)

### 🛠️ One-Minute Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/vishal-dev1128/VENARO-Ecommerce-Website.git
   cd VENARO-Ecommerce-Website
   ```

2. **Initialize Database**
   - Head over to **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a fresh database named `venaro_db`.
   - **Import** the schema located at `database/venaro_db.sql`.

3. **Configure Environment**
   Open `config.php` and map your local MySQL credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'venaro_db');
   define('DB_USER', 'your_username'); // Usually 'root' on XAMPP
   define('DB_PASS', 'your_password'); // Usually '' on XAMPP
   ```

4. **Experience the Magic**
   Fire up Apache & MySQL in your XAMPP Control Panel and visit:
   `http://localhost/VENARO-Ecommerce-Website/`

---

## 📸 Visual Showcase

<table border="0">
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/main page.png" alt="Home Page" width="100%"/>
      <br/><sub><b>🏠 The Grand Entrance (Home)</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/products page.png" alt="Shop Page" width="100%"/>
      <br/><sub><b>🛍️ Curated Collections (Shop)</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/product detail page.png" alt="Product Detail" width="100%"/>
      <br/><sub><b>🔍 Immersive Discovery (Detail)</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/admin dashboard.png" alt="Admin Dashboard" width="100%"/>
      <br/><sub><b>⚙️ Command Center (Admin)</b></sub>
    </td>
  </tr>
  <tr>
    <td align="center" width="50%">
      <img src="docs/screenshots/cart page.png" alt="Cart" width="100%"/>
      <br/><sub><b>🛒 Intuitive Shopping Cart</b></sub>
    </td>
    <td align="center" width="50%">
      <img src="docs/screenshots/user dashboard.png" alt="User Dashboard" width="100%"/>
      <br/><sub><b>👤 Personal Style Profile</b></sub>
    </td>
  </tr>
</table>

---

## ✨ Experience VÉNARO

**VÉNARO** isn't just an e-commerce platform; it's a complete ecosystem designed for the high-end boutique experience. By combining a **minimalist luxury aesthetic** with a **robust technical backbone**, VÉNARO sets a new standard for PHP-based commerce.

### 🌟 Key Highlights
- **Zero-Latency Search**: Instant search suggestions powered by AJAX for a fluid discovery flow.
- **Dynamic Inventory**: Real-time stock alerts and management in the admin panel.
- **Verified Reviews**: Star ratings and feedback system for social proof.
- **Conversion-Optimized Checkout**: A streamlined, secure checkout process to maximize sales.
- **Responsive Mastery**: Flawless experience across Desktop, Tablet, and Mobile.

---

## 🛠️ Feature Ecosystem

VÉNARO provides a dual-interface experience tailored for both the sophisticated shopper and the efficient store owner.

### 🛍️ For the Customer
| Feature | Details |
|---|---|
| **Luxury Catalog** | High-resolution image galleries and immersive product descriptions. |
| **Smart Wishlist** | Save favorites for later with seamless session persistence. |
| **Coupon Engine** | Real-time application of percentage or flat-rate discounts at checkout. |
| **Order Tracking** | End-to-end transparency with real-time status updates on orders. |
| **Secure Profile** | Manage addresses, password changes, and personal preferences easily. |

### 🔒 For the Administrator
| Module | Capability |
|---|---|
| **Product Forge** | Upload unlimited products, manage bulk stock, and set promotional prices. |
| **Order Control** | Manage the full fulfillment lifecycle from 'Pending' to 'Delivered'. |
| **Customer Insights** | View registered users, their order history, and shopping patterns. |
| **Promotion Manager** | Generate unique coupon codes with specific limits and expiry dates. |
| **Store Settings** | Globally update contact info, branding, and operational parameters. |

---

## 🛡️ Security Architecture

VÉNARO is built with a "Security First" philosophy. Every line of code is written to protect sensitive data and ensure a trusted environment.

- **🛡️ SQLi Protection**: 100% of database interactions are handled via **PDO Prepared Statements**. No exceptions.
- **🔐 Credential Safety**: Industry-standard **Bcrypt (Blowfish)** hashing for all user and admin passwords.
- **🕯️ Session Hardening**: Regenerated session IDs and secure flags prevent session hijacking and fixation.
- **🧹 Data Sanitization**: All incoming data (POST/GET) is rigorously filtered and escaped before processing.
- **🛑 Access Guards**: Middleware-style role checks ensure non-admins are physically unable to access management routes.
- **🤖 Automated Oversight**: Continuous security analysis via **GitHub CodeQL** to catch vulnerabilities early.

---

## 🗂️ Project Structure

```text
VENARO-Ecommerce-Website/
├── .github/                # Automation, CI/CD & Security Ops
│   ├── workflows/          # GitHub Actions (Linting + Security Scans)
│   └── ISSUE_TEMPLATE/     # Professional bug & feature reporting
├── admin/                  # Administrative Command Center (Restricted)
│   ├── includes/           # Admin-specific partials
│   └── assets/             # Admin dashboard UI resources
├── api/                    # AJAX JSON Endpoint Layer (Performance Focused)
├── assets/                 # Universal Frontend Resources
│   ├── css/                # Custom Premium Styling
│   └── js/                 # Modular Vanilla JavaScript
├── config.php              # Centralized Database & System Constants
├── database/               # SQL Relational Schema and Initial Data
├── docs/                   # Brand Assets, Screenshots & Wiki
├── includes/               # Reusable Logic (Auth, Header, Footer)
├── uploads/                # Dynamic Media Storage for Products & UI
├── .htaccess               # Apache Redirection & Security Headers
├── LICENSE                 # Public MIT Licensing
└── README.md               # Extensive Project Documentation
```

---

## 📖 Technical Documentation

For developers looking to extend VÉNARO, please refer to our internal wiki:

- **[🏗️ System Architecture](./docs/wiki/architecture.md)**: Deep dive into the data model and structural patterns.
- **[🔌 API Documentation](./docs/wiki/api.md)**: Comprehensive guide to our AJAX-based service layer.
- **[⚙️ Admin Operations Guide](./docs/wiki/admin.md)**: Tutorial on managing the e-commerce lifecycle.

---

## 🤝 Contributing & Community

VÉNARO is an open-source project, and we welcome collaboration from the global engineering community!

1. **Fork** the repository and create your feature branch.
2. Ensure your code follows the established **Modular PHP Style**.
3. Submit a **Pull Request** referencing the issue you've addressed.

---

## 📜 License & Credits

- **License**: Distributed under the [MIT License](./LICENSE). Feel free to use and modify for your projects!
- **Author**: Created with passion by **Vishal**.
- **Special Thanks**: Built with support from modern web standards and the vibrant PHP community.

<div align="center">

**VÉNARO — Define Your Luxury.**

&copy; 2025 VÉNARO Premium E-Commerce. All Rights Reserved.

</div>
