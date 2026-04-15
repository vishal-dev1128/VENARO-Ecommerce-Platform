# Security Policy

## Supported Versions

Only the latest version of VÉNARO is actively supported with security updates.

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | ✅ Active support  |
| < 1.0   | ❌ Not supported   |

---

## Reporting a Vulnerability

We take security seriously at VÉNARO. If you discover a security vulnerability, **please do not open a public GitHub Issue.**

### How to Report

1. **Email**: Send details of the vulnerability to the repository owner via GitHub's private security advisory feature.
2. Go to **Security → Advisories → New Draft Security Advisory** in this repository.
3. Provide a clear description including:
   - The type of vulnerability (e.g., SQL Injection, XSS, CSRF)
   - Steps to reproduce
   - Potential impact
   - Any suggested fix (optional)

### Response Timeline

| Stage                    | Expected Time  |
|--------------------------|----------------|
| Acknowledgment           | Within 48 hours |
| Initial assessment       | Within 5 days  |
| Fix or patch release     | Within 14 days |

---

## Security Best Practices in This Project

- All database queries use **prepared statements** (PDO).
- User passwords are hashed with **`password_hash()`** (bcrypt).
- Sessions use **`session_regenerate_id()`** to prevent fixation attacks.
- All user inputs are validated and sanitized.
- File uploads are restricted to safe MIME types (JPG, PNG, WEBP).
- `.htaccess` enforces **HTTPS redirection** and denies direct access to sensitive directories.

---

## Responsible Disclosure

We appreciate responsible disclosure. Reporters who responsibly identify and submit security issues will be acknowledged in our release notes (with their permission).
