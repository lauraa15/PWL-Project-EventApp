const express = require('express');
const router = express.Router();
const registrationController = require('../controllers/registrationController');

router.post('/:eventId/register', registrationController.register);

module.exports = router;
