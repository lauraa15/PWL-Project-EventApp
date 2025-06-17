const express = require('express');
const router = express.Router();

const userController = require('../controllers/userController');
const { verifyToken } = require('../middlewares/authMiddleware'); // ✅ ini benar
const roleMiddleware = require('../middlewares/roleMiddleware'); // ✅ function langsung

router.use(verifyToken); // ✅ middleware global

// CRUD user hanya untuk role 'finance' dan 'organizer'
router.post('/', roleMiddleware, userController.createUser);
router.put('/:id', roleMiddleware, userController.updateUser);
router.delete('/:id', roleMiddleware, userController.deleteUser);

// Get all users — semua role bisa asal sudah login
router.get('/users', userController.getAllUsers);

module.exports = router;
