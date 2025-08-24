const http = require("http");
const url = require("url");
const { StringDecoder } = require("string_decoder");
const InventoryManager = require("./InventoryManager");
const emitter = require("./events");

// Konfigurasi koneksi database
const inventory = new InventoryManager({
  host: "localhost",
  user: "root",
  password: "",
  database: "inventory_db"
});

// Listener stok rendah
emitter.on("lowStock", (data) => {
  console.log(`⚠️ Produk ${data.productId} stok menipis! (sisa ${data.stock})`);
});

// Fungsi generate ID otomatis
async function generateId(conn, table, prefix) {
  const [rows] = await conn.execute(`SELECT id FROM ${table} ORDER BY id DESC LIMIT 1`);
  if (rows.length === 0) return prefix + "001";
  const lastId = rows[0].id;
  const num = parseInt(lastId.slice(1)) + 1;
  return prefix + String(num).padStart(3, "0");
}

const server = http.createServer(async (req, res) => {
  const parsedUrl = url.parse(req.url, true);
  const decoder = new StringDecoder("utf-8");
  let buffer = "";

  req.on("data", (chunk) => (buffer += decoder.write(chunk)));
  req.on("end", async () => {
    buffer += decoder.end();
    res.setHeader("Content-Type", "application/json");

    try {
      const conn = await inventory.getConnection();

      // =================== PRODUCTS ===================
      if (req.method === "POST" && parsedUrl.pathname === "/products") {
        const body = JSON.parse(buffer);
        const id = await generateId(conn, "products", "P");
        await inventory.addProduct(id, body.name, body.price, body.stock, body.category);
        await conn.end();
        res.end(JSON.stringify({ message: "Product created", id }));
      } else if (req.method === "GET" && parsedUrl.pathname === "/products") {
        const page = parseInt(parsedUrl.query.page) || 1;
        const limit = parseInt(parsedUrl.query.limit) || 1000;
        const offset = (page - 1) * limit;

        let query = "SELECT * FROM products";
        let params = [];
        if (parsedUrl.query.category) {
          query += " WHERE category = ?";
          params.push(parsedUrl.query.category);
        }
        query += " ORDER BY name ASC LIMIT ? OFFSET ?";
        params.push(limit, offset);

        const [rows] = await conn.execute(query, params);
        const [countRes] = await conn.execute("SELECT COUNT(*) as total FROM products");
        await conn.end();

        res.end(JSON.stringify({ page, limit, total: countRes[0].total, data: rows }));
      }

      // =================== CUSTOMERS ===================
      else if (req.method === "POST" && parsedUrl.pathname === "/customers") {
        const body = JSON.parse(buffer);
        const id = await generateId(conn, "customers", "C");
        await conn.execute(
          "INSERT INTO customers (id, name, email) VALUES (?, ?, ?)",
          [id, body.name, body.email]
        );
        await conn.end();
        res.end(JSON.stringify({ message: "Customer created", id }));
      } else if (req.method === "GET" && parsedUrl.pathname === "/customers") {
        const page = parseInt(parsedUrl.query.page) || 1;
        const limit = parseInt(parsedUrl.query.limit) || 1000;
        const offset = (page - 1) * limit;

        const [rows] = await conn.execute(
          "SELECT * FROM customers WHERE deleted_at IS NULL ORDER BY name ASC LIMIT ? OFFSET ?",
          [limit, offset]
        );
        const [countRes] = await conn.execute(
          "SELECT COUNT(*) as total FROM customers WHERE deleted_at IS NULL"
        );
        await conn.end();

        res.end(JSON.stringify({ page, limit, total: countRes[0].total, data: rows }));
      }

      // =================== TRANSACTIONS ===================
      else if (req.method === "POST" && parsedUrl.pathname === "/transactions") {
        const body = JSON.parse(buffer);
        const id = await generateId(conn, "transactions", "T");

        const [productRows] = await conn.execute(
          "SELECT * FROM products WHERE id=?",
          [body.productId]
        );
        if (productRows.length === 0) throw new Error("Product not found");

        let newStock = productRows[0].stock;
        if (body.type === "OUT") {
          if (newStock < body.quantity) throw new Error("Stock tidak cukup");
          newStock -= body.quantity;
        } else if (body.type === "IN") {
          newStock += body.quantity;
        }

        await conn.execute(
          "INSERT INTO transactions (id, productId, customerId, quantity, type, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
          [id, body.productId, body.customerId, body.quantity, body.type]
        );
        await conn.execute(
          "UPDATE products SET stock=? WHERE id=?",
          [newStock, body.productId]
        );

        await conn.end();
        res.end(JSON.stringify({ message: "Transaction created", id }));
      } else if (req.method === "GET" && parsedUrl.pathname === "/transactions") {
        const page = parseInt(parsedUrl.query.page) || 1;
        const limit = parseInt(parsedUrl.query.limit) || 1000;
        const offset = (page - 1) * limit;

        const [rows] = await conn.execute(
          `SELECT 
              t.id, 
              t.quantity, 
              t.type, 
              t.created_at,
              p.id as productId, 
              p.name as product_name, 
              p.category, 
              p.price,
              c.id as customerId, 
              c.name as customer_name, 
              c.email
           FROM transactions t
           JOIN products p ON t.productId = p.id
           JOIN customers c ON t.customerId = c.id
           WHERE t.deleted_at IS NULL
           ORDER BY t.created_at DESC
           LIMIT ? OFFSET ?`,
          [limit, offset]
        );

        const [countRes] = await conn.execute(
          "SELECT COUNT(*) as total FROM transactions WHERE deleted_at IS NULL"
        );

        await conn.end();
        res.end(JSON.stringify({ page, limit, total: countRes[0].total, data: rows }));
      }

      // =================== 404 ===================
      else {
        await conn.end();
        res.statusCode = 404;
        res.end(JSON.stringify({ error: "Endpoint not found" }));
      }
    } catch (err) {
      res.statusCode = err.statusCode || 400;
      res.end(JSON.stringify({ error: err.message }));
    }
  });
});

server.listen(8000, () => {
  console.log("🚀 Server running on http://localhost:8000");
});
