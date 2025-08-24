const mysql = require("mysql2/promise");
const emitter = require("./events");
const AppError = require("./AppError");
const { logTransaction } = require("./logger");

class InventoryManager {
  constructor(dbConfig) {
    this.dbConfig = dbConfig;
    this.lowStockThreshold = 5; // default batas stok
  }

  async getConnection() {
    return await mysql.createConnection(this.dbConfig);
  }

  // Tambah produk baru
  async addProduct(productId, name, price, stock, category) {
    const conn = await this.getConnection();
    await conn.execute(
      "INSERT INTO products (id, name, price, stock, category) VALUES (?, ?, ?, ?, ?)",
      [productId, name, price, stock, category]
    );
    await conn.end();

    if (stock <= this.lowStockThreshold) {
      emitter.emit("lowStock", { productId, stock });
    }
    return { message: "Product added" };
  }

  // Update stok produk
  async updateStock(productId, quantity, transactionType) {
    const conn = await this.getConnection();
    const [rows] = await conn.execute("SELECT stock FROM products WHERE id = ?", [productId]);
    if (rows.length === 0) throw new AppError("Product not found", 404);

    let newStock = rows[0].stock;
    if (transactionType === "add") {
      newStock += quantity;
    } else if (transactionType === "reduce") {
      if (newStock - quantity < 0) throw new AppError("Stock cannot be negative", 400);
      newStock -= quantity;
    }

    await conn.execute("UPDATE products SET stock = ? WHERE id = ?", [newStock, productId]);
    await conn.end();

    if (newStock <= this.lowStockThreshold) {
      emitter.emit("lowStock", { productId, stock: newStock });
    }

    return { message: "Stock updated", stock: newStock };
  }

  // Buat transaksi baru
  async createTransaction(transactionId, productId, quantity, type, customerId = null) {
    const conn = await this.getConnection();

    // Validasi stok untuk transaksi jual
    if (type === "sell") {
      const [rows] = await conn.execute("SELECT stock FROM products WHERE id = ?", [productId]);
      if (rows.length === 0) throw new AppError("Product not found", 404);
      if (rows[0].stock < quantity) throw new AppError("Not enough stock", 400);
    }

    await conn.execute(
      "INSERT INTO transactions (id, product_id, quantity, type, customer_id) VALUES (?, ?, ?, ?, ?)",
      [transactionId, productId, quantity, type, customerId]
    );

    // Update stok otomatis
    await this.updateStock(productId, quantity, type === "sell" ? "reduce" : "add");

    // Logging transaksi
    logTransaction({ transactionId, productId, quantity, type, customerId });

    await conn.end();
    return { message: "Transaction created" };
  }

  // Filter produk berdasarkan kategori
  async getProductsByCategory(category) {
    const conn = await this.getConnection();
    const [rows] = await conn.execute("SELECT * FROM products WHERE category = ?", [category]);
    await conn.end();
    return rows;
  }

  // Hitung nilai total inventory
  async getInventoryValue() {
    const conn = await this.getConnection();
    const [rows] = await conn.execute("SELECT SUM(price * stock) as totalValue FROM products");
    await conn.end();
    return rows[0].totalValue || 0;
  }

  // Riwayat transaksi produk
  async getProductHistory(productId) {
    const conn = await this.getConnection();
    const [rows] = await conn.execute("SELECT * FROM transactions WHERE product_id = ?", [productId]);
    await conn.end();
    return rows;
  }
}

module.exports = InventoryManager;
