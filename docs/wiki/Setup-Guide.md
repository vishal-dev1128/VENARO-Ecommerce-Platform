# Local Setup Guide ⚙️

Follow these steps to get VÉNARO running on your local machine using XAMPP.

---

## 📋 Prerequisites
- **XAMPP** (Apache, MySQL, PHP 8.1+)
- **Git** (optional, for cloning)

---

## 🛠️ Installation Steps

### 1. Place the Files
Move the project folder to your XAMPP htdocs directory:
`C:\xampp\htdocs\new-venaro\`

### 2. Database Integration
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Create a new database named `venaro_db`.
3. Import the SQL file from `database/venaro_db.sql`.

### 3. Configuration
Open `config.php` and verify your credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'venaro_db');
```

### 4. Directoy Permissions
Ensure the `uploads/` directory is writable by the web server.

### 5. Access
- **Frontend**: `http://localhost/new-venaro/`
- **Admin**: `http://localhost/new-venaro/admin/`

---

## 🔑 Admin Credentials
- **Email**: `admin@venaro.com`
- **Password**: `Admin@123`
