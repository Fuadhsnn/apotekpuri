# Modern Alert System - Kasir Apotek

## Overview
Sistem alert modern yang menggantikan alert JavaScript default dengan modal yang lebih menarik dan informatif.

## Jenis Alert yang Tersedia

### 1. 🎉 Success Alert
**Fungsi:** `showSuccessAlert(paymentMethod, penjualanId, total)`

**Kapan Muncul:**
- Setelah transaksi berhasil diproses
- Menampilkan detail transaksi lengkap
- Menyediakan opsi cetak struk dan lanjut transaksi

**Fitur:**
- ✅ Icon sukses dengan animasi pulse
- 💰 Detail pembayaran (total, metode, waktu)
- 📱 Informasi khusus untuk QRIS
- 🖨️ Tombol cetak struk
- ➡️ Tombol lanjut transaksi
- ⏰ Auto close setelah 10 detik

### 2. ❌ Error Alert
**Fungsi:** `showErrorAlert(message)`

**Kapan Muncul:**
- Ketika transaksi gagal
- Error koneksi atau server
- Popup browser diblokir

**Fitur:**
- ❌ Icon error dengan gradient merah
- 📝 Pesan error yang jelas
- 🔄 Tombol coba lagi

### 3. ⚠️ Warning Alert
**Fungsi:** `showWarningAlert(title, message)`

**Kapan Muncul:**
- Keranjang kosong saat checkout
- Pembayaran tidak lengkap
- Uang tidak cukup
- Validasi form gagal

**Fitur:**
- ⚠️ Icon warning dengan warna orange
- 📋 Judul dan pesan yang informatif
- ✅ Tombol "Mengerti"
- ⏰ Auto close setelah 5 detik

### 4. ℹ️ Info Alert
**Fungsi:** `showInfoAlert(title, message)`

**Kapan Muncul:**
- Informasi umum untuk user
- Tips penggunaan
- Notifikasi sistem

**Fitur:**
- ℹ️ Icon info dengan warna biru
- 📝 Informasi yang mudah dipahami
- ✅ Tombol "OK"

### 5. ⏳ Loading Alert
**Fungsi:** `showLoadingAlert(message)`

**Kapan Muncul:**
- Saat memproses pembayaran
- Loading data
- Operasi yang membutuhkan waktu

**Fitur:**
- 🔄 Spinner animasi
- 📝 Pesan loading yang dapat dikustomisasi
- 🚫 Tidak bisa ditutup manual (harus via code)

## Contoh Penggunaan

### Success Alert
```javascript
// Otomatis dipanggil setelah checkout berhasil
showSuccessAlert('Tunai', 123, 50000);
```

### Error Alert
```javascript
showErrorAlert('Koneksi terputus. Silahkan coba lagi.');
```

### Warning Alert
```javascript
showWarningAlert('Keranjang Kosong', 'Silahkan tambahkan obat ke keranjang terlebih dahulu.');
```

### Info Alert
```javascript
showInfoAlert('Tips', 'Gunakan fitur pencarian untuk menemukan obat dengan cepat.');
```

### Loading Alert
```javascript
// Tampilkan loading
showLoadingAlert('Memproses pembayaran...');

// Tutup loading setelah selesai
closeLoadingAlert();
```

## Fitur Animasi

### 1. **Slide In Animation**
- Modal muncul dengan efek scale dan slide dari bawah
- Menggunakan cubic-bezier untuk animasi yang smooth

### 2. **Icon Pulse**
- Icon beranimasi pulse untuk menarik perhatian
- Berbeda untuk setiap jenis alert

### 3. **Button Hover Effects**
- Tombol naik sedikit saat di-hover
- Shadow yang lebih dalam untuk efek depth

### 4. **Backdrop Blur**
- Background blur untuk fokus pada modal
- Overlay dengan transparansi yang elegan

## Responsive Design

### Desktop
- Modal berukuran optimal (max-width: 500px)
- Button horizontal dengan gap yang nyaman
- Icon berukuran 80px

### Mobile
- Modal menyesuaikan lebar layar
- Button menjadi vertikal (stack)
- Icon mengecil menjadi 60px
- Padding yang disesuaikan

## Customization

### Warna Theme
```css
:root {
    --success-color: #4CAF50;
    --error-color: #dc3545;
    --warning-color: #ff9800;
    --info-color: #2196F3;
}
```

### Durasi Animasi
```css
.success-alert-modal {
    animation: modalSlideIn 0.5s ease-out forwards;
}
```

### Auto Close Timer
```javascript
// Success alert: 10 detik
// Warning alert: 5 detik
// Error & Info: Manual close
```

## Browser Support
- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+

## Performance
- 🚀 Lightweight (< 5KB CSS + JS)
- 🎯 No external dependencies
- 💾 Minimal DOM manipulation
- 🔄 Efficient cleanup

## Accessibility
- ♿ Keyboard navigation support
- 🔊 Screen reader friendly
- 🎨 High contrast colors
- 📱 Touch-friendly buttons

---

**Catatan:** Alert system ini menggantikan semua `alert()`, `confirm()`, dan `prompt()` JavaScript default untuk pengalaman user yang lebih modern dan konsisten.