# Integrasi SatuSehat API

Dokumentasi ini menjelaskan cara menggunakan fitur integrasi SatuSehat API untuk menambahkan data obat ke database aplikasi apotek.

## Konfigurasi

1. Tambahkan konfigurasi SatuSehat API di file `.env`:

```
SATUSEHAT_BASE_URL=https://api-satusehat.kemkes.go.id
SATUSEHAT_CLIENT_ID=your_client_id_here
SATUSEHAT_CLIENT_SECRET=your_client_secret_here
SATUSEHAT_ORGANIZATION_ID=your_organization_id_here
```

> **Catatan**: Anda perlu mendaftar sebagai developer di [SatuSehat](https://satusehat.kemkes.go.id/) untuk mendapatkan kredensial API.

## Menggunakan Fitur Import Obat dari SatuSehat

### Melalui Panel Admin Filament

1. Login ke panel admin Filament
2. Buka menu "Import Obat SatuSehat" di bagian "Manajemen Obat"
3. Klik tombol "Import Obat dari SatuSehat"
4. Masukkan kata kunci untuk mencari obat di database SatuSehat
5. Pilih obat dari hasil pencarian
6. Isi informasi tambahan seperti kategori, jenis obat, stok, harga beli, harga jual, dan tanggal kadaluarsa
7. Klik tombol "Create" untuk menambahkan obat ke database

### Melalui API

Aplikasi ini juga menyediakan endpoint API untuk mengintegrasikan SatuSehat:

#### Mencari Obat

```
GET /api/satusehat/medications?search={keyword}&limit={limit}
```

Parameter:
- `search`: Kata kunci pencarian (opsional)
- `limit`: Jumlah maksimum hasil yang ditampilkan (opsional, default: 10)

#### Mendapatkan Detail Obat

```
GET /api/satusehat/medications/{id}
```

Parameter:
- `id`: ID obat dari SatuSehat

#### Mengimpor Obat ke Database

```
POST /api/satusehat/medications/import
```

Body Request (JSON):
```json
{
  "medication_id": "id_obat_dari_satusehat",
  "stok": 100,
  "harga_beli": 10000,
  "harga_jual": 15000,
  "tanggal_kadaluarsa": "2025-12-31",
  "kategori": "antibiotik",
  "jenis_obat": "obat_keras"
}
```

## Struktur Data

Data obat yang diimpor dari SatuSehat akan disimpan dengan struktur berikut:

- `kode_obat`: Kode obat yang digenerate otomatis berdasarkan kategori, tahun, dan nomor urut
- `nama_obat`: Nama obat dari SatuSehat
- `deskripsi`: Deskripsi obat dari SatuSehat (biasanya berisi informasi bentuk sediaan)
- `kategori`: Kategori obat yang dipilih saat import
- `jenis_obat`: Jenis obat yang dipilih saat import
- `stok`: Jumlah stok awal
- `harga_beli`: Harga beli obat
- `harga_jual`: Harga jual obat
- `tanggal_kadaluarsa`: Tanggal kadaluarsa obat

## Troubleshooting

### Tidak Dapat Terhubung ke API SatuSehat

1. Pastikan kredensial API sudah benar di file `.env`
2. Periksa koneksi internet
3. Pastikan server SatuSehat sedang aktif
4. Periksa log aplikasi di `storage/logs/laravel.log` untuk informasi error lebih detail

### Obat Tidak Ditemukan

1. Coba gunakan kata kunci yang lebih umum
2. Pastikan obat tersebut terdaftar di database SatuSehat

## Referensi

- [Dokumentasi API SatuSehat](https://satusehat.kemkes.go.id/platform/docs)
- [FHIR R4 Medication Resource](https://www.hl7.org/fhir/medication.html)