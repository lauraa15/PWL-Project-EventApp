const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const db = require('../config/db');
const authService = require('../services/authService');

exports.register = async (req, res) => {
  let { name, email, password, phone_number } = req.body;

  if (!name || !email || !password || !phone_number) {
    return res.status(400).json({ message: 'Semua field wajib diisi.' });
  }

  if (!/\S+@\S+\.\S+/.test(email)) {
    return res.status(400).json({ message: 'Format email tidak valid.' });
  }
  if (password.length < 6) {
    return res.status(400).json({ message: 'Password minimal 6 karakter.' });
  }

  email = email.toLowerCase();
  const role_id = 4; // Default ke Member

  try {
    const [existingUser] = await db.query('SELECT * FROM users WHERE email = ?', [email]);
    if (existingUser.length > 0) {
      return res.status(409).json({ message: 'Email sudah terdaftar.' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    const [result] = await db.query(
      'INSERT INTO users (role_id, name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)',
      [role_id, name, email, hashedPassword, phone_number]
    );

    return res.status(201).json({ message: 'Pendaftaran berhasil!', userId: result.insertId });
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

   const token = jwt.sign({
                            id: user.id,
                            name: user.name,
                            email: user.email,
                            role_id: user.role_id,
                            }, 'SECRET_KEY', { expiresIn: '1d' });

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
