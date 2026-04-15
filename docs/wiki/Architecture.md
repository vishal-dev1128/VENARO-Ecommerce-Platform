# Architecture Overview

## Tech Stack

| Layer      | Technology         |
|------------|--------------------|
| Frontend   | HTML5, CSS3, Vanilla JS |
| Backend    | PHP 8.2+           |
| Database   | MySQL 8.0+         |
| Server     | Apache (XAMPP)     |

## Folder Structure

```
new-venaro/
├── admin/          ← Admin panel
├── api/            ← AJAX endpoints
├── assets/         ← CSS, JS, Images
├── config.php      ← App configuration
├── database/       ← SQL schema
├── docs/           ← Documentation
├── includes/       ← Shared partials
└── uploads/        ← User media uploads
```

## Request Flow

```
Browser → Apache → .htaccess → PHP Page → config.php → PDO (MySQL)
                                        ↓
                                   includes/ (header, footer, auth)
```

## Database Schema

Key tables:
- `users` — customer accounts
- `products` — product catalog
- `categories` / `collections` — product taxonomy
- `orders` / `order_items` — order management
- `cart` — session cart
- `wishlist` — saved items
- `reviews` — product reviews
- `coupons` — discount codes
- `messages` — contact form submissions
