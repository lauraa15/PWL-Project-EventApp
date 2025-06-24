const express = require('express');
const router = express.Router();
const { register, login } = require('../controllers/authController');
const { verifyToken, requireRoleId } = require('../middlewares/authMiddleware');
const jwt = require('jsonwebtoken');


router.post('/register', register);
router.post('/login', login);
router.get('/verify', (req, res) => {
  const authHeader = req.headers.authorization;
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) return res.status(401).json({ message: 'Token tidak ditemukan' });

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'SECRET_KEY');
    return res.json({ valid: true, user: decoded });
  } catch (err) {
    return res.status(401).json({ message: 'Token tidak valid' });
  }
});

router.get('/admin/dashboard', verifyToken, requireRoleId(1), (req, res) => {
  res.json({ message: 'Dashboard Admin' });
});

router.get('/finance/dashboard', verifyToken, requireRoleId(2), (req, res) => {
  res.json({ message: 'Dashboard Finance' });
});

router.get('/organizer/dashboard', verifyToken, requireRoleId(3), (req, res) => {
  res.json({ message: 'Dashboard Organizer' });
});

router.get('/member/dashboard', verifyToken, requireRoleId(3), (req, res) => {
  res.json({ message: 'Dashboard Member' });
});

module.exports = router;
