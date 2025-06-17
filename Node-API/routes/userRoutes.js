const express = require('express');
const router = express.Router(); // ✅ ini bagian yang kurang
const userController = require('../controllers/userController');
const { verifyToken } = require('../middlewares/authMiddleware');

router.get('/users', verifyToken, userController.getAllUsers);

module.exports = router;
