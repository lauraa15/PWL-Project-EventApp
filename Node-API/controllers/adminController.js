exports.getAllUsers = async (req, res) => {
  try {
    const [rows] = await db.query(`
      SELECT users.*, roles.name AS role_name
      FROM users
      LEFT JOIN roles ON users.role_id = roles.id
    `);
    res.json(rows); // kirim data JSON ke frontend
  } catch (err) {
    console.error('Error ambil users:', err);
    res.status(500).json({ message: 'Gagal mengambil data users' });
  }
};