# E-commerce Website

A complete e-commerce solution with admin dashboard and user interface.

## Project Structure

```
/ecommerce/
│
├── /admin/                # Admin dashboard
│   ├── index.php
│   ├── login.php
│   ├── products.php
│   ├── categories.php
│   ├── orders.php
│   ├── banners.php
│   └── ... (other admin files)
│
├── /includes/             # Common PHP includes (DB, auth, helpers)
│   ├── db.php
│   ├── auth.php
│   └── functions.php
│
├── /assets/               # CSS, JS, images
│   ├── /css/
│   ├── /js/
│   └── /images/
│
├── /user/                 # User-facing pages
│   ├── index.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php
│   └── ... (other user files)
│
├── /uploads/              # Uploaded images (products, banners)
│
├── .htaccess
├── config.php
└── README.md
```

## Setup Instructions

1. Configure your web server (Apache/Nginx) to point to the project directory
2. Create a MySQL database and import the database schema
3. Update database credentials in `config.php`
4. Ensure the `uploads` directory has write permissions
5. Access the admin panel at `/admin` and user interface at `/user`

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for Apache)

## Security

- All sensitive data is encrypted
- CSRF protection implemented
- XSS prevention measures in place
- SQL injection prevention
- Secure password hashing

## Features

### Admin Panel
- Product management
- Category management
- Order management
- Banner management
- User management
- Sales reports
- Inventory tracking

### User Interface
- Product browsing
- Shopping cart
- Checkout process
- Order tracking
- User account management
- Wishlist
- Product reviews 