# Database Seeders

File ini berisi instruksi untuk menggunakan database seeder yang telah dibuat untuk menambahkan data secara otomatis ke dalam database aplikasi apotek.

## Cara Menggunakan Seeder

### 1. Menjalankan Semua Seeder

Untuk menjalankan semua seeder termasuk ObatSeeder, gunakan perintah berikut:

```bash
php artisan db:seed
```

Perintah ini akan menjalankan `DatabaseSeeder` yang akan memanggil semua seeder yang terdaftar, termasuk `ObatSeeder` dan `SupplierSeeder`.

### 2. Menjalankan Seeder Tertentu

Jika Anda hanya ingin menjalankan seeder tertentu tanpa seeder lainnya, gunakan perintah berikut:

```bash
php artisan db:seed --class=ObatSeeder
```

Atau untuk menjalankan SupplierSeeder saja:

```bash
php artisan db:seed --class=SupplierSeeder
```

## Informasi Seeder

### ObatSeeder

ObatSeeder akan menambahkan 100 data obat dengan informasi berikut:

- **Kode Obat**: Dibuat otomatis berdasarkan kategori dan tahun saat ini
- **Nama Obat**: Kombinasi nama generik dan kekuatan dosis (tanpa merek)
- **Deskripsi**: Deskripsi singkat tentang obat tersebut
- **Kategori**: Dipilih secara acak dari: antibiotik, vitamin, analgesik, antipiretik, antihistamin, lainnya
- **Jenis Obat**: Dipilih secara acak dari: obat_bebas, obat_bebas_terbatas, obat_keras, narkotika
- **Stok**: Nilai acak antara 10-1000
- **Harga Beli**: Nilai acak antara Rp 1.000 - Rp 100.000
- **Harga Jual**: Harga beli + margin acak antara 10-50%
- **Tanggal Kadaluarsa**: Tanggal acak 1-3 tahun dari sekarang

### SupplierSeeder

SupplierSeeder akan menambahkan 20 data supplier dengan informasi berikut:

- **Nama Supplier**: Nama perusahaan farmasi yang umum di Indonesia
- **Alamat**: Alamat lengkap dengan format jalan, nomor, dan kota
- **Telepon**: Nomor telepon dengan format 08xx-xxxx-xxxx
- **Email**: Email dengan format info@namasupplier.co.id
- **Keterangan**: Deskripsi singkat tentang supplier tersebut

## Kustomisasi

Jika Anda ingin mengubah jumlah data atau nilai-nilai yang digunakan, Anda dapat mengedit file seeder sesuai kebutuhan:

- Untuk data obat, edit file `ObatSeeder.php`
- Untuk data supplier, edit file `SupplierSeeder.php`

## Catatan Penting

- Seeder akan menghapus data yang tidak memiliki relasi dengan tabel lain sebelum menambahkan data baru
- Untuk data yang memiliki relasi, seeder akan mengupdate data tersebut tanpa menghapusnya
- Pastikan model sudah memiliki semua field yang diperlukan sebelum menjalankan seeder
- Pastikan database sudah dimigrasi sebelum menjalankan seeder