/**
 * Import database/tour_nigeria.sql into a hosted MySQL-compatible database
 * (TiDB Cloud, Aiven, PlanetScale, ...) over TLS.
 *
 * Usage: node scripts/import-remote.js [envFile]
 *   envFile defaults to .env.production.local and must contain DATABASE_URL.
 * The password is never printed.
 */
const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");

const envFile = path.resolve(__dirname, "..", process.argv[2] || ".env.production.local");
const sqlFile = path.resolve(__dirname, "../database/tour_nigeria.sql");
const caFile = path.resolve(__dirname, "../prisma/aiven-ca.pem");

function readDatabaseUrl(file) {
  if (!fs.existsSync(file)) throw new Error(`Env file not found: ${file}`);
  const match = fs
    .readFileSync(file, "utf8")
    .match(/^\s*DATABASE_URL\s*=\s*["']?([^"'\r\n]+)["']?\s*$/m);
  if (!match) throw new Error(`No DATABASE_URL in ${file}`);
  return match[1].trim();
}

async function main() {
  const url = new URL(readDatabaseUrl(envFile));
  const database = decodeURIComponent(url.pathname.replace(/^\//, "")) || "tour_nigeria";
  const isLocal = ["localhost", "127.0.0.1"].includes(url.hostname);

  console.log(`Target: ${url.hostname}:${url.port || 3306} / ${database}`);

  const conn = await mysql.createConnection({
    host: url.hostname,
    port: Number(url.port || 3306),
    user: decodeURIComponent(url.username),
    password: decodeURIComponent(url.password),
    ssl: isLocal
      ? undefined
      : {
          minVersion: "TLSv1.2",
          rejectUnauthorized: true,
          // Providers like Aiven sign with a project CA; trust it when present.
          ...(fs.existsSync(caFile) ? { ca: fs.readFileSync(caFile, "utf8") } : {}),
        },
    multipleStatements: true,
    connectTimeout: 20000,
  });

  await conn.query(
    `CREATE DATABASE IF NOT EXISTS \`${database}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci`
  );
  await conn.query(`USE \`${database}\``);
  console.log(`Database \`${database}\` ready. Importing dump...`);

  const sql = fs
    .readFileSync(sqlFile, "utf8")
    // Drop client-only and versioned comment directives that some servers reject.
    .replace(/^\/\*![0-9]{5} SET [^;]*\*\/;\s*$/gm, "")
    .replace(/^LOCK TABLES .*;$/gm, "")
    .replace(/^UNLOCK TABLES;$/gm, "");

  await conn.query("SET FOREIGN_KEY_CHECKS=0");
  await conn.query(sql);
  await conn.query("SET FOREIGN_KEY_CHECKS=1");

  const tables = ["users", "states", "categories", "attractions", "scenes", "hotspots", "tour_sessions", "scene_views"];
  for (const t of tables) {
    const [rows] = await conn.query(`SELECT COUNT(*) AS n FROM \`${t}\``);
    console.log(`  ${t.padEnd(14)} ${rows[0].n}`);
  }
  await conn.end();
  console.log("Import complete.");
}

main().catch((err) => {
  console.error("Import failed:", err.code || "", err.message);
  process.exit(1);
});
