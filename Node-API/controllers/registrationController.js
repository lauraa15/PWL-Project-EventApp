const db = require('../config/db');
const { v4: uuidv4 } = require('uuid');
const dayjs = require('dayjs');

const register = async (req, res) => {
    const { name, email, phone, session_ids = [], notes } = req.body;
    const { eventId } = req.params;

    const userId = req.user?.id || 1; // gunakan real auth jika ada
    const registrationCode = uuidv4().split('-')[0].toUpperCase();
    const qrCode = `QR-${registrationCode}`;
    const now = dayjs().format('YYYY-MM-DD HH:mm:ss');

    const connection = await db.getConnection();
    await connection.beginTransaction();

    try {
        // 1. Simpan ke registrations
        const [registrationResult] = await connection.query(
            `INSERT INTO registrations 
            (user_id, event_id, registration_code, registration_date, status, updated_at)
            VALUES (?, ?, ?, ?, ?, ?)`,
            [userId, eventId, registrationCode, now, 'registered', now]
        );

        const registrationId = registrationResult.insertId;

        // 2. Ambil harga total dari sesi (atau event langsung)
        const [eventRows] = await connection.query(
            `SELECT registration_fee FROM events WHERE id = ?`,
            [eventId]
        );

        const amount = eventRows[0]?.registration_fee || 0;

        // 3. Simpan ke payments
        await connection.query(
            `INSERT INTO payments 
            (registration_id, amount, status, notes, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?)`,
            [registrationId, amount, 'pending', notes || null, now, now]
        );

        // 4. Add current user ke event_comittees jika belum ada
         await connection.query(
            `INSERT IGNORE INTO event_committees (event_id, user_id)
            VALUES (?, ?)`,
            [eventId, userId]
         );

        // 5. Add current_participants ke events
        await connection.query(
            `UPDATE events 
            SET current_participants = current_participants + 1, updated_at = ?
            WHERE id = ?`,
            [now, eventId]
        );

        await connection.commit();

        return res.status(201).json({
            success: true,
            registration_id: registrationId,
            registration_code: registrationCode,
            amount,
            payment_status: 'pending'
        });
    } catch (error) {
        await connection.rollback();
        console.error('Registration or payment failed:', error);
        return res.status(500).json({ error: 'Failed to register and create payment.' });
    } finally {
        connection.release();
    }
};

module.exports = {
  register
};