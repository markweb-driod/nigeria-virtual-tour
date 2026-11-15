/**
 * Nigerian Virtual Tour System - Demo Data Importer
 * 
 * Imports database/tour_nigeria.sql into MySQL database using mysql CLI or Node mysql2.
 */
const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

require('dotenv').config({ path: path.resolve(__dirname, '../.env.local') });
if (!process.env.DATABASE_URL) {
  require('dotenv').config({ path: path.resolve(__dirname, '../.env') });
}

const dbUrl = process.env.DATABASE_URL || 'mysql://root:@localhost:3306/tour_nigeria';
const sqlFilePath = path.resolve(__dirname, '../database/tour_nigeria.sql');

if (!fs.existsSync(sqlFilePath)) {
  console.error('❌ SQL dump file not found at:', sqlFilePath);
  process.exit(1);
}

console.log('🌍 Preparing database import...');
console.log('📁 SQL Source:', sqlFilePath);
console.log('🔗 Target Database:', dbUrl.replace(/:[^:@]*@/, ':****@'));

try {
  // Parse DATABASE_URL
  const parsed = new URL(dbUrl);
  const user = parsed.username || 'root';
  const password = parsed.password || '';
  const host = parsed.hostname || 'localhost';
  const port = parsed.port || '3306';
  const database = parsed.pathname.replace(/^\//, '') || 'tour_nigeria';

  const mysqlBin = process.platform === 'win32' && fs.existsSync('C:\\xampp\\mysql\\bin\\mysql.exe')
    ? 'C:\\xampp\\mysql\\bin\\mysql.exe'
    : 'mysql';

  const passArg = password ? `-p"${password}"` : '';
  const cmd = `"${mysqlBin}" -h ${host} -P ${port} -u ${user} ${passArg} -e "CREATE DATABASE IF NOT EXISTS \`${database}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`;
  execSync(cmd, { stdio: 'inherit' });

  console.log(`✅ Database \`${database}\` confirmed / created.`);

  // Import SQL file
  console.log('⏳ Importing tables, scenes, hotspots, and demo tour sessions...');
  const importCmd = `"${mysqlBin}" -h ${host} -P ${port} -u ${user} ${passArg} ${database} < "${sqlFilePath}"`;
  execSync(importCmd, { stdio: 'inherit', shell: true });

  console.log('🎉 Successfully imported all demo data into `' + database + '`!');
} catch (err) {
  console.error('⚠️ Import failed via CLI. Make sure MySQL is running.');
  console.error(err.message);
  process.exit(1);
}
