# Admin Panel Guide

## Access

| Field    | Value              |
|----------|--------------------|
| URL      | `/admin/`          |
| Email    | `admin@venaro.com` |
| Password | `admin123`         |

> ⚠️ **Change the default password** immediately in production.

---

## Dashboard

The admin dashboard (`/admin/dashboard.php`) displays:
- Total revenue
- Order counts by status
- Recent orders
- New customer registrations
- Product inventory stats

---

## Products

- **Add Product** — `/admin/product-add.php`
  - Upload up to 5 images (JPG, PNG, WEBP)
  - Assign category and collection
  - Set price, sale price, stock, and tags

- **Edit Product** — `/admin/product-edit.php`
- **Product Listing** — `/admin/products.php`

---

## Orders

- **Order List** — `/admin/orders.php`
- **Order Detail** — `/admin/order-detail.php`
  - Update order status (Pending → Processing → Shipped → Delivered → Cancelled)

---

## Categories & Collections

- **Categories** — `/admin/categories.php`
- **Collections** — `/admin/collections.php`
- **Category Order** — `/admin/category-order.php` (drag-and-drop reorder)

---

## Customers

- `/admin/customers.php` — View all registered users
- View order history per customer

---

## Coupons

- `/admin/coupons.php`
- Create percent or flat discount coupons
- Set expiry dates and usage limits

---

## Reviews

- `/admin/reviews.php`
- Approve, moderate, or delete product reviews

---

## Messages

- `/admin/messages.php`
- View contact form submissions

---

## Settings

- `/admin/settings.php`
- Update store name, admin email, phone, address

---

## Security

- Admin routes check role via session (`$_SESSION['user_role'] === 'admin'`)
- Session timer auto-logout after inactivity
- Password reset available at `/admin/reset_password.php`
