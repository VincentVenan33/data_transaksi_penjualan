const fs = require("fs");
const path = require("path");

function logTransaction(data) {
    const logPath = path.join(__dirname, "transactions.log");
    const logEntry = `[${new Date().toISOString()}] ${JSON.stringify(data)}\n`;
    fs.appendFileSync(logPath, logEntry);
}

module.exports = { logTransaction };
