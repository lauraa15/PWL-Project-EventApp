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

const updateUser = async (req, res) => {
    const userId = req.params.id;
    const { name, email, phone_number, role_name } = req.body;

    const targetUser = await db.users.findByPk(userId, {
        include: ['role']
    });

    if (!['finance', 'organizer'].includes(targetUser.role.role_name.toLowerCase())) {
        return res.status(403).json({ message: 'Hanya user dengan role Finance atau Organizer yang bisa diubah.' });
    }

    // Lanjutkan update
};
