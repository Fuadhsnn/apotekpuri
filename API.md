# 🔌 API Documentation

## Overview

Apotek Puri Pasir Putih menyediakan RESTful API untuk integrasi dengan sistem eksternal dan pengembangan aplikasi mobile.

### Base URL
```
http://localhost:8000/api
```

### Authentication
API menggunakan Laravel Sanctum untuk authentication. Untuk endpoint yang memerlukan authentication, sertakan token di header:

```http
Authorization: Bearer {your-token}
```

### Response Format
Semua response menggunakan format JSON dengan struktur standar:

```json
{
    "success": true,
    "data": {},
    "message": "Success message",
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0.0"
    }
}
```

### Error Response
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "nama_obat": ["The nama obat field is required."]
        }
    },
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0.0"
    }
}
```

---

## 📋 Endpoints

### Authentication

#### Login
```http
POST /api/auth/login
```

**Request Body:**
```json
{
    "email": "admin@apotek.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@apotek.com",
            "role": "admin"
        },
        "token": "1|abc123def456...",
        "expires_at": "2024-01-16T10:30:00Z"
    },
    "message": "Login successful"
}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

#### User Profile
```http
GET /api/auth/user
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Admin",
        "email": "admin@apotek.com",
        "role": "admin",
        "created_at": "2024-01-01T00:00:00Z"
    }
}
```

---

### 💊 Obat (Medicine)

#### Get All Medicines
```http
GET /api/obat
```

**Query Parameters:**
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 100)
- `search` (string): Search by name or code
- `kategori` (string): Filter by category
- `stok_min` (integer): Minimum stock filter

**Example:**
```http
GET /api/obat?search=paracetamol&kategori=obat-bebas&page=1&per_page=20
```

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "kode_obat": "OBT001",
                "nama_obat": "Paracetamol 500mg",
                "deskripsi": "Obat penurun panas dan pereda nyeri",
                "kategori": "Obat Bebas",
                "stok": 100,
                "harga_beli": 3000,
                "harga_jual": 5000,
                "tanggal_kadaluarsa": "2025-12-31",
                "gambar": "obat/paracetamol.jpg",
                "created_at": "2024-01-01T00:00:00Z",
                "updated_at": "2024-01-01T00:00:00Z"
            }
        ],
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 95
    }
}
```

#### Get Single Medicine
```http
GET /api/obat/{id}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "kode_obat": "OBT001",
        "nama_obat": "Paracetamol 500mg",
        "deskripsi": "Obat penurun panas dan pereda nyeri",
        "kategori": "Obat Bebas",
        "stok": 100,
        "harga_beli": 3000,
        "harga_jual": 5000,
        "tanggal_kadaluarsa": "2025-12-31",
        "gambar": "obat/paracetamol.jpg",
        "supplier": "PT. Kimia Farma",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z"
    }
}
```

#### Create Medicine
```http
POST /api/obat
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "kode_obat": "OBT002",
    "nama_obat": "Ibuprofen 400mg",
    "deskripsi": "Anti-inflamasi non-steroid",
    "kategori": "Obat Bebas",
    "stok": 50,
    "harga_beli": 4000,
    "harga_jual": 7000,
    "tanggal_kadaluarsa": "2025-12-31",
    "supplier": "PT. Kalbe Farma"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "kode_obat": "OBT002",
        "nama_obat": "Ibuprofen 400mg",
        // ... other fields
    },
    "message": "Medicine created successfully"
}
```

#### Update Medicine
```http
PUT /api/obat/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** (Same as create, all fields optional)

#### Delete Medicine
```http
DELETE /api/obat/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Medicine deleted successfully"
}
```

---

### 🛒 Penjualan (Sales)

#### Get Sales
```http
GET /api/penjualan
Authorization: Bearer {token}
```

