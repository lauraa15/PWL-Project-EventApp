const express = require('express');
const router = express.Router();
const { verifyToken, requireRoleId } = require('../middlewares/authMiddleware');
const paymentController = require('../controllers/paymentController');
const { getFinanceRegistrations } = paymentController;

router.use(verifyToken);
router.use(requireRoleId(2));
// GET /api/finance/registrations
router.get('/registrations', verifyToken, requireRoleId(2), getFinanceRegistrations);

router.patch('/:id/approve', paymentController.approvePayment);
router.patch('/:id/reject', paymentController.rejectPayment);
module.exports = router;
