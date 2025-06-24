const express = require('express');
const router = express.Router();

const userController = require('../controllers/userController');
const { verifyToken } = require('../middlewares/authMiddleware'); 
const roleMiddleware = require('../middlewares/roleMiddleware');  

router.use(verifyToken);

router.get('/', userController.getAllUsers);

router.post('/', roleMiddleware, userController.createUser);
router.put('/:id', roleMiddleware, userController.updateUser);
router.delete('/:id', roleMiddleware, userController.deleteUser);

router.patch('/:id/toggle', userController.toggleUserStatus);



module.exports = router;
