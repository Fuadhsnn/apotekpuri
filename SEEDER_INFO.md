# Database Seeder - Obat Indonesia

## ObatSeeder - Data Obat Lengkap Indonesia

Seeder ini berisi **80 jenis obat** yang umum digunakan di Indonesia, lengkap dengan:

### Kategori Obat yang Tersedia:

1. **Analgesik** (Pereda Nyeri)
   - Paracetamol, Ibuprofen, Asam Mefenamat
   - Diklofenak, Bodrex, Paramex

2. **Antibiotik** 
   - Amoxicillin, Cefixime, Ciprofloxacin
   - Azithromycin

3. **Vitamin & Suplemen**
   - Vitamin C, Vitamin B Complex, Vitamin D3
   - Multivitamin, Zinc, Calcium, Omega-3, Magnesium

4. **Obat Topikal** (Kulit)
   - Clotrimazole, Gentamicin, Miconazole
   - Betamethasone, Betadine, Rivanol

5. **Gastrointestinal** (Pencernaan)
   - Omeprazole, Loperamide, Ranitidine
   - Domperidone, Entrostop, Diapet, Mylanta, Promag

6. **Respirasi** (Pernapasan)
   - Salbutamol, CTM, Ambroxol
   - Dextromethorphan, OBH Combi, Woods, Decolgen

7. **Herbal**
   - Tolak Angin, Antangin JRG, Temulawak
   - Madu TJ, Kuku Bima, Extra Joss

8. **Kardiovaskular**
   - Amlodipine, Simvastatin, Captopril
   - Atorvastatin

9. **Endokrin** (Diabetes)
   - Metformin, Glibenclamide, Gliclazide
   - Insulin Regular

10. **Oftalmologi** (Mata)
    - Chloramphenicol, Artificial Tears, Dexamethasone
    - Insto, Visine, Cendo Xitrol

11. **Dermatologi** (Kulit)
    - Hydrocortisone, Caladine, Mupirocin
    - Scabimite, Calamine, Fenistil, Benzolac, Acnes

12. **Antiviral**
    - Acyclovir, Oseltamivir

13. **Obat Anak**
    - Tempra Sirup, Proris Sirup, Combantrin Sirup

14. **Obat Khusus**
    - Antimo (mabuk perjalanan), Dramamine
    - Combantrin (cacing), Boraginol (wasir)
    - Lelap (tidur herbal)

### Jenis Obat Berdasarkan Regulasi:
- **Obat Bebas**: 45 item
- **Obat Bebas Terbatas**: 15 item  
- **Obat Keras**: 20 item

### Informasi Setiap Obat:
- Kode obat unik (format: KATEGORI-TAHUN-NOMOR)
- Nama obat lengkap dengan dosis
- Deskripsi dan kegunaan
- Kategori obat
- Jenis obat (bebas/bebas terbatas/keras)
- Stok awal (150-600 unit)
- Harga beli dan jual
- Tanggal kadaluarsa (1-3 tahun dari sekarang)

### Cara Menjalankan Seeder:

```bash
# Jalankan semua seeder
php artisan db:seed

# Atau jalankan hanya ObatSeeder
php artisan db:seed --class=ObatSeeder

# Reset dan jalankan ulang
php artisan migrate:fresh --seed
```

### Rentang Harga:
- **Obat Murah**: Rp 2.000 - Rp 10.000
- **Obat Sedang**: Rp 10.000 - Rp 25.000  
- **Obat Mahal**: Rp 25.000 - Rp 150.000

### Brand Obat Indonesia Terkenal:
- Kimia Farma, Kalbe Farma, Tempo Scan Pacific
- Dexa Medica, Sanbe Farma, Indofarma
- Combiphar, Pharos, Bernofarm

Data ini sangat cocok untuk:
- Demo sistem apotek
- Training aplikasi farmasi
- Testing fitur manajemen obat
- Simulasi penjualan apotek

**Total: 80 obat dengan data lengkap dan realistis untuk pasar Indonesia**