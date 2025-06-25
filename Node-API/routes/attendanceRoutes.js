// routes/attendance.js
const express = require('express');
const router = express.Router();
const db = require('../config/db');

router.post('/scan', async (req, res) => {
    const { qr_code_text, session_id } = req.body;

    if (!qr_code_text || !session_id) {
        return res.status(400).json({ success: false, message: 'Data tidak lengkap' });
    }

    try {
        // Cek registration ID dari kode registrasi
        const [registration] = await db.query(
            'SELECT id FROM registrations WHERE registration_code = ?', [qr_code_text]
        );

        if (!registration || registration.length === 0) {
            return res.status(404).json({ success: false, message: 'Registrasi tidak ditemukan' });
        }

        const registrationId = registration[0].id;

        // Cek apakah sudah pernah dicatat untuk sesi ini
        const [existing] = await db.query(
            'SELECT id FROM attendances WHERE registration_id = ? AND session_id = ?', [registrationId, session_id]
        );

        if (existing.length > 0) {
            return res.json({ success: false, message: 'Peserta sudah tercatat hadir di sesi ini.' });
        }

        // Insert ke attendance
        await db.query(`
            INSERT INTO attendances (registration_id, session_id, scan_time, created_at)
            VALUES (?, ?, NOW(), NOW())
        `, [registrationId, session_id]);

        res.json({ success: true, message: 'Kehadiran berhasil dicatat.' });
    } catch (err) {
        console.error('❌ Error saat mencatat kehadiran:', err);
        res.status(500).json({ success: false, message: 'Terjadi kesalahan server.' });
    }
});
router.get('/user/:userId', async (req, res) => {
  try {
    const { userId } = req.params;
    const [data] = await db.query(`
      SELECT a.*, r.registration_code, u.name, s.title as session_name
      FROM attendances a
      JOIN registrations r ON r.id = a.registration_id
      JOIN users u ON u.id = r.user_id
      JOIN event_sessions s ON s.id = a.session_id
      WHERE r.user_id = ?
    `, [userId]);

    return res.json({ success: true, data }); // ✅ tambahkan return agar menghentikan eksekusi
  } catch (err) {
    console.error(err);
    return res.status(500).json({ success: false, message: "Gagal ambil data attendance" });
  }
});


module.exports = router;
