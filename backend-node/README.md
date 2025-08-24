# 📘 Inventory Management API (Node.js Native + MySQL)

Implementasi **Sistem Inventory Management** menggunakan **Node.js (tanpa framework)** dan **MySQL**.  
Mencakup fitur wajib sesuai soal tes + bonus (EventEmitter, Custom Error, Logging).

---

## 📂 Struktur Project

```
backend-node/
├── server.js              # Entry point API (HTTP server)
├── InventoryManager.js    # Class Inventory Manager (business logic)
├── events.js              # EventEmitter untuk notifikasi stok rendah
├── AppError.js            # Custom Error class
├── logger.js              # Logging transaksi ke file
├── transactions.log       # File log transaksi (otomatis terbuat saat transaksi)
├── db.sql                 # Schema database MySQL
├── package.json           # NPM dependencies & scripts
└── README.md              # Dokumentasi
```

---

## ⚙️ Persiapan

### 1. Setup Database

1. Pastikan MySQL sudah jalan.
2. Import schema `db.sql`:

   ```sql
   source db.sql;
   ```

   atau copy-paste isi `db.sql` ke MySQL client.

3. Database default:
   - Host: `localhost`
   - User: `root`
   - Password: _(kosong, bisa disesuaikan di `server.js`)_
   - Database: `inventory_db`

---

### 2. Setup Backend

Masuk ke folder backend:

```bash
cd backend-node
npm install
```

---

## 🚀 Menjalankan Server

- Mode normal:

  ```bash
  npm start
  ```

- Mode development (auto-reload dengan nodemon):
  ```bash
  npm run dev
  ```

Server akan jalan di:

```
http://localhost:8000
```

---

## 📌 Endpoint API

### 1. Tambah Produk

**POST** `/products`

```json
{
  "id": "P001",
  "name": "Laptop",
  "price": 10000000,
  "stock": 10,
  "category": "Elektronik"
}
```

---

### 2. Daftar Produk (dengan pagination & filter)

**GET** `/products?page=1&limit=10`  
**GET** `/products?category=Elektronik&page=1&limit=5`

Response:

```json
{
  "page": 1,
  "limit": 10,
  "total": 1,
  "data": [
    {
      "id": "P001",
      "name": "Laptop",
      "price": 10000000,
      "stock": 10,
      "category": "Elektronik"
    }
  ]
}
```

---

### 3. Update Stok Produk

**PUT** `/products/:id`

```json
{
  "quantity": 2,
  "type": "reduce"
}
```

---

### 4. Tambah Transaksi

**POST** `/transactions`

```json
{
  "id": "T001",
  "productId": "P001",
  "quantity": 2,
  "type": "sell",
  "customerId": "C001"
}
```

---

### 5. Laporan Nilai Inventory

**GET** `/reports/inventory`

Response:

```json
{
  "totalValue": 80000000
}
```

---

### 6. Produk Stok Rendah (dengan pagination)

**GET** `/reports/low-stock?threshold=5&page=1&limit=5`

Response:

```json
{
  "page": 1,
  "limit": 5,
  "total": 2,
  "data": [
    {
      "id": "P002",
      "name": "Mouse",
      "price": 150000,
      "stock": 3,
      "category": "Elektronik"
    },
    {
      "id": "P005",
      "name": "Keyboard",
      "price": 250000,
      "stock": 2,
      "category": "Elektronik"
    }
  ]
}
```

---

### 7. Riwayat Transaksi Produk

**GET** `/products/:id/history`

Response:

```json
[
  {
    "id": "T001",
    "product_id": "P001",
    "quantity": 2,
    "type": "sell",
    "customer_id": "C001",
    "created_at": "2025-08-23T12:10:45.000Z"
  }
]
```

---

## 📝 Bonus Features

- **EventEmitter** → log peringatan ke console kalau stok ≤ 5:
  ```
  ⚠️ Produk P001 stok menipis! (sisa 3)
  ```
- **Custom Error class** → semua error punya `statusCode` & `message` rapi.
- **Logging transaksi** → setiap transaksi otomatis tersimpan di `transactions.log`:
  ```
  [2025-08-23T12:10:45.123Z] {"transactionId":"T001","productId":"P001","quantity":2,"type":"sell","customerId":"C001"}
  ```

---

✅ Dengan ini, semua requirement tes **Soal 1 (Backend)** sudah dipenuhi + bonus poin juga.
