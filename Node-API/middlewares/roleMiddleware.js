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

// module.exports = (req, res, next) => {
//   const requesterRoleId = req.user?.role_id;

//   // Misal: hanya role_id 1 (Admin) yang boleh menambah user
//   if (requesterRoleId !== 1) {
//     return res.status(403).json({
//       message: 'Hanya admin yang diizinkan melakukan aksi ini.'
//     });
//   }

//   next();
// };