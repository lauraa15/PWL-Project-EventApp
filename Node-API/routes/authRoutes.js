const express = require('express');
const router = express.Router();
const { register, login } = require('../controllers/authController');
const { verifyToken, requireRoleId } = require('../middleware/authmiddleware');


router.post('/register', register);
router.post('/login', login);

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
