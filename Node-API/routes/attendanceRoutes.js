const express = require('express');
const router = express.Router();
const { scanAttendance } = require('../controllers/attendanceController');
const { verifyToken } = require('../middlewares/authMiddleware');

router.post('/scan', verifyToken, scanAttendance);

module.exports = router;
