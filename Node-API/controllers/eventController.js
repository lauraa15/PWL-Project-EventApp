const db = require('../config/db');

// Ambil semua event
const getAllEvents = async (req, res) => {
  console.log('req.user:', req.user);

  try {
    const [events] = await db.query(`
      SELECT e.*, et.type AS event_type_name
      FROM events e
      JOIN event_types et ON e.event_type_id = et.id
    `);
    const [eventTypes] = await db.query('SELECT id, type FROM event_types');

    res.status(200).json({ events, eventTypes });
  } catch (err) {
    console.error('Error fetching events:', err);
    res.status(500).json({ message: 'Gagal mengambil data event' });
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

    return res.status(200).json({
      success: true,
      data: event
    });

  } catch (err) {
    console.error('Get event by ID error:', err);
    res.status(500).json({ message: 'Gagal mengambil detail event.' });
  }
};

const updateEvent = (req, res) => {
    const eventId = req.params.id;
    const data = req.body;

    if (!eventId) {
        return res.status(400).json({ message: 'ID event tidak valid.' });
    }

    // Buat array untuk menyimpan bagian SET dari query dan nilai-nilainya
    const fields = [];
    const values = [];

    const allowedFields = [
        'name', 'event_type_id', 'description', 'start_date', 'end_date',
        'location', 'registration_open_date', 'registration_close_date',
        'registration_fee', 'registration_type', 'max_participants',
        'certificate_type', 'is_active'
    ];

    // Loop semua field yang diizinkan dan tambahkan jika ada di request body
    allowedFields.forEach(field => {
        if (data[field] !== undefined && data[field] !== null && data[field] !== '') {
            fields.push(`${field} = ?`);
            values.push(data[field]);
        }
    });

    if (fields.length === 0) {
        return res.status(400).json({ message: 'Tidak ada data untuk diupdate.' });
    }

    // Tambahkan updated_at
    fields.push('updated_at = NOW()');

    const sql = `
        UPDATE events SET ${fields.join(', ')}
        WHERE id = ?
    `;
    values.push(eventId);

    db.query(sql, values, (err, result) => {
        if (err) {
            console.error('Update event error:', err);
            return res.status(500).json({ message: 'Gagal memperbarui event.' });
        }

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Event tidak ditemukan.' });
        }

        res.status(200).json({ message: 'Event berhasil diperbarui.' });
    });
};


module.exports = {
  getAllEvents,
  toggleEventStatus,
  getEvent,
  updateEvent
};
