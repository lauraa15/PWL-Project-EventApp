const express = require('express');
const router = express.Router();
const { register, login } = require('../controllers/authController');
const { verifyToken, requireRoleId } = require('../middleware/authmiddleware');


router.post('/register', register);
router.post('/login', login);

router.get('/admin/dashboard', verifyToken, requireRoleId(1), (req, res) => {
  res.json({ message: 'Dashboard Admin' });
});

router.get('/panitia/dashboard', verifyToken, requireRoleId(2), (req, res) => {
  res.json({ message: 'Dashboard Panitia' });
});

router.get('/mahasiswa/dashboard', verifyToken, requireRoleId(3), (req, res) => {
  res.json({ message: 'Dashboard Mahasiswa' });
});

module.exports = router;
