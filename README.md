# LogiFlow — Logistics Management & Tracking System

A modern logistics management platform built with **Laravel 13**, **Tailwind CSS v4**, and **Alpine.js**. Manage shipments, drivers, vehicles, and customers — with real-time tracking, email notifications, and a public booking form.


![Tests](https://github.com/Shammyshammy/logiflow/actions/workflows/tests.yml/badge.svg)


## ✨ Features

### Public
- **Shipment tracking** — enter any tracking number and see the full status timeline with location history
- **Booking form** — customers can create shipments without an account
- **Animated landing page** with hero + scroll reveals

### Admin & Staff
- Full CRUD for **shipments**, **customers**, **drivers**, **vehicles**
- **Assign** drivers + vehicles to shipments
- **Update status** with location + note (tracked timeline)
- **Dashboard** with stats + charts (7-day trend, status breakdown)
- **CSV + PDF export** (single shipment + bulk)
- **Activity log** (audit trail of every action)
- **In-app notifications** with unread badge
- **Dark mode** with persistence
- **ETA calculations** with overdue highlighting
- **Leaflet maps** showing origin → destination routes

### Driver
- Dedicated dashboard showing assigned deliveries only
- Self-service status updates (picked up → in transit → delivered)

### Customer
- Self-service dashboard showing only their own shipments
- Booking confirmation + status update emails

### System
- Role-based access control (`admin`, `staff`, `driver`, `customer`)
- Email notifications on status changes (markdown mailables)
- Event-driven architecture (Laravel events + listeners)
- Zero external SaaS dependency — runs on any LAMP/LEMP stack

---

## 🧰 Tech Stack

| Layer | Tech |
|---|---|
| Backend | Laravel 13 (PHP 8.3+) |
| Database | MySQL / MariaDB |
| Frontend (public) | Blade + custom CSS (LogiFlow design system) |
| Frontend (admin) | Blade + Tailwind CSS v4 |
| Interactivity | Alpine.js, Chart.js, Leaflet |
| Auth | Laravel Breeze (Blade) |
| Mail | Laravel Mail (markdown mailables) |
| PDF | barryvdh/laravel-dompdf |

---

## 🚀 Installation

```bash
git clone https://github.com/Shammyshammy/logiflow.git
cd logiflow
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logiflow
DB_USERNAME=root
DB_PASSWORD=
```

Then:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Visit `http://127.0.0.1:8000`.

### Default Seeded Accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@logiflow.test` | `password` |
| Staff | `staff@logiflow.test` | `password` |
| Driver | `driver@logiflow.test` | `password` |
| Customer | `customer@logiflow.test` | `password` |

---

## 📧 Email Configuration

During development, emails are written to `storage/logs/laravel.log`:

```env
MAIL_MAILER=log
```

For production, use any SMTP provider:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="LogiFlow"
```

---

## 🗂️ Project Structure

```
app/
├── Events/                 ShipmentStatusUpdated
├── Http/Controllers/       All controllers
├── Http/Middleware/        EnsureUserHasRole
├── Listeners/              SendShipmentStatusEmail
├── Mail/                   ShipmentStatusChanged mailable
├── Models/                 Shipment, Customer, Driver, Vehicle, etc.
└── Notifications/          ShipmentStatusNotification

database/
├── migrations/             All schema
└── seeders/                LogiflowSeeder

resources/
├── css/
│   ├── app.css             Entry point
│   └── logiflow.css        Public design system + admin styles
└── views/
    ├── layouts/            public.blade.php, admin.blade.php, auth.blade.php
    ├── tracking/           Public tracking pages
    ├── booking/            Public booking form + success
    ├── shipments/          CRUD + PDF
    ├── customers/          CRUD
    ├── drivers/            CRUD
    ├── vehicles/           CRUD
    ├── notifications/      In-app notifications
    ├── activity/           Audit log
    ├── emails/             Markdown email templates
    └── vendor/mail/        Published mail components
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📸 Screenshots

> - Landing page - home(2).png
> - Tracking result - trackresult.png
> - Admin dashboard - admindashboard.png
> - Booking - book.png
> - Customers - customers.png
> - Drivers - drivers.png
> - Login - login(2).png
> - Register - register(2).png
> - Shipment detail with Leaflet map - trackresult.png
> - Driver dashboard - driverdashboard.png
> - Dark mode admin - admindashboard darkmode.png

---

## 📝 License

MIT
