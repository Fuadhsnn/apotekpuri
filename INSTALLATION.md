# 🔧 Panduan Instalasi Lengkap

## Persyaratan Sistem

### Minimum Requirements
- **PHP** >= 8.1
- **Composer** >= 2.0
- **MySQL** >= 8.0 atau **MariaDB** >= 10.3
- **Apache** atau **Nginx**
- **Memory** 256MB+
- **Storage** 500MB+ free space

### PHP Extensions Required
```
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD
- Zip
```

## Instalasi Step by Step

### 1. Persiapan Environment

#### Windows (XAMPP)
1. Download dan install [XAMPP](https://www.apachefriends.org/)
2. Start Apache dan MySQL dari XAMPP Control Panel
3. Download dan install [Composer](https://getcomposer.org/)

#### Linux (Ubuntu/Debian)
```bash
# Update package list
sudo apt update

# Install PHP and extensions
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath

# Install MySQL
sudo apt install mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### macOS
```bash
# Install Homebrew (if not installed)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php@8.1

# Install MySQL
brew install mysql

# Install Composer
brew install composer
```

### 2. Download Project

#### Option A: Git Clone
```bash
git clone https://github.com/username/apotek-puri-pasir-putih.git
cd apotek-puri-pasir-putih
```

#### Option B: Download ZIP
1. Download ZIP dari GitHub
2. Extract ke folder web server (htdocs/www)
3. Rename folder sesuai kebutuhan

### 3. Install Dependencies

```bash
# Masuk ke direktori project
cd apotek-puri-pasir-putih

# Install PHP dependencies
composer install

# Jika ada error, coba:
composer install --ignore-platform-reqs
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env` sesuai konfigurasi:

```env
APP_NAME="Apotek Puri Pasir Putih"
APP_ENV=local
APP_KEY=base64:generated_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apotek_db
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Database Setup

#### Create Database
```bash
# Login ke MySQL
mysql -u root -p

# Create database
CREATE DATABASE apotek_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user (optional)
CREATE USER 'apotek_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON apotek_db.* TO 'apotek_user'@'localhost';
FLUSH PRIVILEGES;

# Exit MySQL
EXIT;
```

#### Run Migrations
```bash
# Run database migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 6. Storage Configuration

```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac only)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# For production (Linux/Mac)
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 7. Additional Configuration

#### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### Optimize for Production (Optional)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

### 8. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# Custom host and port
php artisan serve --host=0.0.0.0 --port=8080
```

## Verifikasi Instalasi

### 1. Check Application
- Buka browser: `http://localhost:8000`
- Pastikan halaman loading tanpa error

### 2. Check Admin Panel
- URL: `http://localhost:8000/admin`
- Login: `admin@apotek.com` / `password`

### 3. Check Kasir Interface
- URL: `http://localhost:8000/kasir`
- Login: `kasir@apotek.com` / `password`

### 4. Check Public Page
- URL: `http://localhost:8000/pelanggan`
- Pastikan produk loading dengan benar

## Troubleshooting

### Common Issues

#### 1. Composer Install Error
```bash
# Update composer
composer self-update

# Clear composer cache
composer clear-cache

# Install with ignore platform requirements
composer install --ignore-platform-reqs
```

#### 2. Database Connection Error
- Pastikan MySQL service berjalan
- Check username/password di `.env`
- Pastikan database sudah dibuat
- Test koneksi: `php artisan tinker` → `DB::connection()->getPdo();`

#### 3. Permission Denied
```bash
# Linux/Mac
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache

# Windows (run as administrator)
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

#### 4. Storage Link Error
```bash
# Remove existing link
rm public/storage

# Create new link
php artisan storage:link

# Manual link (if command fails)
ln -s ../storage/app/public public/storage
```

#### 5. Key Generation Error
```bash
# Generate new key
php artisan key:generate --force

# Manual key generation
php -r "echo base64_encode(random_bytes(32));"
```

#### 6. Migration Error
```bash
# Reset migrations
php artisan migrate:reset

# Fresh migration with seed
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

### Environment Specific Issues

#### XAMPP Windows
- Pastikan Apache dan MySQL running
- Check port conflicts (80, 3306)
- Disable Windows Defender real-time protection sementara

#### Linux Production
```bash
# Install additional packages
sudo apt install php8.1-intl php8.1-imagick

# Configure Apache/Nginx
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### macOS
```bash
# Fix permission issues
sudo dseditgroup -o edit -a $(whoami) -t user _www

# Start services
brew services start mysql
brew services start php@8.1
```

## Performance Optimization

### 1. PHP Configuration
Edit `php.ini`:
```ini
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
```

### 2. MySQL Configuration
Edit `my.cnf`:
```ini
[mysqld]
innodb_buffer_pool_size = 256M
query_cache_size = 64M
max_connections = 100
```

### 3. Laravel Optimization
```bash
# Production optimization
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Security Checklist

- [ ] Change default passwords
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure proper file permissions
- [ ] Enable HTTPS in production
- [ ] Regular security updates
- [ ] Backup database regularly
- [ ] Monitor log files

## Next Steps

1. **Customize Configuration** - Sesuaikan setting aplikasi
2. **Add Sample Data** - Tambah data obat dan kategori
3. **Test All Features** - Verifikasi semua fungsi bekerja
4. **Setup Backup** - Konfigurasi backup otomatis
5. **Deploy to Production** - Setup server production

## Support

Jika mengalami masalah instalasi:

1. Check log files: `storage/logs/laravel.log`
2. Enable debug mode: `APP_DEBUG=true`
3. Check system requirements
4. Consult documentation
5. Contact support

---

**Happy Coding! 🚀**