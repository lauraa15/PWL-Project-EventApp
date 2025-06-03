const db = require('../config/db');
const bcrypt = require('bcrypt');

exports.registerUser = async ({ role_id, name, email, password, phone_number }) => {
  if (!role_id || !name || !email || !password) {
    throw new Error('All required fields must be provided');
  }

  const conn = await db.getConnection();
  try {
    const [existing] = await conn.query('SELECT id FROM users WHERE email = ?', [email]);
    if (existing.length > 0) {
      throw new Error('Email already registered');
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const [result] = await conn.query(
      `INSERT INTO users (role_id, name, email, password, phone_number) 
       VALUES (?, ?, ?, ?, ?)`,
      [role_id, name, email, hashedPassword, phone_number]
    );

    return {
      id: result.insertId,
      name,
      email,
      phone_number
    };
  } finally {
    conn.release();
  }
};
