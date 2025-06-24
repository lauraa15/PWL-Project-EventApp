const express = require('express');
const router = express.Router();

const eventController = require('../controllers/eventController');
const { verifyToken } = require('../middlewares/authMiddleware');

router.use(verifyToken);

router.get('/', eventController.getAllEvents);
router.patch('/:id/toggle', eventController.toggleEventStatus);


module.exports = router;
