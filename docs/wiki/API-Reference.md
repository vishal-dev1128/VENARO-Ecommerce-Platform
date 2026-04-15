# API Reference

All AJAX endpoints live in the `/api/` directory and respond in JSON.

## Endpoints

### `POST /api/cart-add.php`
Add a product to the cart.

**Request body:**
```json
{ "product_id": 1, "quantity": 2 }
```
**Response:**
```json
{ "success": true, "cart_count": 3 }
```

---

### `POST /api/coupon-apply.php`
Apply a coupon code at checkout.

**Request body:**
```json
{ "code": "SAVE20" }
```
**Response:**
```json
{ "success": true, "discount": 200, "type": "percent" }
```

---

### `POST /api/wishlist-toggle.php`
Toggle a product in the user's wishlist.

**Request body:**
```json
{ "product_id": 5 }
```
**Response:**
```json
{ "success": true, "in_wishlist": true }
```

---

### `POST /api/review-submit.php`
Submit a product review.

**Request body:**
```json
{ "product_id": 3, "rating": 5, "comment": "Excellent!" }
```
**Response:**
```json
{ "success": true }
```

---

### `GET /api/search-suggestions.php?q=watch`
Get live search suggestions.

**Response:**
```json
[{ "id": 1, "name": "Luxury Watch", "slug": "luxury-watch" }]
```

---

### `POST /api/newsletter-subscribe.php`
Subscribe to the newsletter.

**Request body:**
```json
{ "email": "user@example.com" }
```
**Response:**
```json
{ "success": true }
```

---

### `POST /api/change-password.php`
Change logged-in user's password.

**Request body:**
```json
{ "current_password": "...", "new_password": "..." }
```

---

### `POST /api/update-profile.php`
Update user profile information.

---

### `POST /api/cancel-order.php`
Cancel an order (if in pending status).

**Request body:**
```json
{ "order_id": 12 }
```

---

> All endpoints return `{ "success": false, "message": "..." }` on failure.
