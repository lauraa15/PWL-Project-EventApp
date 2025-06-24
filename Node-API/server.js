require('dotenv').config();

const axios = require('axios');
const express = require('express');
const cors = require('cors');
const db = require('./config/db');
const authRoutes = require('./routes/authRoutes');
const eventRoutes = require('./routes/eventRoutes');
const registrationRoutes = require('./routes/registrationRoutes');
const paymentRoutes = require('./routes/paymentRoutes');
const attendanceRoutes = require('./routes/attendanceRoutes');
const certificateRoutes = require('./routes/certificateRoutes');
const testRoutes = require('./routes/testRoutes');
const userRoutes = require('./routes/userRoutes');


const app = express();

// ✅ Middleware (harus ditulis SEBELUM routes)
app.use(cors({
  origin: ['http://localhost:8000', 'http://127.0.0.1:8000'], // list origin yang diizinkan
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS','PATCH'],
  allowedHeaders: ['Content-Type', 'Authorization'],
  credentials: true
}));
app.use(express.json()); // <=== penting agar bisa baca JSON
app.use(express.urlencoded({ extended: true }));
app.use('/api/users', userRoutes);
// app.use(cors()); 
// app.use(cors({ origin: 'http://localhost:8000' }));

// ✅ Cek koneksi ke Laravel (opsional)
axios.get('http://localhost:8000/test-connection')
  .then(response => {
    console.log('Laravel says:', response.data);
  })
  .catch(error => {
    console.error('Cannot connect to Laravel API:', error.message);
  });

// ✅ Cek koneksi DB
db.getConnection()
  .then(conn => {
    console.log('Connected to MySQL database');
    conn.release();
  })
  .catch(err => {
    console.error('Database connection failed:', err);
    process.exit(1);
  });

// ✅ Routes
app.use('/api/auth', authRoutes);
app.use('/api/events', eventRoutes);
app.use('/api/registrations', registrationRoutes);
app.use('/api/payments', paymentRoutes);
app.use('/api/attendances', attendanceRoutes);
app.use('/api/certificates', certificateRoutes);
app.use('/api/test', testRoutes);

// ✅ Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
