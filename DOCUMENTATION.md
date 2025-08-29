# EventTick - Event Ticketing System Documentation

## 📋 Overview
EventTick adalah sistem ticketing event yang dibangun dengan Laravel 12, menyediakan platform lengkap untuk manajemen event, pemesanan tiket, dan verifikasi QR code.

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
│   ├── Providers/           # Service Providers
│   └── Console/             # Artisan Commands
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

## 🗄️ Database Schema

### Core Tables

#### 1. Users Table
```sql
users
├── id (bigint, primary key)
├── name (varchar)
├── email (varchar, unique)
├── password (varchar)
├── role (enum: 'user', 'admin')
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### 2. Events Table
```sql
events
├── id (bigint, primary key)
├── title (varchar)
├── description (text)
├── venue (varchar)
├── event_date (datetime)
├── price (decimal)
├── capacity (int)
├── image (varchar, nullable)
├── status (enum: 'active', 'inactive')
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### 3. Bookings Table
```sql
bookings
├── id (bigint, primary key)
├── user_id (bigint, foreign key)
├── event_id (bigint, foreign key)
├── booking_code (varchar, unique)
├── quantity (int)
├── total_amount (decimal)
├── status (enum: 'pending', 'paid', 'cancelled')
├── payment_status (enum: 'pending', 'paid', 'failed')
├── payment_method (enum: 'bank_transfer', 'ewallet')
├── customer_name (varchar)
├── customer_email (varchar)
├── customer_phone (varchar)
├── paid_at (timestamp, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### 4. Tickets Table
```sql
tickets
├── id (bigint, primary key)
├── booking_id (bigint, foreign key)
├── event_id (bigint, foreign key)
├── ticket_code (varchar, unique)
├── customer_name (varchar)
├── customer_email (varchar)
├── customer_phone (varchar)
├── ticket_number (varchar)
├── qr_code (text)
├── status (enum: 'pending', 'active', 'used', 'cancelled')
├── used_at (timestamp, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### 5. Payments Table
```sql
payments
├── id (bigint, primary key)
├── booking_id (bigint, foreign key)
├── payment_method (enum: 'bank_transfer', 'ewallet')
├── amount (decimal)
├── status (enum: 'pending', 'success', 'failed')
├── payment_details (json)
├── payment_proof (varchar, nullable)
├── bank_account_id (bigint, foreign key, nullable)
├── paid_at (timestamp, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

#### 6. Bank Accounts Table
```sql
bank_accounts
├── id (bigint, primary key)
├── bank_name (varchar)
├── account_number (varchar)
├── account_holder (varchar)
├── account_type (enum: 'savings', 'current')
├── is_active (boolean)
├── description (text, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

## 🚀 Features

### 1. Public Features
- **Event Listing**: Melihat daftar event yang tersedia
- **Event Search**: Pencarian event berdasarkan keyword
- **Event Details**: Informasi lengkap event
- **User Registration & Login**: Sistem autentikasi

### 2. User Features
- **Event Booking**: Pemesanan tiket event
- **Checkout Process**: Proses pembayaran dengan form customer data
- **Payment Proof Upload**: Upload bukti pembayaran
- **Booking Management**: Lihat dan kelola booking
- **Ticket Download**: Download tiket dalam format PDF
- **QR Code Verification**: Scan QR code untuk verifikasi

### 3. Admin Features
- **Dashboard**: Overview sistem
- **Event Management**: CRUD event
- **Booking Management**: Kelola semua booking
- **Payment Approval**: Setujui/tolak pembayaran
- **Bank Account Management**: Kelola rekening bank
- **Ticket Validation**: Validasi tiket saat event

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM (untuk Tailwind CSS)

### Installation Steps

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

4. **Database Configuration**
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
- **Admin**: admin@eventtick.com / password
- **User**: user@eventtick.com / password

## 📱 API Endpoints

### Public Endpoints
```
GET  /                    # Home page
GET  /events             # Event listing
GET  /events/search      # Event search
GET  /events/{id}        # Event details
```

### Authentication Endpoints
```
POST /login              # User login
POST /register           # User registration
POST /logout             # User logout
```

### User Endpoints (Auth Required)
```
GET  /checkout/{event}           # Checkout page
POST /checkout/{event}/process   # Process booking
GET  /checkout/success/{booking} # Success page
POST /checkout/{booking}/upload-proof # Upload payment proof

GET  /bookings                   # User bookings
GET  /bookings/{id}              # Booking details
POST /bookings/{id}/confirm-payment # Confirm payment

GET  /tickets/{id}               # Ticket details
GET  /tickets/{id}/download      # Download ticket PDF

GET  /tickets/scan               # QR scan page
GET  /tickets/scan/{code}        # Scan result
GET  /tickets/scan/{code}/api    # Scan API
POST /tickets/validate/{code}    # Validate ticket
```

### Admin Endpoints (Admin Required)
```
GET  /admin/dashboard                    # Admin dashboard
GET  /admin/events                      # Event management
POST /admin/events                      # Create event
GET  /admin/events/{id}/edit            # Edit event
PUT  /admin/events/{id}                 # Update event
DELETE /admin/events/{id}               # Delete event
PATCH /admin/events/{id}/toggle-status  # Toggle event status

GET  /admin/bookings                    # Booking management
GET  /admin/bookings/{id}               # Booking details
PATCH /admin/bookings/{id}/approve-payment # Approve payment
PATCH /admin/bookings/{id}/reject-payment  # Reject payment
PATCH /admin/bookings/{id}/cancel       # Cancel booking
PATCH /admin/tickets/{id}/mark-used     # Mark ticket used

GET  /admin/bank-accounts               # Bank account management
POST /admin/bank-accounts               # Create bank account
GET  /admin/bank-accounts/{id}/edit     # Edit bank account
PUT  /admin/bank-accounts/{id}          # Update bank account
DELETE /admin/bank-accounts/{id}        # Delete bank account
PATCH /admin/bank-accounts/{id}/toggle-status # Toggle status
```

## 🔐 Authentication & Authorization

### User Roles
- **User**: Akses terbatas ke fitur pemesanan dan manajemen booking pribadi
- **Admin**: Akses penuh ke semua fitur sistem

### Middleware
- `auth`: Memastikan user sudah login
- `admin`: Memastikan user memiliki role admin

## 🎫 Ticket System

### Ticket Generation
1. User melakukan booking
2. Sistem generate ticket code unik
3. QR code dibuat menggunakan external API
4. Ticket disimpan dengan status 'pending'

### Ticket Status Flow
```
pending → active → used
    ↓
cancelled
```

### QR Code Data
QR code berisi informasi:
- Ticket code
- Event ID
- Booking ID
- Customer information
- Ticket number

## 💳 Payment System

### Payment Flow
1. User mengisi form customer data
2. Pilih metode pembayaran
3. Pilih rekening bank (jika bank transfer)
4. Submit booking
5. Upload bukti pembayaran
6. Admin verifikasi pembayaran
7. Ticket status berubah menjadi 'active'

### Payment Methods
- **Bank Transfer**: Transfer ke rekening yang tersedia
- **E-Wallet**: Pembayaran via e-wallet

## 📊 Database Relationships

### Model Relationships
```php
User
├── hasMany(Booking)
└── hasMany(Ticket)

Event
├── hasMany(Booking)
└── hasMany(Ticket)

Booking
├── belongsTo(User)
├── belongsTo(Event)
├── hasMany(Ticket)
└── hasMany(Payment)

Ticket
├── belongsTo(Booking)
└── belongsTo(Event)

Payment
├── belongsTo(Booking)
└── belongsTo(BankAccount)

BankAccount
└── hasMany(Payment)
```

## 🎨 Frontend Components

### Blade Templates Structure
```
resources/views/
├── layouts/
│   └── app.blade.php          # Main layout
├── public/
│   └── events/                # Public event views
├── admin/
│   ├── dashboard.blade.php     # Admin dashboard
│   ├── events/                 # Event management views
│   ├── bookings/               # Booking management views
│   └── bank-accounts/          # Bank account views
├── auth/                       # Authentication views
├── bookings/                   # User booking views
├── checkout/                   # Checkout process views
└── tickets/                    # Ticket views
```

### CSS Framework
- **Tailwind CSS**: Utility-first CSS framework
- **Custom CSS**: Hover effects dan interactive elements
- **Responsive Design**: Mobile-first approach

## 🔍 QR Code Scanning

### Scanning Process
1. Admin scan QR code menggunakan device
2. Sistem validasi ticket
3. Update status ticket menjadi 'used'
4. Record timestamp penggunaan

### Validation Rules
- Ticket harus ada dalam database
- Status ticket harus 'active'
- Ticket belum digunakan sebelumnya

## 📄 PDF Generation

### Ticket PDF Features
- Event information
- Customer details
- QR code for verification
- Important notes
- Professional design

### PDF Library
- **DomPDF**: HTML to PDF conversion
- **Custom Styling**: Optimized for print
- **QR Code Integration**: Embedded QR codes

## 🚨 Error Handling

### Common Errors
- **404**: Resource tidak ditemukan
- **403**: Unauthorized access
- **422**: Validation errors
- **500**: Server errors

### Error Pages
- Custom error pages untuk user experience yang lebih baik
- Logging untuk debugging

## 🔧 Maintenance & Updates

### Regular Tasks
- **Database Backup**: Backup database secara berkala
- **Log Rotation**: Rotate log files
- **Security Updates**: Update dependencies
- **Performance Monitoring**: Monitor system performance

### Troubleshooting
- **QR Code Issues**: Cek external API availability
- **PDF Generation**: Verifikasi DomPDF installation
- **File Upload**: Cek storage permissions
- **Database**: Monitor query performance

## 📈 Performance Optimization

### Database Optimization
- Proper indexing pada foreign keys
- Eager loading untuk relationships
- Query optimization

### Caching Strategy
- Route caching
- View caching
- Database query caching

### Asset Optimization
- CSS/JS minification
- Image optimization
- CDN integration (optional)

## 🔒 Security Features

### Authentication Security
- Password hashing dengan bcrypt
- CSRF protection
- Session security

### Data Protection
- Input validation
- SQL injection prevention
- XSS protection

### File Upload Security
- File type validation
- File size limits
- Secure storage paths

## 🚀 Deployment

### Production Requirements
- **Web Server**: Nginx/Apache
- **PHP**: 8.2+ dengan extensions yang diperlukan
- **Database**: MySQL 8.0+ dengan proper configuration
- **SSL Certificate**: HTTPS encryption
- **Backup Strategy**: Automated backup system

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eventtick
DB_USERNAME=username
DB_PASSWORD=password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 📝 Contributing

### Code Standards
- PSR-12 coding standards
- Proper documentation
- Unit testing
- Code review process

### Development Workflow
1. Fork repository
2. Create feature branch
3. Make changes
4. Write tests
5. Submit pull request

## 📞 Support

### Documentation
- API documentation
- User manual
- Admin guide
- Troubleshooting guide

### Contact
- Technical support: tech@eventtick.com
- User support: support@eventtick.com
- Bug reports: bugs@eventtick.com

---

**Version**: 1.0.0  
**Last Updated**: {{ date('Y-m-d') }}  
**Maintainer**: EventTick Development Team

# Fitur QR Code Tiket Event

## Deskripsi
Fitur ini memungkinkan sistem untuk menghasilkan QR code yang memuat data lengkap pemesanan tiket event. Ketika QR code di-scan, akan muncul detail lengkap pemesanan tiket termasuk informasi event, customer, dan status pembayaran.

## Fitur Utama

### 1. Generate QR Code Otomatis
- QR code dibuat otomatis saat tiket dibuat
- Memuat data lengkap pemesanan tiket
- Disimpan di storage dengan format PNG

### 2. QR Code Scanner
- Halaman scan QR code dengan kamera
- Input manual kode tiket
- Tampilan hasil scan yang informatif

### 3. Validasi Tiket
- Fitur validasi tiket untuk entry event
- Update status tiket menjadi "used"
- Tracking waktu penggunaan tiket

### 4. Data yang Di-encode dalam QR Code
```json
{
    "ticket_id": 1,
    "ticket_code": "TKT001",
    "ticket_number": "TKT-000001",
    "event_title": "Nama Event",
    "event_date": "2024-01-01 19:00",
    "venue": "Lokasi Event",
    "customer_name": "Nama Customer",
    "customer_email": "email@example.com",
    "status": "active",
    "booking_code": "BK001",
    "scan_url": "https://example.com/tickets/scan/TKT001"
}
```

## Cara Penggunaan

### 1. Generate QR Code untuk Tiket yang Sudah Ada
```bash
# Generate QR code untuk semua tiket
php artisan tickets:generate-qr --all

# Generate QR code untuk tiket tertentu
php artisan tickets:generate-qr --ticket-id=1

# Generate QR code untuk tiket yang belum punya QR code
php artisan tickets:generate-qr
```

### 2. Scan QR Code
1. Buka halaman `/tickets/scan`
2. Klik "Mulai Scan" untuk menggunakan kamera
3. Arahkan kamera ke QR code tiket
4. Atau masukkan kode tiket secara manual

### 3. Validasi Tiket
1. Setelah scan, klik "Validasi Tiket" jika status aktif
2. Konfirmasi validasi
3. Status tiket akan berubah menjadi "used"

## Struktur File

```
app/
├── Services/
│   └── QrCodeService.php          # Service untuk generate QR code
├── Http/Controllers/
│   └── TicketScanController.php   # Controller untuk scan dan validasi
├── Console/Commands/
│   └── GenerateTicketQrCodes.php  # Command artisan untuk generate QR code
└── Models/
    └── Ticket.php                 # Model dengan observer untuk auto-generate

resources/views/tickets/
├── scan.blade.php                 # Halaman scan QR code
└── scan-result.blade.php          # Halaman hasil scan

config/
└── qrcode.php                     # Konfigurasi QR code
```

## Routes

```php
// QR Code scanning routes
Route::get('/tickets/scan', [TicketScanController::class, 'showScanPage'])->name('tickets.scan');
Route::get('/tickets/scan/{ticketCode}', [TicketScanController::class, 'scanTicket'])->name('tickets.scan-result');
Route::get('/tickets/scan/{ticketCode}/api', [TicketScanController::class, 'scanTicketApi'])->name('tickets.scan-api');
Route::post('/tickets/validate/{ticketCode}', [TicketScanController::class, 'validateTicket'])->name('tickets.validate');
```

## API Endpoints

### Scan Ticket
```
GET /tickets/scan/{ticketCode}/api
```

Response:
```json
{
    "success": true,
    "data": {
        "ticket_id": 1,
        "ticket_code": "TKT001",
        "event_title": "Nama Event",
        "customer_name": "Nama Customer",
        "status": "active",
        "is_valid": true,
        "is_used": false
    }
}
```

### Validate Ticket
```
POST /tickets/validate/{ticketCode}
```

Response:
```json
{
    "success": true,
    "message": "Tiket berhasil divalidasi",
    "data": {
        "ticket_code": "TKT001",
        "customer_name": "Nama Customer",
        "event_title": "Nama Event",
        "validated_at": "2024-01-01 20:00:00"
    }
}
```

## Konfigurasi

File `config/qrcode.php` berisi konfigurasi untuk:
- Format dan ukuran QR code
- Storage location
- Data yang di-include
- Styling dan warna

## Dependencies

- `simplesoftwareio/simple-qrcode`: Package untuk generate QR code
- `html5-qrcode`: Library JavaScript untuk scan QR code di browser

## Keamanan

- Hanya admin yang bisa mengakses fitur scan dan validasi
- CSRF protection untuk semua POST requests
- Validasi data tiket sebelum update status

## Troubleshooting

### QR Code tidak muncul
1. Pastikan storage link sudah dibuat: `php artisan storage:link`
2. Cek permission folder storage
3. Jalankan command generate QR code

### Scanner tidak berfungsi
1. Pastikan menggunakan HTTPS (untuk akses kamera)
2. Cek permission kamera di browser
3. Pastikan library html5-qrcode ter-load

### Error generate QR code
1. Cek log Laravel untuk detail error
2. Pastikan package QR code terinstall
3. Cek konfigurasi storage

## Pengembangan Selanjutnya

- [ ] QR code dengan logo custom
- [ ] Batch validation untuk multiple tiket
- [ ] Export data scan history
- [ ] Mobile app untuk scan
- [ ] Offline QR code validation
