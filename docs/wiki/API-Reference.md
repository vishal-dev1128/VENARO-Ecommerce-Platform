# API Reference 🔌

VÉNARO uses AJAX extensively for a smooth, single-page feel. Most endpoints are located in the `api/` directory.

---

## 🛒 Cart Management

### `cart-add.php`
- **Method**: `POST`
- **Target**: Adds a product variant to the cart.
- **Payload**: `product_id`, `variant_id`, `quantity`.

### `cart-update.php`
- **Method**: `POST`
- **Target**: Updates the quantity of a cart item.
- **Payload**: `cart_id`, `quantity`.

---

## ❤️ Wishlist Management

### `wishlist-toggle.php`
- **Method**: `POST`
- **Target**: Adds or removes a product from the user's wishlist.
- **Payload**: `product_id`.

---

## 📩 Marketing

### `newsletter-subscribe.php`
- **Method**: `POST`
- **Target**: Subscribes an email address to the newsletter.
- **Payload**: `email`.

---

## 🔐 Auth Helpers

### `check-email.php`
- **Method**: `GET`
- **Target**: Checks if an email is already registered (used for real-time validation).
- **Params**: `email`.
