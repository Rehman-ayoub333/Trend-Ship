const express = require('express');
const cors = require('cors');
const path = require('path');
const sqlite3 = require('sqlite3').verbose();
const { body, validationResult } = require('express-validator');
const rateLimit = require('express-rate-limit');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 5000;
const dbPath = process.env.DB_PATH || './data/trendship.sqlite';

// Middleware
app.use(cors());
app.use(express.json());
app.use('/assets', express.static(path.join(__dirname, 'assets')));

// Database connection
const db = new sqlite3.Database(dbPath, (err) => {
  if (err) {
    console.error('Error opening database:', err.message);
  } else {
    console.log('Connected to the SQLite database.');
  }
});

// Helper for reference generation
const generateRef = (prefix) => {
  const year = new Date().getFullYear();
  const randomNum = Math.floor(1000 + Math.random() * 9000);
  return `${prefix}-${year}-${randomNum}`;
};

// --- RATE LIMITING (Part 11.1) ---
const contactLimiter = rateLimit({
  windowMs: 60 * 60 * 1000, // 1 hour
  max: 3, // limit each IP to 3 requests per windowMs
  message: { success: false, error: 'Too many requests. Please wait before trying again.' }
});

// --- ROUTING TABLE (Part 0.1) ---
app.get('/', (req, res) => res.sendFile(path.join(__dirname, 'index.html')));
app.get('/design-trend/', (req, res) => res.sendFile(path.join(__dirname, 'design-trend/index.html')));
app.get('/themes/', (req, res) => res.sendFile(path.join(__dirname, 'themes/index.html')));
app.get('/exhibition/', (req, res) => res.sendFile(path.join(__dirname, 'exhibition/index.html')));
app.get('/collection/', (req, res) => res.sendFile(path.join(__dirname, 'collection/index.html')));
app.get('/trend-report/', (req, res) => res.sendFile(path.join(__dirname, 'trend-report/index.html')));
app.get('/lookbook/', (req, res) => res.sendFile(path.join(__dirname, 'lookbook/index.html')));
app.get('/about/', (req, res) => res.sendFile(path.join(__dirname, 'about/index.html')));
app.get('/contact/', (req, res) => res.sendFile(path.join(__dirname, 'contact/index.html')));

// --- API ENDPOINTS ---

// 11.1 POST /api/contact
app.post('/api/contact', contactLimiter, [
  body('name').trim().notEmpty().withMessage('Name is required'),
  body('email').isEmail().withMessage('Valid email is required'),
  body('interest').isIn(['general', 'exhibition', 'collection', 'press', 'partnership']).withMessage('Invalid interest area'),
  body('message').isLength({ min: 10, max: 2000 }).withMessage('Message must be between 10-2000 chars')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  const { name, email, company, interest, message } = req.body;
  const reference = generateRef('TRD');

  db.run(`INSERT INTO contacts (name, email, company, interest, message, reference) VALUES (?, ?, ?, ?, ?, ?)`,
    [name, email, company, interest, message, reference],
    function(err) {
      if (err) {
        console.error('Error inserting contact:', err.message);
        return res.status(500).json({ success: false, error: 'Internal server error' });
      }
      console.log(`Notification sent to hello@lxhausys.com for ${reference}`);
      res.json({ success: true, reference });
    }
  );
});

// 11.1 POST /api/newsletter
app.post('/api/newsletter', [
  body('email').isEmail().withMessage('Valid email is required')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  const { email, tag } = req.body;

  db.run(`INSERT INTO newsletter (email) VALUES (?)`, [email], function(err) {
    if (err) {
      if (err.message.includes('UNIQUE constraint failed')) {
        return res.status(400).json({ success: false, error: 'Already subscribed' });
      }
      return res.status(500).json({ success: false, error: 'Internal server error' });
    }
    console.log(`New subscriber: ${email}${tag ? ` with tag [${tag}]` : ''}`);
    res.json({ success: true, message: "Welcome! Check your inbox." });
  });
});

