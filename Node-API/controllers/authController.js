require('dotenv').config();
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
  const { email, phone, password } = req.body;

  // Cek field wajib
  if ((!email && !phone) || !password) {
    return res.status(400).json({
      success: false,
      message: 'Email atau Nomor HP dan password wajib diisi.'
    });
  }

  try {
    // Pilih field untuk login
    const field = email ? 'email' : 'phone_number';
    const identifier = email || phone;

    // Cari user berdasarkan email atau no hp
    const [rows] = await db.query(`SELECT * FROM users WHERE ${field} = ?`, [identifier]);
    if (rows.length === 0) {
      return res.status(401).json({
        success: false,
        message: 'Email/Nomor HP atau password salah.'
      });
    }

    const user = rows[0];

    // Cek password
    const passwordMatch = await bcrypt.compare(password, user.password);
    if (!passwordMatch) {
      return res.status(401).json({
        success: false,
        message: 'Email/Nomor HP atau password salah.'
      });
    }

    // Buat token dengan data lengkap agar tidak perlu query ulang
    const token = jwt.sign({
      id: user.id,
      name: user.name,
      email: user.email,
      phone_number: user.phone_number,
      role_id: user.role_id,
      created_at: user.created_at // opsional
    }, process.env.JWT_SECRET || 'SECRET_KEY', { expiresIn: '1d' });

    // Kirim respons sukses dengan user info
    return res.status(200).json({
      success: true,
      message: 'Login berhasil',
      token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        phone_number: user.phone_number,
        role_id: user.role_id
      }
    });

  } catch (error) {
    console.error('Login Error:', error);
    return res.status(500).json({
      success: false,
      message: 'Terjadi kesalahan pada server.'
    });
  }
};

