# EventTick - Event Ticketing System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-green.svg)](https://mysql.com)

EventTick adalah sistem ticketing event yang lengkap dan modern, dibangun dengan Laravel 12. Sistem ini menyediakan platform untuk manajemen event, pemesanan tiket, pembayaran, dan verifikasi QR code.

## ✨ Features

### 🎫 Public Features
- **Event Listing** - Lihat semua event yang tersedia
- **Event Search** - Cari event berdasarkan keyword
- **Event Details** - Informasi lengkap setiap event
- **User Registration & Login** - Sistem autentikasi yang aman

### 👤 User Features
- **Event Booking** - Pemesanan tiket dengan mudah
- **Checkout Process** - Proses pembayaran yang simpel
- **Payment Proof Upload** - Upload bukti pembayaran
- **Booking Management** - Kelola semua booking Anda
- **Ticket Download** - Download tiket dalam format PDF
- **QR Code Verification** - Scan QR code untuk verifikasi

### 🔧 Admin Features
- **Dashboard** - Overview sistem yang informatif
- **Event Management** - CRUD event dengan interface yang mudah
- **Booking Management** - Kelola semua booking user
- **Payment Approval** - Setujui/tolak pembayaran
- **Bank Account Management** - Kelola rekening bank
- **Ticket Validation** - Validasi tiket saat event

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

### Installation

1. **Clone Repository**
```bash
git clone <repository-url>
cd EventTick
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
# Update .env file dengan database credentials
php artisan migrate
php artisan db:seed
```

5. **Storage Setup**
```bash
php artisan storage:link
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Server**
```bash
php artisan serve
```

### Default Accounts
- **Admin**: `admin@eventtick.com` / `password`
- **User**: `user@eventtick.com` / `password`

## 🏗️ Architecture

### Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates + Tailwind CSS
- **PDF Generation**: DomPDF
- **QR Code**: External API (qrserver.com)
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage

### Project Structure
```
EventTick/
├── app/
│   ├── Http/Controllers/     # Application Controllers
│   ├── Models/              # Eloquent Models
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Database Schema
│   └── seeders/            # Sample Data
├── resources/
│   └── views/              # Blade Templates
├── routes/
│   ├── web.php             # Web Routes
│   └── auth.php            # Authentication Routes
└── public/                 # Public Assets
```

## 🎫 Ticket System

### Ticket Generation Flow
1. User melakukan booking event
2. Sistem generate ticket code unik
3. QR code dibuat menggunakan external API
4. Ticket disimpan dengan status 'pending'
5. Setelah pembayaran diverifikasi, status berubah menjadi 'active'

### Ticket Status Flow
```
pending → active → used
    ↓
cancelled
```

## 💳 Payment System

### Payment Flow
1. User mengisi form customer data
2. Pilih metode pembayaran (Bank Transfer/E-Wallet)
3. Pilih rekening bank (jika bank transfer)
4. Submit booking
5. Upload bukti pembayaran
6. Admin verifikasi pembayaran
7. Ticket status berubah menjadi 'active'

## 🔍 QR Code System

### QR Code Data
QR code berisi informasi lengkap:
- Ticket code
- Event ID
- Booking ID
- Customer information
- Ticket number

### Scanning Process
1. Admin scan QR code menggunakan device
2. Sistem validasi ticket
3. Update status ticket menjadi 'used'
4. Record timestamp penggunaan

## 📄 PDF Generation

### Ticket PDF Features
- Event information yang lengkap
- Customer details
- QR code untuk verifikasi
- Important notes
- Design yang profesional dan menarik

### PDF Library
- **DomPDF**: HTML to PDF conversion
- **Custom Styling**: Optimized for print
- **QR Code Integration**: Embedded QR codes

## 🔐 Security Features

- **Authentication Security**: Password hashing dengan bcrypt
- **CSRF Protection**: Cross-site request forgery protection
- **Input Validation**: Validasi input yang ketat
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Output escaping
- **File Upload Security**: File type dan size validation

## 📱 API Endpoints

### Public Endpoints
```
GET  /                    # Home page
GET  /events             # Event listing
GET  /events/search      # Event search
GET  /events/{id}        # Event details
```

### User Endpoints (Auth Required)
```
GET  /checkout/{event}           # Checkout page
POST /checkout/{event}/process   # Process booking
GET  /bookings                   # User bookings
GET  /tickets/{id}/download      # Download ticket PDF
GET  /tickets/scan               # QR scan page
```

### Admin Endpoints (Admin Required)
```
GET  /admin/dashboard            # Admin dashboard
GET  /admin/events              # Event management
GET  /admin/bookings            # Booking management
GET  /admin/bank-accounts       # Bank account management
```

## 🚨 Error Handling

- **Custom Error Pages**: User experience yang lebih baik
- **Logging**: Comprehensive logging untuk debugging
- **Validation Errors**: Clear error messages
- **Graceful Fallbacks**: System tetap berjalan meski ada error

## 🔧 Maintenance

### Regular Tasks
- Database backup secara berkala
- Log rotation
- Security updates
- Performance monitoring

### Troubleshooting
- QR Code issues: Cek external API availability
- PDF Generation: Verifikasi DomPDF installation
- File Upload: Cek storage permissions
- Database: Monitor query performance

## 📈 Performance

### Optimization Techniques
- Database indexing pada foreign keys
- Eager loading untuk relationships
- Route caching
- View caching
- Asset minification

## 🚀 Deployment

### Production Requirements
- **Web Server**: Nginx/Apache
- **PHP**: 8.2+ dengan extensions yang diperlukan
- **Database**: MySQL 8.0+ dengan proper configuration
- **SSL Certificate**: HTTPS encryption
- **Backup Strategy**: Automated backup system

## 📝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Code Standards
- PSR-12 coding standards
- Proper documentation
- Unit testing
- Code review process

## 📞 Support

- **Documentation**: [DOCUMENTATION.md](DOCUMENTATION.md)
- **Issues**: [GitHub Issues](https://github.com/your-repo/issues)
- **Email**: support@eventtick.com

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - HTML to PDF conversion
- [QR Server](https://api.qrserver.com) - QR code generation API

---

**Version**: 1.0.0  
**Last Updated**: {{ date('Y-m-d') }}  
**Maintainer**: EventTick Development Team

⭐ **Star this repository if you find it helpful!**
# Event-Ticketing
