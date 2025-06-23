const express = require('express');
const router = express.Router();

const userController = require('../controllers/userController');
const { verifyToken } = require('../middlewares/authMiddleware'); // ✅
const roleMiddleware = require('../middlewares/roleMiddleware');  // ✅ function

// ✅ HARUS TANPA KURUNG KURAWAL
router.use(verifyToken);

router.get('/', userController.getAllUsers);
// CRUD user khusus finance dan organizer
router.post('/', roleMiddleware, userController.createUser);
router.put('/:id', roleMiddleware, userController.updateUser);
router.delete('/:id', roleMiddleware, userController.deleteUser);

// Semua user bisa akses

module.exports = router;
