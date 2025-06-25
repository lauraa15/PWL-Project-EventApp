const express = require('express');
const router = express.Router();
const multer = require('multer');
const path = require('path');
const registrationController = require('../controllers/registrationController');
const paymentController = require('../controllers/paymentController');
const { verifyToken, requireRoleId } = require('../middlewares/authMiddleware');


// Configure multer for file upload
const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, 'uploads/receipts/') // Make sure this directory exists
    },
    filename: function (req, file, cb) {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, 'receipt-' + uniqueSuffix + path.extname(file.originalname));
    }
});

const fileFilter = (req, file, cb) => {
    // Check file type
    if (file.mimetype.startsWith('image/')) {
        cb(null, true);
    } else {
        cb(new Error('Only image files are allowed!'), false);
    }
};

const upload = multer({
    storage: storage,
    limits: {
        fileSize: 5 * 1024 * 1024 // 5MB limit
    },
    fileFilter: fileFilter
});

// Routes
router.post('/:eventId/register', registrationController.register);
router.post('/upload-receipt', verifyToken, upload.single('receipt'), paymentController.uploadReceipt);
router.get('/qr-code/:eventId', verifyToken, paymentController.getQRCode);
router.get('/payment-status', verifyToken, paymentController.getPaymentStatus);
router.put('/verify-payment/:paymentId', verifyToken, paymentController.verifyPayment); // For admin role
router.put('/reject-payment/:paymentId', verifyToken, paymentController.rejectPayment); // For admin role

module.exports = router;
