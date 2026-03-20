# Product Requirements Document (PRD)

## VÉNARO - Ecommerce Website

### 1. Project Overview
- **Product Name**: VÉNARO
- **Target Audience**: Men seeking luxury apparel (T-Shirts, Sweatshirts, Hoodies, Sweatpants, Varsity Jackets).
- **Platform**: Web-based eCommerce application with a customer-facing frontend and a comprehensive Shopify-style admin panel.
- **Goal**: Deliver a premium, cinematic shopping experience with robust backend management to redefine modern men's fashion retail online.

### 2. Tech Stack & Architecture
- **Frontend Development**: HTML5, CSS3, JavaScript (Vanilla/ES6), AJAX.
- **Backend Development**: PHP 8.1+ (Procedural/Custom MVC concepts).
- **Database**: MySQL/MariaDB (InnoDB, normalized 23-table schema).
- **Environment**: XAMPP (Apache + MySQL).
- **Security**: PDO prepared statements, CSRF tokens, Bcrypt password hashing, XSS prevention.

### 3. Core Features & Requirements

#### 3.1 Customer Authentication & Accounts
- User registration with real-time validation and T&C acceptance.
- Password strength tracking.
- Security policies: 5 failed logins trigger a 15-minute lockout. 'Remember me' functionality for 30 days.
- User profiles with photo upload, address book management (up to 10 addresses).
- Password recovery/forgot password flow.

#### 3.2 Product Discovery & Shopping Experience
- **Homepage**: 95vh luxury hero section, blurred brand watermark, staggered animations.
- **Product Catalog**: Advanced filtering (category, collection, price, size, color) and sorting.
- **Search Engine**: FULLTEXT product search implementation.
- **Product Detail Pages**: Multi-image gallery, size/color variant selection, related products, reviews.
- **Cart & Wishlist**: AJAX-powered, variant-aware cart. Dual persistence (Session + Database).
- **Checkout Processing**: Multi-step flow (Address -> Review -> Payment). Supports Razorpay & COD (with surcharge and cap).
- **Order Management**: Self-service cancellation, guest tracking, print-ready invoices, status updates, and email notifications.

#### 3.3 Admin Panel (Shopify-Style Management)
- **Dashboard Overview**: KPI metrics for Products, Orders, Customers, Revenue.
- **Product Management**: Complete CRUD with variants matrix (Size x Color), multi-image drag-and-drop, SEO live previews.
- **Category & Collection Management**: Hierarchical categories and marketing collections (Featured, Seasonal).
- **Order Processing**: Update order statuses, add tracking info, full audit logs of status changes.
- **Customer Directory**: Manage users, block/unblock capabilities.
- **Marketing Tools**: Coupon engine (percentage, flat, free shipping) with usage limits.
- **Communication & Quality**: Contact message inbox & product review moderation.

### 4. Database Schema Structure
High-level organization of the 23 tables:
- **Users & Auth**: users, addresses, admin_users
- **Catalog**: categories, collections, products, product_variants, product_images, plus M:M relations
- **Sales**: cart, wishlist, orders, order_items, order_status_history
- **Marketing & Support**: coupons, coupon_usage, reviews, support_tickets, ticket_messages, newsletter_subscribers
- **Platform**: settings, faqs

### 5. Design & User Interface Guidelines
- **Color Scheme**: Deep blacks (#000, #111), clean whites (#fff), soft panels (#f8f8f8).
- **Typography**: Playfair Display (branding/categories), Bodoni Moda (headings), Montserrat (CTAs/UI), Inter (body).
- **Interactive Elements**: Sharp-edged buttons (no border-radius), dark fills, smooth cubic-bezier animations, hover zoom effects.
- **Vibe**: Expensive, cinematic, brand-first. No gamified elements or playful styling.

### 6. Security & Infrastructure Policies
- **Authentication**: Bcrypt hashing (cost 12), separate logic for Admin vs User Sessions.
- **Data Safety**: 100% PDO prepared statements for database transactions.
- **File Uploads**: Strict MIME-type checking, 5MB cap.
- **Business Logic**: Flat shipping fee rules, limits on coupon redemption, maximum address counts per user.

### 7. Roadmap & Future Enhancements
- **Phase 6 - Operational**: Automatic PDF generation, advanced admin reporting, coupon logic during checkout.
- **Phase 7 - Growth**: Full SEO optimization, Google OAuth login, multi-currency display, referral rewards.
- **Phase 8 - Scale**: Redis caching, CDN offloading, master-slave database replication, PWA adoption.
