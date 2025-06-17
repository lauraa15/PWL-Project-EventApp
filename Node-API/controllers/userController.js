const db = require('../config/db');


exports.getAllUsers = async (req, res) => {
  try {
    const [users] = await db.query(`
      SELECT 
        users.id, users.name, users.email, users.phone_number, users.is_active, 
        roles.name AS role_name
      FROM users
      JOIN roles ON users.role_id = roles.id
    `);
    res.json({ users });
  } catch (err) {
    console.error('Get users error:', err);
    res.status(500).json({ message: 'Gagal mengambil data pengguna.' });
  }
};
