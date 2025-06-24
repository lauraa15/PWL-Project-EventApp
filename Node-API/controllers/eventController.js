const db = require('../config/db');

// Ambil semua event
const getAllEvents = async (req, res) => {
  try {
    const [events] = await db.query(`
      SELECT e.*, et.type AS event_type_name
      FROM events e
      JOIN event_types et ON e.event_type_id = et.id
    `);
    res.json({ success: true, data: events });
  } catch (error) {
    console.error('Get all events error:', error);
    res.status(500).json({ success: false, message: 'Gagal mengambil data events.' });
  }
};

// Nonaktif/Aktifkan event
const toggleEventStatus = async (req, res) => {
  const eventId = req.params.id;

  try {
    const [eventRows] = await db.query('SELECT is_active FROM events WHERE id = ?', [eventId]);
    if (eventRows.length === 0) {
      return res.status(404).json({ message: 'Event tidak ditemukan.' });
    }

    const currentStatus = eventRows[0].is_active;
    const newStatus = currentStatus ? 0 : 1;

    await db.query('UPDATE events SET is_active = ? WHERE id = ?', [newStatus, eventId]);

    res.json({ message: `Event berhasil ${newStatus ? 'diaktifkan' : 'dinonaktifkan'}.` });
  } catch (err) {
    console.error('Toggle event status error:', err);
    res.status(500).json({ message: 'Gagal mengubah status event.' });
  }
};

const getEvent = async (req, res) => {
  const eventId = req.params.id;

  try {
    // Ambil detail event
    const [eventResult] = await db.query(`
      SELECT e.*, et.type AS event_type_name
      FROM events e
      JOIN event_types et ON e.event_type_id = et.id
      WHERE e.id = ?
    `, [eventId]);

    if (eventResult.length === 0) {
      return res.status(404).json({ message: 'Event tidak ditemukan.' });
    }

    const event = eventResult[0];

    // Ambil daftar panitia
    const [committeeResult] = await db.query(`
      SELECT u.id, u.name, u.email, u.phone_number
      FROM event_committees ec
      JOIN users u ON ec.user_id = u.id
      WHERE ec.event_id = ?
    `, [eventId]);

    // Gabungkan
    event.committees = committeeResult;

    res.json({ success: true, data: event });

  } catch (err) {
    console.error('Get event by ID error:', err);
    res.status(500).json({ message: 'Gagal mengambil detail event.' });
  }
};
module.exports = {
  getAllEvents,
  toggleEventStatus,
  getEvent
};
