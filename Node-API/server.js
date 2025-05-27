const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const db = require('./config/db');

const app = express();

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Database connection
db.getConnection()
  .then(conn => {
    console.log('Connected to MySQL database');
    conn.release();
  })
  .catch(err => {
    console.error('Database connection failed:', err);
    process.exit(1);
  });

// Routes
const authRoutes = require('./routes/authRoutes');
const eventRoutes = require('./routes/eventRoutes');
const registrationRoutes = require('./routes/registrationRoutes');
const paymentRoutes = require('./routes/paymentRoutes');
const attendanceRoutes = require('./routes/attendanceRoutes');
const certificateRoutes = require('./routes/certificateRoutes');

app.use('/api/auth', authRoutes);
app.use('/api/events', eventRoutes);
app.use('/api/registrations', registrationRoutes);
app.use('/api/payments', paymentRoutes);
app.use('/api/attendances', attendanceRoutes);
app.use('/api/certificates', certificateRoutes);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});

const testRoutes = require('./routes/testRoutes');
app.use('/api/test', testRoutes); // ✅ recommended
