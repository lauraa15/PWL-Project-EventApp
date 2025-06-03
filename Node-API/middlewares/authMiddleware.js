const jwt = require('jsonwebtoken');

const verifyToken = (req, res, next) => {
  const token = req.headers.authorization?.split(' ')[1]; // "Bearer <token>"
  if (!token) return res.status(401).json({ message: 'Unauthorized' });

  try {
    const decoded = jwt.verify(token, 'SECRET_KEY');
    req.user = decoded; // { id, name, role_id, ... }
    next();
  } catch (err) {
    res.status(401).json({ message: 'Invalid token' });
  }
};

// Cek role_id secara langsung
const requireRoleId = (roleId) => {
  return (req, res, next) => {
    if (req.user.role_id !== roleId) {
      return res.status(403).json({ message: 'Akses ditolak.' });
    }
    next();
  };
};

module.exports = {
  verifyToken,
  requireRoleId,
};
