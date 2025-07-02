# 🏥 Sistem Manajemen Apotek Puri Pasir Putih

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-3.0-F59E0B?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Sistem manajemen apotek modern dengan interface yang user-friendly dan fitur lengkap**

[Demo](#demo) • [Instalasi](#instalasi) • [Fitur](#fitur) • [Dokumentasi](#dokumentasi) • [Kontribusi](#kontribusi)

</div>

---

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Penggunaan](#penggunaan)
- [API Documentation](#api-documentation)
- [Screenshots](#screenshots)
- [Troubleshooting](#troubleshooting)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)
- [Kontak](#kontak)

---

## 🎯 Tentang Proyek

Sistem Manajemen Apotek Puri Pasir Putih adalah aplikasi web modern yang dirancang untuk mengelola operasional apotek secara efisien. Sistem ini menyediakan interface yang intuitif untuk admin dan kasir, serta halaman publik yang menarik untuk pelanggan.

### 🌟 Keunggulan

- **Modern UI/UX** - Interface yang clean dan responsive
- **Multi-Role System** - Admin dan Kasir dengan hak akses berbeda
- **Real-time Operations** - Transaksi dan inventory real-time
- **Comprehensive Reports** - Laporan lengkap dengan export PDF/Excel
- **Mobile Friendly** - Responsive design untuk semua device
- **Secure** - Sistem keamanan berlapis dengan authentication

---

## 🚀 Fitur Utama

### 👨‍💼 Panel Admin
- **Dashboard Analytics** - Overview bisnis dengan grafik dan statistik
- **Manajemen Obat** - CRUD obat dengan kategori dan stok
- **Manajemen User** - Kelola admin dan kasir
- **Laporan Penjualan** - Report detail dengan filter tanggal
- **Laporan Pembelian** - Tracking pembelian dan supplier
- **Export Data** - PDF dan Excel export dengan multiple format
- **Backup & Restore** - Sistem backup database otomatis

### 💰 Sistem Kasir
- **Point of Sale** - Interface kasir yang cepat dan mudah
- **Pencarian Obat** - Search real-time dengan autocomplete
- **Manajemen Transaksi** - Proses pembayaran tunai dan QRIS
- **Print Struk** - Cetak struk otomatis
- **Obat Racikan** - Support untuk obat racikan/resep dokter
- **Stok Real-time** - Update stok otomatis setelah transaksi

### 🌐 Halaman Publik
- **Modern Landing Page** - Design modern dengan animasi
- **Katalog Obat** - Browse obat dengan search dan filter
- **Product Details** - Modal detail produk yang informatif
- **Contact Information** - Info lengkap apotek dengan maps
- **WhatsApp Integration** - Konsultasi langsung via WhatsApp
- **Responsive Design** - Perfect di desktop, tablet, dan mobile

---

## 🛠️ Teknologi

### Backend
- **Laravel 10.x** - PHP Framework
- **Filament 3.0** - Admin Panel
- **MySQL 8.0+** - Database
- **PHP 8.1+** - Programming Language

### Frontend
- **Blade Templates** - Laravel Templating
- **Modern CSS** - CSS Variables, Grid, Flexbox
- **Vanilla JavaScript** - ES6+ dengan Class-based architecture
- **Font Awesome** - Icon library
- **Google Fonts** - Typography (Inter)

### Libraries & Packages
- **DomPDF** - PDF Generation
- **Maatwebsite Excel** - Excel Export/Import
- **Simple QR Code** - QR Code generation
- **Carbon** - Date manipulation
- **Intervention Image** - Image processing

---

## 📋 Persyaratan Sistem

### Minimum Requirements
- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 16.x (optional, untuk development)
- **MySQL** >= 8.0 atau **MariaDB** >= 10.3
- **Apache** atau **Nginx**
- **Extension PHP**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD

### Recommended
- **PHP** 8.2+
- **MySQL** 8.0+
- **Memory** 512MB+
- **Storage** 1GB+ free space

---

## 🔧 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/apotek-puri-pasir-putih.git
cd apotek-puri-pasir-putih
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (optional)
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Migration & Seeding
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE apotek_db"

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7. Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Default Users
Setelah seeding, gunakan akun berikut:

**Admin:**
- Email: `admin@apotek.com`
- Password: `password`

**Kasir:**
- Email: `kasir@apotek.com`
- Password: `password`

### File Upload Configuration
Edit `config/filesystems.php` untuk konfigurasi upload:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

### PDF Configuration
Edit `config/dompdf.php` untuk konfigurasi PDF:
```php
'convert_entities' => true,
'default_font' => 'DejaVu Sans',
```

---

## 📖 Penggunaan

### Akses Sistem

| Role | URL | Deskripsi |
|------|-----|-----------|
| Admin | `/admin` | Panel admin dengan Filament |
| Kasir | `/kasir` | Interface kasir untuk transaksi |
| Public | `/pelanggan` | Halaman publik untuk pelanggan |

### Workflow Transaksi

1. **Login sebagai Kasir** di `/kasir`
2. **Cari Obat** menggunakan search box
3. **Tambah ke Keranjang** dengan quantity yang diinginkan
4. **Input Data Pelanggan** (opsional)
5. **Pilih Metode Pembayaran** (Tunai/QRIS)
6. **Proses Pembayaran** dan cetak struk
7. **Stok Otomatis Terupdate**

### Generate Laporan

1. **Login sebagai Admin** di `/admin`
2. **Pilih Menu Laporan** (Penjualan/Pembelian)
3. **Set Filter Tanggal** sesuai kebutuhan
4. **Export ke PDF/Excel** dengan berbagai format
5. **Download File** akan otomatis dimulai

---

## 🔌 API Documentation

### Endpoints Utama

#### Obat API
```http
GET /pelanggan/obat
```
Response:
```json
[
  {
    "id": 1,
    "kode_obat": "OBT001",
    "nama_obat": "Paracetamol 500mg",
    "kategori": "Obat Bebas",
    "harga": 5000,
    "stok": 100,
    "deskripsi": "Obat penurun panas dan pereda nyeri"
  }
]
```

#### Kasir API
```http
POST /kasir/checkout
Content-Type: application/json

{
  "customerName": "John Doe",
  "paymentMethod": "Tunai",
  "amountReceived": 50000,
  "orderItems": [
    {
      "id": 1,
      "quantity": 2,
      "price": 5000
    }
  ]
}
```

#### Export PDF
```http
GET /export/penjualan-pdf?dari_tanggal=2024-01-01&sampai_tanggal=2024-01-31
```

---

## 📸 Screenshots

### Admin Dashboard
![Admin Dashboard](docs/images/admin-dashboard.png)

### Kasir Interface
![Kasir Interface](docs/images/kasir-interface.png)

### Public Page
![Public Page](docs/images/public-page.png)

### Mobile Responsive
![Mobile View](docs/images/mobile-view.png)

---

## 🐛 Troubleshooting

### Common Issues

#### 1. Error "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

#### 2. Storage Link Error
```bash
php artisan storage:link
```

#### 3. Permission Denied (Linux/Mac)
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 4. Database Connection Error
- Pastikan MySQL service berjalan
- Cek konfigurasi `.env`
- Pastikan database sudah dibuat

#### 5. PDF Export Error
```bash
# Install required fonts
sudo apt-get install php-gd php-mbstring
```

#### 6. UTF-8 Encoding Issues
Sistem sudah dilengkapi dengan UTF-8 safe functions:
- `clean_utf8()` - Membersihkan string
- `safe_json_response()` - JSON response yang aman

### Debug Mode
Untuk development, aktifkan debug mode di `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

### Log Files
Check log files di `storage/logs/laravel.log` untuk error details.

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari developer lain! Berikut cara berkontribusi:

### 1. Fork Repository
```bash
git fork https://github.com/username/apotek-puri-pasir-putih.git
```

### 2. Create Feature Branch
```bash
git checkout -b feature/amazing-feature
```

### 3. Commit Changes
```bash
git commit -m 'Add some amazing feature'
```

### 4. Push to Branch
```bash
git push origin feature/amazing-feature
```

### 5. Open Pull Request
Buat Pull Request dengan deskripsi yang jelas tentang perubahan yang dibuat.

### Coding Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments for complex logic
- Write tests for new features

---

## 📁 Struktur Proyek

```
apotek/
├── app/
│   ├── Filament/           # Filament admin resources
│   ├── Http/
│   │   ├── Controllers/    # Controllers
│   │   └── Middleware/     # Custom middleware
│   ├── Models/             # Eloquent models
│   ├── Exports/            # PDF/Excel export classes
│   └── Helpers/            # Helper classes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── public/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Static images
├── resources/
│   └── views/             # Blade templates
├── routes/
│   └── web.php            # Web routes
└── storage/
    ├── app/public/        # Uploaded files
    └── logs/              # Log files
```

---

## 🔒 Security

### Implemented Security Features
- **Authentication** - Laravel Sanctum
- **Authorization** - Role-based access control
- **CSRF Protection** - Built-in Laravel CSRF
- **XSS Prevention** - Input sanitization
- **SQL Injection Prevention** - Eloquent ORM
- **UTF-8 Safe** - Custom UTF-8 cleaning functions

### Security Best Practices
- Regularly update dependencies
- Use strong passwords
- Enable HTTPS in production
- Regular database backups
- Monitor log files

---

## 🚀 Deployment

### Production Deployment

#### 1. Server Requirements
- PHP 8.1+ with required extensions
- MySQL 8.0+
- Nginx/Apache
- SSL Certificate (recommended)

#### 2. Environment Configuration
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

#### 3. Optimization Commands
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

#### 4. Web Server Configuration

**Nginx Example:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/apotek/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 📊 Performance

### Optimization Tips
- Use Redis for caching (optional)
- Optimize images before upload
- Enable gzip compression
- Use CDN for static assets
- Regular database optimization

### Monitoring
- Monitor server resources
- Check application logs
- Database query optimization
- Regular backups

---

## 📝 Changelog

### Version 1.0.0 (2024-01-15)
- ✨ Initial release
- 🎨 Modern UI design
- 🔐 Multi-role authentication
- 📊 Comprehensive reporting
- 📱 Mobile responsive design
- 🛡️ UTF-8 safe operations

### Version 1.1.0 (2024-01-20)
- 🚀 Performance improvements
- 🐛 Bug fixes for PDF export
- ✨ Enhanced search functionality
- 📱 Better mobile experience

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

```
MIT License

Copyright (c) 2024 Apotek Puri Pasir Putih

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 📞 Kontak

### Developer
- **Name**: Fuad
- **Email**: fuad@example.com
- **GitHub**: [@fuad](https://github.com/fuad)

### Apotek Puri Pasir Putih
- **Address**: Jl. Pasir Putih Ruko Villagio Residence, Depok
- **Phone**: 082311323121
- **Email**: info@apotekpuripasirputih.com

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Filament](https://filamentphp.com) - Admin Panel
- [Font Awesome](https://fontawesome.com) - Icons
- [Google Fonts](https://fonts.google.com) - Typography
- [DomPDF](https://github.com/dompdf/dompdf) - PDF Generation

---

<div align="center">

**⭐ Jika proyek ini membantu, berikan star di GitHub! ⭐**

Made with ❤️ by [Fuad](https://github.com/fuad)

</div>