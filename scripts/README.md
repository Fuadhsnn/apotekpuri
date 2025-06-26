# Script Impor Otomatis Data Obat dari SatuSehat

Folder ini berisi script untuk mengimpor data obat dari SatuSehat API secara otomatis ke database aplikasi apotek.

## Persiapan

Sebelum menggunakan script ini, pastikan:

1. Aplikasi apotek sudah terkonfigurasi dengan benar
2. Kredensial SatuSehat API sudah diatur di file `.env`:
   ```
   SATUSEHAT_BASE_URL=https://api-satusehat.kemkes.go.id
   SATUSEHAT_CLIENT_ID=your_client_id_here
   SATUSEHAT_CLIENT_SECRET=your_client_secret_here
   SATUSEHAT_ORGANIZATION_ID=your_organization_id_here
   ```
3. Database sudah siap dan dapat diakses

## Cara Penggunaan

### Menggunakan Artisan Command

```bash
php artisan satusehat:import-medications [jumlah] [--category=kategori1] [--category=kategori2] ...
```

Parameter:
- `jumlah`: Jumlah obat yang akan diimpor (default: 100)
- `--category`: Kategori obat yang akan diimpor (dapat diulang untuk beberapa kategori)

Kategori yang tersedia:
- antibiotik
- vitamin
- analgesik
- antipiretik
- antihistamin
- lainnya

Contoh:
```bash
php artisan satusehat:import-medications 50 --category=antibiotik --category=vitamin
```

### Menggunakan Script Helper

#### Windows

```bash
scripts\import_medications.bat [jumlah] [kategori1,kategori2,...]
```

Contoh:
```bash
scripts\import_medications.bat 50 antibiotik,vitamin
```

#### Linux/Mac

```bash
./scripts/import_medications.sh [jumlah] [kategori1,kategori2,...]
```

Contoh:
```bash
./scripts/import_medications.sh 50 antibiotik,vitamin
```

### Menggunakan PHP Script

```bash
php scripts/import_medications.php [jumlah] [kategori1,kategori2,...]
```

Contoh:
```bash
php scripts/import_medications.php 50 antibiotik,vitamin
```

## Penjadwalan Otomatis

### Menggunakan Cron Job (Linux/Mac)

Tambahkan baris berikut ke crontab untuk menjalankan impor setiap hari pada pukul 01:00 pagi:

```
0 1 * * * cd /path/to/apotek-app && php artisan satusehat:import-medications 100
```

### Menggunakan Task Scheduler (Windows)

1. Buka Task Scheduler
2. Buat tugas baru
3. Atur trigger untuk berjalan setiap hari pada pukul 01:00 pagi
4. Atur action untuk menjalankan `cmd.exe` dengan argumen:
   ```
   /c cd /d "C:\path\to\apotek-app" && php artisan satusehat:import-medications 100
   ```

## Catatan

- Script ini akan menghasilkan data obat dengan nilai acak untuk stok, harga beli, harga jual, dan tanggal kadaluarsa
- Jika jumlah obat yang diminta melebihi jumlah obat yang tersedia di SatuSehat API, script akan mengimpor sebanyak yang tersedia
- Log impor dapat dilihat di file `storage/logs/laravel.log`