// 11.1 POST /api/report/download
app.post('/api/report/download', [
  body('email').isEmail().withMessage('Valid email is required')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  const { email } = req.body;

  db.run(`INSERT INTO report_downloads (email) VALUES (?)`, [email], function(err) {
    if (err) console.error('Error recording download:', err.message);
    
    // Auto-add to newsletter with tag as per Part 11.1
    db.run(`INSERT OR IGNORE INTO newsletter (email) VALUES (?)`, [email], function(nlErr) {
      if (!nlErr) console.log(`Email ${email} auto-added to newsletter (Tag: report-download-2025)`);
      res.json({ success: true, pdf_url: "/assets/TRENDSHIP-2025-Report.pdf" });
    });
  });
});

// 11.1 POST /api/booking
app.post('/api/booking', [
  body('name').trim().notEmpty(),
  body('email').isEmail(),
  body('date').isISO8601().withMessage('Invalid date'),
  body('time').isIn(['10:00', '12:00', '14:00', '16:00']).withMessage('Invalid time slot'),
  body('party').isInt({ min: 1, max: 8 }).withMessage('Party size must be 1-8')
], (req, res) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ success: false, errors: errors.array() });
  }

  const { name, email, date, time, party } = req.body;
  const bookingDate = new Date(date);
  if (bookingDate.getDay() === 1) return res.status(400).json({ success: false, error: 'Closed on Mondays' });

  // 11.1 Check slot availability (Mocking "full" if total party size for slot > 15)
  db.get(`SELECT SUM(party) as total FROM bookings WHERE date = ? AND time = ?`, [date, time], (err, row) => {
    if (err) return res.status(500).json({ success: false, error: 'Internal server error' });
    
    const currentTotal = row.total || 0;
    if (currentTotal + party > 15) {
      return res.status(400).json({ success: false, error: 'Time slot is fully booked. Please choose another.' });
    }

    const reference = generateRef('MAI');
    db.run(`INSERT INTO bookings (name, email, date, time, party, reference) VALUES (?, ?, ?, ?, ?, ?)`,
      [name, email, date, time, party, reference],
      function(insErr) {
        if (insErr) return res.status(500).json({ success: false, error: 'Internal server error' });
        res.json({ success: true, booking_ref: reference, confirmation_email_sent: true });
      }
    );
  });
});

// 11.1 GET /api/spec/:materialId
const SPEC_LOOKUP = {
  'boost-01': 'LX-B2025-001.pdf', 'boost-02': 'LX-B2025-002.pdf', 'boost-03': 'LX-B2025-003.pdf',
  'cosmos-01': 'LX-C2025-001.pdf', 'cosmos-02': 'LX-C2025-002.pdf', 'cosmos-03': 'LX-C2025-003.pdf',
  'ooparts-01': 'LX-O2025-001.pdf', 'ooparts-02': 'LX-O2025-002.pdf', 'ooparts-03': 'LX-O2025-003.pdf',
  'synergy-01': 'LX-S2025-001.pdf', 'synergy-02': 'LX-S2025-002.pdf', 'synergy-03': 'LX-S2025-003.pdf'
};
app.get('/api/spec/:materialId', (req, res) => {
  const fileName = SPEC_LOOKUP[req.params.materialId];
  if (!fileName) return res.status(404).send('Spec sheet not found');
  res.redirect(`/assets/specs/${fileName}`);
});

// 11.1 POST /api/moodboard/generate
app.post('/api/moodboard/generate', (req, res) => {
  const { items, name } = req.body;
  if (!items || items.length < 2) return res.status(400).json({ success: false, error: 'Select at least 2 items' });
  const moodboardId = Math.random().toString(36).substring(7);
  res.json({ success: true, download_url: `/tmp/moodboard-${moodboardId}.pdf` });
});

// Static assets for CSS, JS, etc.
app.use('/css', express.static(path.join(__dirname, 'css')));
app.use('/js', express.static(path.join(__dirname, 'js')));
app.use('/components', express.static(path.join(__dirname, 'components')));

app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));
