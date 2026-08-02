# General Online Store — PHP MVC E-Commerce Store

A full-featured e-commerce web application built with **PHP 8**, **MySQL**, **MVC architecture**, and **OOP conventions**. Features a complete storefront with dark mode, bilingual support (English/Khmer), and a full admin panel.

## Demo

- **Store:** `http://localhost/project-php-oop/eshop-oop/`
- **Admin Panel:** `http://localhost/project-php-oop/eshop-oop/admin/dashboard`

## Tech Stack

- **Backend:** PHP 8 (MVC + OOP)
- **Database:** MySQL (XAMPP)
- **Frontend:** Tailwind CSS, Font Awesome 6
- **Server:** Apache (XAMPP)

## Features

### Storefront
- Homepage with hero banner, new arrivals, featured products, promo banners
- Shop page with category filter, price range filter, search
- Product detail page with compare price and discount badges
- Shopping cart with quantity management
- Checkout with invoice generation
- My Orders page for customers

### Admin Panel
- Dashboard with sales overview, recent orders, low stock alerts
- Product management (CRUD) with image upload, category assignment, compare price
- Category management (CRUD) with icons and sort order
- Order management with status tracking and invoice viewing
- Inventory management with stock adjustments (add/remove/set)
- User management (CRUD) with role assignment
- Global search across products, orders, and users
- Real-time calendar and live clock in header

### UI/UX
- **Dark/Light Mode** — toggle with localStorage persistence
- **Bilingual** — English and Khmer (KM) with session-based switching
- **Toast Notifications** — success/error popups with auto-dismiss
- **Responsive** — mobile-friendly navigation and layouts
- **Compare Price** — show discounts with strikethrough and percentage badges
- **Low Stock Alerts** — badges at threshold (≤10 units)

## Project Structure

```
general-online-store/
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   └── HomeController.php
│   ├── Core/
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Session.php
│   │   └── Lang/
│   │       ├── Language.php
│   │       ├── en.php
│   │       └── km.php
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Product.php
│   │   └── User.php
│   └── Views/
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── products.php
│       │   ├── product_form.php
│       │   ├── categories.php
│       │   ├── category_form.php
│       │   ├── orders.php
│       │   ├── order_detail.php
│       │   ├── inventory.php
│       │   ├── users.php
│       │   └── user_form.php
│       ├── customer/
│       │   ├── home.php
│       │   ├── shop.php
│       │   ├── product_detail.php
│       │   ├── cart.php
│       │   ├── checkout.php
│       │   ├── invoice.php
│       │   ├── my_orders.php
│       │   ├── login.php
│       │   └── register.php
│       └── layouts/
│           ├── admin.php
│           ├── customer.php
│           └── auth.php
├── config/
│   ├── config.php
│   └── database.sql
├── public/
│   ├── .htaccess
│   ├── index.php
│   └── uploads/
├── images/
└── .htaccess
```

## Setup & Installation

Follow these steps to set up the project on your local machine (XAMPP):

1. **Move Project:** Place the `eshop-oop` folder inside your XAMPP `htdocs` directory.
2. **Database Setup:** 
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `eshop_db`.
3. **Configuration:** 
   - Open `config/config.php`.
   - Update `DB_USER` and `DB_PASS` if they differ from your local MySQL settings.
4. **Auto-Initialization:** 
   - Start Apache and MySQL in your XAMPP Control Panel.
   - Open your browser and visit `http://localhost/project-php-oop/eshop-oop/`.
   - The system will automatically detect the empty database and create all required tables and default data.
5. **Import Cambodia Address Data (Essential for Demo):**
   - The project includes a complete location cascading system (Province -> District -> Commune -> Village).
   - To populate the database with over 14,000 locations, open your terminal/command prompt in the project root and run:
     ```bash
     php database/seeds/seed_cambodia.php
     ```
   - *Wait 1-2 minutes until you see "Done! Cambodia address data seeded successfully."*

## Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@store.com` | `admin123` |
| **Customer** | `john@gmail.com` | `customer123` |

## Database Tables

- **users** — user accounts with roles (admin/customer)
- **categories** — product categories with icons
- **products** — products with price, compare price, stock, image, category
- **orders** — customer orders with invoice numbers
- **order_items** — individual items per order

## License

MIT
opencode -s ses_0511f7de2ffeGAmlyqjXUS9Qon