**Query Parameters:**
- `dari_tanggal` (date): Start date (YYYY-MM-DD)
- `sampai_tanggal` (date): End date (YYYY-MM-DD)
- `metode_pembayaran` (string): Payment method filter
- `page` (integer): Page number

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "nomor_nota": "PJ-20240115-001",
                "tanggal_penjualan": "2024-01-15",
                "nama_pelanggan": "John Doe",
                "total_harga": 15000,
                "metode_pembayaran": "Tunai",
                "bayar": 20000,
                "kembalian": 5000,
                "user": {
                    "id": 2,
                    "name": "Kasir 1"
                },
                "details": [
                    {
                        "id": 1,
                        "obat_id": 1,
                        "jumlah": 2,
                        "harga": 5000,
                        "subtotal": 10000,
                        "obat": {
                            "nama_obat": "Paracetamol 500mg"
                        }
                    }
                ]
            }
        ],
        "current_page": 1,
        "total": 150
    }
}
```

#### Create Sale (Checkout)
```http
POST /api/penjualan
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "nama_pelanggan": "John Doe",
    "nama_dokter": "Dr. Smith",
    "nomor_resep": "RX001",
    "metode_pembayaran": "Tunai",
    "bayar": 25000,
    "items": [
        {
            "obat_id": 1,
            "jumlah": 2,
            "harga": 5000
        },
        {
            "obat_id": 2,
            "jumlah": 1,
            "harga": 7000
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nomor_nota": "PJ-20240115-001",
        "total_harga": 17000,
        "kembalian": 8000,
        "details": [
            // ... sale details
        ]
    },
    "message": "Sale completed successfully"
}
```

#### Get Sale Details
```http
GET /api/penjualan/{id}
Authorization: Bearer {token}
```

---

### 📊 Reports

#### Sales Report
```http
GET /api/reports/penjualan
Authorization: Bearer {token}
```

**Query Parameters:**
- `dari_tanggal` (date): Start date
- `sampai_tanggal` (date): End date
- `format` (string): Response format (json, pdf, excel)

**Response:**
```json
{
    "success": true,
    "data": {
        "summary": {
            "total_transaksi": 150,
            "total_pendapatan": 2500000,
            "total_item_terjual": 450,
            "rata_rata_transaksi": 16667
        },
        "transactions": [
            // ... transaction details
        ],
        "period": {
            "dari_tanggal": "2024-01-01",
            "sampai_tanggal": "2024-01-15"
        }
    }
}
```

#### Export Sales Report
```http
GET /api/reports/penjualan/export
Authorization: Bearer {token}
```

**Query Parameters:**
- `format` (string): pdf or excel
- `dari_tanggal` (date): Start date
- `sampai_tanggal` (date): End date

**Response:** File download

---

### 📈 Dashboard

#### Dashboard Statistics
```http
GET /api/dashboard/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "today": {
            "penjualan": 15,
            "pendapatan": 250000,
            "item_terjual": 45
        },
        "this_month": {
            "penjualan": 450,
            "pendapatan": 7500000,
            "item_terjual": 1350
        },
        "stock_alerts": {
            "low_stock": 5,
            "expired_soon": 2,
            "out_of_stock": 1
        },
        "top_products": [
            {
                "nama_obat": "Paracetamol 500mg",
                "total_terjual": 120
            }
        ]
    }
}
```

---

## 🔍 Search API

### Global Search
```http
GET /api/search
```

**Query Parameters:**
- `q` (string): Search query
- `type` (string): Search type (obat, penjualan, all)
- `limit` (integer): Result limit (default: 10)

**Response:**
```json
{
    "success": true,
    "data": {
        "obat": [
            {
                "id": 1,
                "nama_obat": "Paracetamol 500mg",
                "kode_obat": "OBT001",
                "stok": 100
            }
        ],
        "penjualan": [
            {
                "id": 1,
                "nomor_nota": "PJ-20240115-001",
                "nama_pelanggan": "John Doe"
            }
        ]
    }
}
```

---

## 📱 Mobile API

### Simplified Product List
```http
GET /api/mobile/products
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama": "Paracetamol 500mg",
            "harga": 5000,
            "stok": 100,
            "gambar": "https://example.com/storage/obat/paracetamol.jpg",
            "tersedia": true
        }
    ]
}
```

### Quick Checkout
```http
POST /api/mobile/checkout
Content-Type: application/json
```

**Request Body:**
```json
{
    "items": [
        {
            "id": 1,
            "qty": 2
        }
    ],
    "payment": "cash",
    "customer": "John Doe"
}
```

---

## 🚨 Error Codes

| Code | Description |
|------|-------------|
| `VALIDATION_ERROR` | Request validation failed |
| `UNAUTHORIZED` | Authentication required |
| `FORBIDDEN` | Insufficient permissions |
| `NOT_FOUND` | Resource not found |
| `INSUFFICIENT_STOCK` | Not enough stock available |
| `INVALID_PAYMENT` | Payment validation failed |
| `SERVER_ERROR` | Internal server error |

---

## 📝 Rate Limiting

API endpoints are rate limited:
- **Authenticated requests**: 1000 per hour
- **Unauthenticated requests**: 100 per hour
- **Search endpoints**: 200 per hour

Rate limit headers:
```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1642248000
```

---

## 🔒 Security

### CORS
API supports CORS for web applications. Configure allowed origins in `.env`:
```env
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://yourdomain.com
```

### API Versioning
Current version: `v1`

Future versions will be available at:
```
/api/v2/endpoint
```

### Input Validation
All inputs are validated and sanitized. Special characters are properly escaped to prevent XSS attacks.

---

## 📚 SDKs and Libraries

### JavaScript/TypeScript
```javascript
// Example usage
const api = new ApotekAPI('http://localhost:8000/api', 'your-token');

// Get products
const products = await api.obat.getAll({
    search: 'paracetamol',
    page: 1
});

// Create sale
const sale = await api.penjualan.create({
    nama_pelanggan: 'John Doe',
    items: [
        { obat_id: 1, jumlah: 2, harga: 5000 }
    ]
});
```

### PHP
```php
// Example usage
$api = new ApotekAPI('http://localhost:8000/api', 'your-token');

// Get products
$products = $api->obat()->getAll([
    'search' => 'paracetamol',
    'page' => 1
]);

// Create sale
$sale = $api->penjualan()->create([
    'nama_pelanggan' => 'John Doe',
    'items' => [
        ['obat_id' => 1, 'jumlah' => 2, 'harga' => 5000]
    ]
]);
```

---

## 🧪 Testing

### Postman Collection
Import our Postman collection for easy API testing:
```
https://api.postman.com/collections/apotek-puri-pasir-putih
```

### Example cURL Commands

**Get Products:**
```bash
curl -X GET "http://localhost:8000/api/obat" \
  -H "Accept: application/json"
```

**Create Sale:**
```bash
curl -X POST "http://localhost:8000/api/penjualan" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -d '{
    "nama_pelanggan": "John Doe",
    "metode_pembayaran": "Tunai",
    "bayar": 25000,
    "items": [
      {
        "obat_id": 1,
        "jumlah": 2,
        "harga": 5000
      }
    ]
  }'
```

---

## 📞 Support

For API support:
- **Documentation**: This file
- **Issues**: GitHub Issues
- **Email**: api-support@apotekpuri.com

---

**API Version**: 1.0.0  
**Last Updated**: January 15, 2024