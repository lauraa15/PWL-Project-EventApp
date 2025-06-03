const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const db = require('../config/db');
const authService = require('../services/authService');

exports.register = async (req, res) => {
  const { role_id, name, email, password, phone_number } = req.body;

  if (!role_id || !name || !email || !password) {
    return res.status(400).json({ message: 'Semua field wajib diisi.' });
  }

  try {
    const [existingUser] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
    if (existingUser.length > 0) {
      return res.status(409).json({ message: 'Email sudah terdaftar.' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    await db.query(
      'INSERT INTO users (role_id, name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)',
      [role_id, name, email, hashedPassword, phone_number || null]
    );

    return res.status(201).json({ message: 'Pendaftaran berhasil!' });
  } catch (error) {
    console.error('Register Error:', error);
    return res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
  }
};

exports.login = async (req, res) => {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).json({ message: 'Email dan password wajib diisi.' });
  }

  try {
    const [rows] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
    if (rows.length === 0) {
      return res.status(401).json({ message: 'Email atau password salah.' });
    }

    const user = rows[0];

    const passwordMatch = await bcrypt.compare(password, user.password);
    if (!passwordMatch) {
      return res.status(401).json({ message: 'Email atau password salah.' });
    }

    // Buat token JWT, sesuaikan secret dan expirasi
    const token = jwt.sign(
      { id: user.id, email: user.email, role_id: user.role_id },
      process.env.JWT_SECRET || 'secretkey', // simpan secret di .env nanti ya
      { expiresIn: '1h' }
    );

    return res.json({
      message: 'Login berhasil',
      token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        role_id: user.role_id,
        phone_number: user.phone_number,
      }
    });
  } catch (error) {
    console.error('Login Error:', error);
    return res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
  }
};
