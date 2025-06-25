// attendanceController.js
const db = require('../config/db');

exports.scanAttendance = async (req, res) => {
  const { qr_code, session_id } = req.body;

  if (!qr_code || !session_id) {
    return res.status(400).json({ message: 'QR code dan session wajib diisi' });
  }

  try {
    // Cek apakah qr_code valid
    const [registrations] = await db.query(
      'SELECT id FROM registrations WHERE qr_code = ?',
      [qr_code]
    );

    if (registrations.length === 0) {
      return res.status(404).json({ message: 'QR code tidak valid atau tidak ditemukan' });
    }

    const registration_id = registrations[0].id;

    // Cek apakah sudah pernah scan untuk session ini
    const [existing] = await db.query(
      'SELECT * FROM attendance WHERE registration_id = ? AND session_id = ?',
      [registration_id, session_id]
    );

    if (existing.length > 0) {
      return res.status(409).json({ message: 'Peserta sudah terdaftar hadir untuk sesi ini' });
    }

    // Insert ke attendance
    await db.query(
      `INSERT INTO attendance (registration_id, session_id, scan_time, created_at)
       VALUES (?, ?, NOW(), NOW())`,
      [registration_id, session_id]
    );

    res.json({ message: 'Kehadiran berhasil dicatat' });

  } catch (err) {
    console.error('Error scan QR:', err);
    res.status(500).json({ message: 'Terjadi kesalahan saat memproses kehadiran' });
  }
};
