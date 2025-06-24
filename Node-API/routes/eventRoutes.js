const express = require('express');
const router = express.Router();
const db = require('../config/db');
const multer = require('multer');
const path = require('path');

// Middleware JWT (opsional)
const jwt = require('jsonwebtoken');

// Setup storage untuk Multer
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, 'public/posters'); // Pastikan folder ini ada
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, uniqueSuffix + path.extname(file.originalname));
  }
});
const upload = multer({ storage });

function verifyToken(req, res, next) {
  const token = req.headers['authorization'];
  if (!token) return res.status(403).json({ message: 'Token tidak diberikan.' });

  const bearerToken = token.split(' ')[1];
  try {
    const decoded = jwt.verify(bearerToken, process.env.JWT_SECRET || 'SECRET_KEY');
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(401).json({ message: 'Token tidak valid.' });
  }
}

// ✅ GET /api/events — ambil semua event
router.get('/', async (req, res) => {
  try {
    const [events] = await db.query('SELECT * FROM events ORDER BY registration_open_date ASC');
    const [eventTypes] = await db.query('SELECT id, type FROM event_types');

    res.json({
      events,
      eventTypes
    });
  } catch (err) {
    console.error('Error fetching events:', err);
    res.status(500).json({ message: 'Gagal mengambil data event' });
  }
});


router.post('/add-event', upload.single('poster_image'), async (req, res) => {
  try {
    const {
      event_type_id,
      name,
      description,
      start_date,
      end_date,
      location,
      registration_fee,
      registration_type,
      max_participants,
      current_participants = 0,
      registration_open_date,
      registration_close_date,
      certificate_type,
      is_active
    } = req.body;

    // Validasi dasar
    if (!event_type_id || !name || !start_date || !end_date || !location) {
      return res.status(400).json({ message: 'Mohon lengkapi data wajib.' });
    }

    // Handle image path jika ada file upload
    let poster_image = null;
    if (req.file) {
      poster_image = `/posters/${req.file.filename}`;
    }

    const [result] = await db.query(`
      INSERT INTO events (
        event_type_id,
        name,
        description,
        start_date,
        end_date,
        location,
        poster_image,
        registration_fee,
        registration_type,
        max_participants,
        current_participants,
        registration_open_date,
        registration_close_date,
        certificate_type,
        is_active
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      event_type_id,
      name,
      description,
      start_date,
      end_date,
      location,
      poster_image,
      registration_fee,
      registration_type,
      max_participants,
      current_participants,
      registration_open_date,
      registration_close_date,
      certificate_type,
      is_active
    ]);

    res.status(201).json({ message: 'Event berhasil dibuat', event_id: result.insertId });
  } catch (err) {
    console.error('Error creating event:', err);
    res.status(500).json({ message: 'Gagal membuat event' });
  }
});

// ✅ GET /api/events/:id — ambil detail event
router.get('/:id', async (req, res) => {
  const { id } = req.params;
  try {
    const [rows] = await db.query('SELECT * FROM events WHERE id = ?', [id]);
    if (rows.length === 0) {
      return res.status(404).json({ message: 'Event tidak ditemukan' });
    }
    res.json(rows[0]);
  } catch (err) {
    console.error('Error fetching event by ID:', err);
    res.status(500).json({ message: 'Terjadi kesalahan pada server' });
  }
});

module.exports = router;
