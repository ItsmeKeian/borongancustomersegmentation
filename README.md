# Borongan City CRM & Customer Segmentation Platform

A comprehensive Customer Relationship Management (CRM) and marketing automation platform designed for local businesses in Borongan City, Philippines. Establishments can manage customer data, create demographic-based segments, run targeted SMS/Email campaigns, track sales and inventory, and view analytics through dashboards and reports.

---

## Features

### Admin Panel
- **Dashboard** — Total establishments, establishment types, system logs, login attempts, growth charts
- **Establishments** — Create and manage business accounts (Food & Beverage, Retail, Services, etc.)
- **Messages** — Chat with establishments (help/support)
- **System Settings** — System logs, failed login attempts, system information (version, backup, PHP, DB size)

### Establishment (Business) Portal
- **Dashboard** — Total customers, segments, sent campaigns, total revenue, growth and segment charts
- **Customers** — Add, edit, view customers; CSV/Excel import; demographics (age, gender, location, occupation, income, education)
- **Purchased** — Purchase history and records
- **High-Risk Customers** — Identify and manage high-risk customers
- **Product Analytics** — Product performance and sales insights
- **Campaigns** — Create and send SMS/Email campaigns to segments (scheduled or immediate)
- **Segmentation** — Create segments (e.g. by age range, demographics) and filter segment members
- **Reports** — Revenue by segment, exports, and analytics
- **Inventory** — Product/inventory management
- **Reminders** — Calendar and reminder system
- **System Logs** — Establishment-level activity logs
- **Settings** — Business profile, QR code generation for customer registration
- **Notifications** — In-app notifications
- **Help** — Tutorial video and chatbot for admin support

### Public
- **Customer Registration** — QR-code-based public form; customers register per establishment with segment selection

---

## Tech Stack

| Layer        | Technology |
|-------------|------------|
| Backend     | PHP 7.2+ (MySQLi + PDO) |
| Database    | MySQL / MariaDB |
| Frontend    | HTML5, Bootstrap 5, jQuery |
| Charts      | Chart.js |
| Alerts/UI   | SweetAlert2 |
| Email       | PHPMailer |
| SMS         | Twilio SDK |
| Spreadsheets| PhpSpreadsheet |
| PDF         | TCPDF (via vendor) |

---

## Requirements

- **PHP** 7.2 or higher (8.x recommended)
- **MySQL** 5.7+ or **MariaDB** 10.2+
- **Composer** (for PHP dependencies)
- **Web server** (Apache with mod_rewrite, or Nginx) — e.g. XAMPP, WAMP, or LAMP

---

## Installation

### 1. Clone or copy the project

Place the project in your web root (e.g. `htdocs/borongan` for XAMPP).

### 2. Install PHP dependencies

```bash
cd borongan
composer install
```

This installs:
- `phpoffice/phpspreadsheet` — Excel/CSV import/export
- `phpmailer/phpmailer` — Email sending
- `twilio/sdk` — SMS campaigns

### 3. Database setup

1. Create a MySQL database, e.g.:

   ```sql
   CREATE DATABASE custsegmentation CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

2. Import the schema and data:

   - Use the SQL file in `sql/` (e.g. `u235984133_segmentation.sql`).
   - If the file uses a different database name, either:
     - Create a DB with that name and import, then update `php/dbconnect.php` to use it, or  
     - Replace the DB name in the SQL file with `custsegmentation` and import into `custsegmentation`.

   ```bash
   mysql -u root -p custsegmentation < sql/u235984133_segmentation.sql
   ```

   Or import via phpMyAdmin.

### 4. Configure database connection

Edit `php/dbconnect.php` and set your credentials:

```php
define("DB_HOST", "localhost");
define("DB_NAME", "custsegmentation");
define("DB_USER", "root");
define("DB_PASS", "");
```

Use your actual host, database name, user, and password.

### 5. Run the application

- Start Apache and MySQL (e.g. from XAMPP Control Panel).
- Open in browser:  
  `http://localhost/borongan/`  
  (adjust host and path if different).

---

## Usage

### Login

- **URL:** `index.php` (or your site root).
- **Roles:**
  - **Establishment** — Business login (email + password).
  - **System Admin** — Admin login (username + password).

### Main entry points

| Role          | Entry point / area |
|---------------|---------------------|
| Admin         | `admindashboard.php`, `adminestablishment.php`, `adminsystemsettings.php`, `admin_messages.php` |
| Establishment | `establishment_dashboard.php` (then use sidebar for Customers, Campaigns, Segmentation, etc.) |
| Public        | `public_registry.php?est=EstablishmentName` (e.g. from QR code link) |

### Optional: Email (PHPMailer) and SMS (Twilio)

- **Email:** Configure SMTP in the code that uses PHPMailer (e.g. `php/mailer.php` or campaign scripts).
- **SMS:** Set Twilio credentials (Account SID, Auth Token, phone number) in the script that uses the Twilio SDK (e.g. `php/sms_sender.php` or campaign send logic).

---

## Project structure (overview)

```
borongan/
├── php/                    # Backend logic
│   ├── dbconnect.php        # Database config (edit this)
│   ├── require_login.php   # Auth / role check
│   ├── chat/               # Admin–establishment messaging
│   ├── create/             # Create campaign, customer, establishment, segment, etc.
│   ├── get/                # API-style get (customers, segments, logs, etc.)
│   ├── update/             # Update customer, establishment, purchased, etc.
│   ├── delete/             # Delete records
│   ├── count/              # Dashboard counts
│   ├── import/             # CSV/Excel import
│   ├── export/             # Report export
│   ├── inventory/          # Inventory CRUD
│   ├── reminder/           # Reminders
│   └── ...
├── css/                    # Styles (admin, establishment, alerts, etc.)
├── js/                     # Frontend scripts (dashboard, campaigns, customers, etc.)
├── sql/                    # Database dump(s)
├── logs/                    # Application logs
├── vendor/                  # Composer dependencies
├── index.php               # Login
├── default.php             # Post-login redirect
├── public_registry.php     # Public customer registration (QR)
├── admindashboard.php
├── adminestablishment.php
├── adminsystemsettings.php
├── establishment_dashboard.php
├── establishment_customers.php
├── establishment_campaigns.php
├── establishment_segmentation.php
├── ...                     # Other establishment_*.php pages
├── composer.json
└── README.md               # This file
```

---

## License

This project is for portfolio/educational use. Adjust licensing as needed for your context.

---

## Author

Borongan City CRM & Customer Segmentation Platform — portfolio project.
