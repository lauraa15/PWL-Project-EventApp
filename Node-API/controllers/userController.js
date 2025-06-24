const db = require('../config/db');
const bcrypt = require('bcrypt');

// ✅ GET semua user
const getAllUsers = async (req, res) => {
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

// ✅ CREATE user baru dengan role 'finance' atau 'organizer'
// const createUser = async (req, res) => {
//   const { name, email, phone_number, role_name, password } = req.body;

//   try {
//     // // Validasi role hanya boleh finance / organizer
//     const allowedRoles = ['finance', 'organizer'];
//     if (!allowedRoles.includes(role_name.toLowerCase())) {
//       return res.status(400).json({ message: 'Role tidak diizinkan untuk ditambahkan.' });
//     }

//     // Validasi password wajib
//     if (!password || password.trim().length < 6) {
//       return res.status(400).json({ message: 'Password wajib diisi minimal 6 karakter.' });
//     }

//     // Cari role_id dari nama role
//     const [roleResult] = await db.query(`SELECT id FROM roles WHERE name = ?`, [role_name.toLowerCase()]);
//     if (roleResult.length === 0) {
//       return res.status(400).json({ message: 'Role tidak ditemukan di database.' });
//     }

//     const roleId = roleResult[0].id;

//     // Hash password
//     const hashedPassword = await bcrypt.hash(password, 10);

//     // Simpan user baru
//     const [result] = await db.query(`
//       INSERT INTO users (name, email, phone_number, password, role_id, is_active)
//       VALUES (?, ?, ?, ?, ?, 1)
//     `, [name, email, phone_number, hashedPassword, roleId]);

//     res.status(201).json({
//       message: 'User berhasil ditambahkan.',
//       userId: result.insertId
//     });

//   } catch (err) {
//     console.error('Create user error:', err);
//     res.status(500).json({ message: 'Gagal menambahkan user.' });
//   }
// };

// KALO CREATE USER NYA BISA SEMUA ROLE
const createUser = async (req, res) => {
  const { name, email, phone_number, role_name, password } = req.body;

  try {
    // Validasi input
    if (!name || !email || !phone_number || !role_name || !password) {
      return res.status(400).json({ message: 'Semua field wajib diisi.' });
    }

    if (password.trim().length < 6) {
      return res.status(400).json({ message: 'Password minimal 6 karakter.' });
    }

    // Cari role_id berdasarkan role_name (case-insensitive)
    const [roleResult] = await db.query(`SELECT id FROM roles WHERE LOWER(name) = ?`, [role_name.toLowerCase()]);
    if (roleResult.length === 0) {
      return res.status(400).json({ message: 'Role tidak ditemukan di database.' });
    }

    const roleId = roleResult[0].id;

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Simpan user
    const [result] = await db.query(`
      INSERT INTO users (name, email, phone_number, password, role_id, is_active)
      VALUES (?, ?, ?, ?, ?, 1)
    `, [name, email, phone_number, hashedPassword, roleId]);

    res.status(201).json({
      message: 'User berhasil ditambahkan.',
      userId: result.insertId
    });

  } catch (err) {
    console.error('Create user error:', err);
    res.status(500).json({ message: 'Gagal menambahkan user.' });
  }
};

// ✅ UPDATE user jika dia ber-role 'finance' atau 'organizer'
const updateUser = async (req, res) => {
  const userId = req.params.id;
  const { name, email, phone_number, role_name } = req.body;

  try {
    // Ambil user yang mau diupdate
    const [users] = await db.query(`
      SELECT users.*, roles.name AS role_name
      FROM users
      JOIN roles ON users.role_id = roles.id
      WHERE users.id = ?
    `, [userId]);

    const targetUser = users[0];
    if (!targetUser) {
      return res.status(404).json({ message: 'User tidak ditemukan.' });
    }

    if (!['finance', 'organizer'].includes(targetUser.role_name.toLowerCase())) {
      return res.status(403).json({ message: 'Hanya user dengan role Finance atau Organizer yang dapat diubah.' });
    }

    const [roleResult] = await db.query(`SELECT id FROM roles WHERE name = ?`, [role_name.toLowerCase()]);
    if (roleResult.length === 0) {
      return res.status(400).json({ message: 'Role tidak ditemukan.' });
    }

    const roleId = roleResult[0].id;

    await db.query(`
      UPDATE users
      SET name = ?, email = ?, phone_number = ?, role_id = ?
      WHERE id = ?
    `, [name, email, phone_number, roleId, userId]);

    res.json({ message: 'User berhasil diperbarui.' });
  } catch (err) {
    console.error('Update user error:', err);
    res.status(500).json({ message: 'Gagal memperbarui user.' });
  }
};




// ✅ DELETE user (bisa diubah jadi "nonaktif" juga kalau ingin soft delete)
const deleteUser = async (req, res) => {
  const userId = req.params.id;

  try {
    // Ambil user yang mau dihapus
    const [users] = await db.query(`
      SELECT users.*, roles.name AS role_name
      FROM users
      JOIN roles ON users.role_id = roles.id
      WHERE users.id = ?
    `, [userId]);

    const targetUser = users[0];
    if (!targetUser) {
      return res.status(404).json({ message: 'User tidak ditemukan.' });
    }

    if (!['finance', 'organizer'].includes(targetUser.role_name.toLowerCase())) {
      return res.status(403).json({ message: 'Hanya user dengan role Finance atau Organizer yang dapat dihapus.' });
    }

    await db.query(`DELETE FROM users WHERE id = ?`, [userId]);

    res.json({ message: 'User berhasil dihapus.' });
  } catch (err) {
    console.error('Delete user error:', err);
    res.status(500).json({ message: 'Gagal menghapus user.' });
  }
};

const toggleUserStatus = async (req, res) => {
  const userId = req.params.id;

  try {
    console.log('Masuk toggleUserStatus dengan ID:', userId);
    const [userResult] = await db.query('SELECT is_active FROM users WHERE id = ?', [userId]);
    console.log('Query result:', userResult);

    if (userResult.length === 0) {
      return res.status(404).json({ message: 'User tidak ditemukan.' });
    }

    const currentStatus = userResult[0].is_active;
    const newStatus = currentStatus ? 0 : 1;

    await db.query('UPDATE users SET is_active = ? WHERE id = ?', [newStatus, userId]);

    res.json({ message: `User berhasil ${newStatus ? 'diaktifkan' : 'dinonaktifkan'}.` });
  } catch (err) {
    console.error('Toggle status error:', err);
    res.status(500).json({ message: 'Gagal mengubah status user.' });
  }
};

// ✅ Ekspor semua fungsi
module.exports = {
  getAllUsers,
  createUser,
  updateUser,
  deleteUser,
  toggleUserStatus
};
