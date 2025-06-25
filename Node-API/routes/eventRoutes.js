const express = require('express');
const router = express.Router();
const db = require('../config/db');
const multer = require('multer');
const path = require('path');
const eventController = require('../controllers/eventController');
const { verifyToken } = require('../middlewares/authMiddleware');

// Middleware JWT (opsional)
const jwt = require('jsonwebtoken');
const { getAllEvents } = require('../controllers/eventController');


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
// Wajib pakai verifyToken di awal
router.get('/', eventController.getAllEvents);

// PATCH, PUT, dsb
router.patch('/:id/toggle', verifyToken, eventController.toggleEventStatus);
router.put('/update-event/:id', verifyToken, eventController.updateEvent);
router.get('/:id', verifyToken, eventController.getEvent);


router.get('/finance/registrations', async (req, res) => {
  try {
    // ambil data registrasi dari database
    const [rows] = await db.query(`
      SELECT r.*, u.name as user_name, u.email, e.name as event_name
      FROM registrations r
      JOIN users u ON r.user_id = u.id
      JOIN events e ON r.event_id = e.id
      WHERE e.finance_id = ?
    `, [req.user.id]);

    res.json({ success: true, data: rows });
  } catch (err) {
    console.error('Gagal ambil data registrasi:', err);
    res.status(500).json({ success: false, message: 'Terjadi kesalahan server' });
  }
}); 


module.exports = router;
