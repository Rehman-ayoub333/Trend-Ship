const sqlite3 = require('sqlite3').verbose();
const path = require('path');
require('dotenv').config();

const dbPath = process.env.DB_PATH || './data/trendship.sqlite';
const dbDir = path.dirname(dbPath);

const db = new sqlite3.Database(dbPath, (err) => {
  if (err) {
    console.error('Error opening database:', err.message);
    return;
  }
  console.log('Connected to the SQLite database.');
});

db.serialize(() => {
  // Contacts Table
  db.run(`CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    company TEXT,
    interest TEXT NOT NULL,
    message TEXT NOT NULL,
    reference TEXT UNIQUE,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);

  // Newsletter Table
  db.run(`CREATE TABLE IF NOT EXISTS newsletter (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);

  // Bookings Table
  db.run(`CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    date TEXT NOT NULL,
    time TEXT NOT NULL,
    party INTEGER NOT NULL,
    reference TEXT UNIQUE,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);

  // Report Downloads Table
  db.run(`CREATE TABLE IF NOT EXISTS report_downloads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);

  console.log('Tables initialized successfully.');
});

db.close();
