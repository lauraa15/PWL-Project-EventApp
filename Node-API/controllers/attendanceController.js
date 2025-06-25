const db = require('../config/db');

const scanAttendance = async (req, res) => {
  const { qr_code_text, session_id } = req.body;

  try {
    if (!qr_code_text || !session_id) {
      return res.status(400).json({ success: false, message: 'QR Code dan sesi wajib diisi.' });
    }

    // Ambil ID registrasi dari QR
    const match = qr_code_text.match(/REGISTRATION-(\d+)/);
    if (!match) {
      return res.status(400).json({ success: false, message: 'Format QR tidak valid.' });
    }
    const registration_id = parseInt(match[1]);

    // Cek apakah registrasi valid
    const [registrations] = await db.query(`
      SELECT r.*, u.name AS user_name 
      FROM registrations r
      JOIN users u ON r.user_id = u.id
      WHERE r.id = ?
    `, [registration_id]);

    if (registrations.length === 0) {
      return res.status(404).json({ success: false, message: 'Registrasi tidak ditemukan.' });
    }

    // Cek apakah sudah absen
    const [existing] = await db.query(`
      SELECT * FROM attendance 
      WHERE registration_id = ? AND session_id = ?
    `, [registration_id, session_id]);

    if (existing.length > 0) {
      return res.status(409).json({ success: false, message: 'Peserta sudah dicatat hadir di sesi ini.' });
    }

    // Simpan kehadiran
    await db.query(`
      INSERT INTO attendance (registration_id, session_id, scan_time, created_at)
      VALUES (?, ?, NOW(), NOW())
    `, [registration_id, session_id]);

    return res.json({ 
      success: true, 
      message: 'Kehadiran berhasil dicatat.',
      data: {
        registration_id,
        name: registrations[0].user_name
      }
    });

  } catch (error) {
    console.error('❌ Error saat mencatat kehadiran:', error);
    return res.status(500).json({ success: false, message: 'Terjadi kesalahan pada server.' });
  }
};

module.exports = {
  scanAttendance
};
