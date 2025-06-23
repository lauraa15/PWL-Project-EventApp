const roleMiddleware = (req, res, next) => {
    const allowedRoles = ['finance', 'organizer'];
    const targetRole = req.body.role_name?.toLowerCase();

    if (!allowedRoles.includes(targetRole)) {
        return res.status(403).json({
            message: 'Anda hanya dapat mengelola user dengan role Finance atau Organizer.'
        });
    }

    next();
};

module.exports = roleMiddleware;