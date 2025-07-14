# Deployment Guide untuk Render.com

## Prerequisites
- Akun Render.com
- Repository GitHub yang sudah terhubung
- Database PostgreSQL (akan dibuat otomatis oleh Render)

## Langkah Deployment

### 1. Push ke GitHub
```bash
git add .
git commit -m "Prepare for deployment"
git push origin main
```

### 2. Deploy di Render.com

1. Login ke [Render.com](https://render.com)
2. Klik "New +" dan pilih "Blueprint"
3. Connect repository GitHub `Fuadhsnn/apotekpuri`
4. Render akan otomatis mendeteksi `render.yaml` dan membuat:
   - Web Service (Laravel app)
   - PostgreSQL Database

### 3. Environment Variables

Render akan otomatis mengatur environment variables dari `render.yaml`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (akan di-generate otomatis)
- Database credentials (dari PostgreSQL service)

### 4. Post-Deployment Setup

Setelah deployment selesai, jalankan migrasi database:

```bash
# Di Render Dashboard > Web Service > Shell
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Custom Domain (Optional)

1. Di Render Dashboard, pilih Web Service
2. Klik "Settings" > "Custom Domains"
3. Tambahkan domain yang diinginkan

## Troubleshooting

### Database Connection Error
- Pastikan database service sudah running
- Cek environment variables di Render Dashboard

### 500 Error
- Cek logs di Render Dashboard
- Pastikan `APP_KEY` sudah di-generate
- Jalankan `php artisan config:clear`

### File Upload Issues
- Pastikan storage sudah di-link: `php artisan storage:link`
- Cek permissions folder storage

## Monitoring

- Monitor logs di Render Dashboard
- Set up alerts untuk downtime
- Monitor database performance

## Backup

- Database backup otomatis di Render
- Backup code di GitHub
- Backup uploads di external storage (optional) 