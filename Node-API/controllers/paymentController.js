const db = require('../config/db');
const QRCode = require('qrcode');
const path = require('path');
const fs = require('fs');

const getFinanceRegistrations = async (req, res) => {
    const eventId = req.query.event_id; // ambil dari query string ?event_id=3
    const financeId = req.user.id;

  if (!eventId) {
    return res.status(400).json({ message: 'Event ID wajib dikirim.' });
  }

  try {
    const [rows] = await db.query(`
      SELECT 
        r.*, 
        u.name AS user_name, 
        u.email, 
        e.name AS event_name
      FROM registrations r
      JOIN users u ON r.user_id = u.id
      JOIN events e ON r.event_id = e.id
      JOIN event_committees ec ON e.id = ec.event_id
      JOIN users cu ON ec.user_id = cu.id
      WHERE cu.id = ? AND cu.role_id = 2
    `, [financeId]);

    res.json({ success: true, data: rows });
  } catch (err) {
    console.error('Error fetching registrations for event:', err);
    res.status(500).json({ message: 'Gagal mengambil data registrasi' });
  }
};

const approvePayment = async (req, res) => {
  const { id } = req.params;

  try {
    console.log('1. Update payment status');
    await db.query('UPDATE payments SET status = ? WHERE registration_id = ?', ['confirmed', id]);

    console.log('2. Generate QR code');
    const qrText = `REGISTRATION-${id}`;
    const qrPath = `public/qrcodes/qr_${id}.png`;

    await QRCode.toFile(qrPath, qrText);

    console.log('3. Simpan QR ke tabel registrations');
    const qrRelativePath = `qrcodes/qr_${id}.png`;
    await db.query('UPDATE registrations SET qr_code = ?, status = ? WHERE id = ?', [qrRelativePath, 'confirmed', id]);

    res.json({ success: true, message: 'Pembayaran disetujui dan QR code dibuat.' });
  } catch (err) {
    console.error('❌ Approve error:', err); // PENTING: lihat console ini
    res.status(500).json({ message: 'Gagal menyetujui pembayaran.' });
  }
};

const rejectPayment = async (req, res) => {
  const { id } = req.params;
  try {
    await db.query('UPDATE payments SET status = ? WHERE registration_id = ?', ['cancelled', id]);
    await db.query('UPDATE registrations SET status = ? WHERE id = ?', ['cancelled', id]);

    res.json({ success: true, message: 'Pembayaran ditolak.' });
  } catch (err) {
    console.error('Reject error:', err);
    res.status(500).json({ message: 'Gagal menolak pembayaran.' });
  }
};

module.exports = {
  getFinanceRegistrations,
    approvePayment,
    rejectPayment
};
