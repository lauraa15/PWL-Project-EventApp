const jwt = require('jsonwebtoken');

const verifyToken = (req, res, next) => {
  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return res.status(401).json({ message: 'Unauthorized' });
  }

  const token = authHeader.split(' ')[1];
  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'SECRET_KEY');
    req.user = decoded; // token harus berisi { id, role_id, ... }
    next();
  } catch (err) {
    console.error('JWT error:', err.message);
    res.status(401).json({ message: 'Invalid token' });
  }
};

const requireRoleId = (roleId) => {
  return (req, res, next) => {
    if (!req.user || req.user.role_id !== roleId) {
      return res.status(403).json({ message: 'Akses ditolak.' });
    }
    next();
  };
};

module.exports = {
  verifyToken,
  requireRoleId,
};
