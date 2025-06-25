require('dotenv').config(); // Untuk load environment variables
const jwt = require('jsonwebtoken');

const verifyToken = (req, res, next) => {
  const authHeader = req.headers.authorization;
  
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    console.log('Authorization header not found or invalid format');
    return res.status(401).json({ message: 'Unauthorized' });
  }

  const token = authHeader.split(' ')[1];
  console.log('Token received:', token ? 'Token present' : 'No token');
  
  try {
    // PERBAIKAN: Gunakan jwt.verify() yang benar, bukan parseJwt()
    const jwtSecret = process.env.JWT_SECRET || 'SECRET_KEY';
    const decoded = jwt.verify(token, jwtSecret);
    
    console.log('JWT verification successful for user:', decoded.id);
    
    // Validasi struktur token
    if (!decoded.id) {
      return res.status(401).json({ 
        message: 'Invalid token structure - missing user id' 
      });
    }
    
    req.user = decoded; // token harus berisi { id, role_id, ... }
    next();
    
  } catch (err) {
    console.error('JWT verification failed:', err.message);
    
    // Berikan response error yang lebih spesifik
    let errorMessage = 'Invalid token';
    
    switch (err.name) {
      case 'JsonWebTokenError':
        errorMessage = 'Malformed token';
        break;
      case 'TokenExpiredError':
        errorMessage = 'Token has expired';
        break;
      case 'NotBeforeError':
        errorMessage = 'Token not active yet';
        break;
      default:
        errorMessage = 'Token verification failed';
    }
    
    res.status(401).json({ message: errorMessage });
  }
};

const requireRoleId = (roleId) => {
  return (req, res, next) => {
    console.log('Checking role access. Required:', roleId, 'User role:', req.user?.role_id);
    
    if (!req.user) {
      return res.status(403).json({ 
        message: 'Akses ditolak - User not authenticated' 
      });
    }
    
    if (req.user.role_id !== roleId) {
      return res.status(403).json({ 
        message: 'Akses ditolak - Insufficient permissions',
        debug: process.env.NODE_ENV === 'development' ? 
          `Required role: ${roleId}, User role: ${req.user.role_id}` : undefined
      });
    }
    
    next();
  };
};

// TAMBAHAN: Helper function untuk generate token (gunakan saat login)
const generateToken = (user) => {
  if (!user || !user.id) {
    throw new Error('User object with id is required');
  }

  const payload = {
    id: user.id,
    role_id: user.role_id,
    email: user.email,
    name: user.name,
    iat: Math.floor(Date.now() / 1000)
  };
  
  const jwtSecret = process.env.JWT_SECRET || 'SECRET_KEY';
  
  if (jwtSecret === 'SECRET_KEY') {
    console.warn('WARNING: Using default JWT secret. Set JWT_SECRET in environment variables!');
  }
  
  const token = jwt.sign(payload, jwtSecret, { 
    expiresIn: process.env.JWT_EXPIRES_IN || '24h' 
  });
  
  console.log('Token generated for user:', user.id);
  return token;
};

// TAMBAHAN: Function untuk decode token tanpa verifikasi (untuk debugging)
const decodeToken = (token) => {
  try {
    return jwt.decode(token);
  } catch (err) {
    console.error('Failed to decode token:', err.message);
    return null;
  }
};

module.exports = {
  verifyToken,
  requireRoleId,
  generateToken,
  decodeToken
};