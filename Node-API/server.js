
const axios = require('axios');

axios.get('http://localhost:8000/test-connection')
  .then(response => {
    console.log('Laravel says:', response.data);
  })
  .catch(error => {
    console.error('Cannot connect to Laravel API:', error.message);
  });
  
const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const db = require('./config/db');

const app = express();

app.use(cors({ origin: 'http://localhost:8000' }));
// app.use(cors());
app.use(bodyParser.json());


db.getConnection()
  .then(conn => {
    console.log('Connected to MySQL database');
    conn.release();
  })
  .catch(err => {
    console.error('Database connection failed:', err);
    process.exit(1);
  });


const authRoutes = require('./routes/authRoutes');
const eventRoutes = require('./routes/eventRoutes');
const registrationRoutes = require('./routes/registrationRoutes');
const paymentRoutes = require('./routes/paymentRoutes');
const attendanceRoutes = require('./routes/attendanceRoutes');
const certificateRoutes = require('./routes/certificateRoutes');
const testRoutes = require('./routes/testRoutes'); 

app.use('/api/auth', authRoutes);
app.use('/api/events', eventRoutes);
app.use('/api/registrations', registrationRoutes);
app.use('/api/payments', paymentRoutes);
app.use('/api/attendances', attendanceRoutes);
app.use('/api/certificates', certificateRoutes);
app.use('/api/test', testRoutes); 


const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
