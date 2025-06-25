const db = require('../config/db');
const QRCode = require('qrcode');
const path = require('path');
const fs = require('fs').promises;
const { v4: uuidv4 } = require('uuid');

// ========== KODE LAMA UNTUK ROLE 'FINANCE' (JANGAN DIUBAH) ==========
const getFinanceRegistrations = async (req, res) => {
    const eventId = req.query.event_id; // ambil dari query string ?event_id=3
    const financeId = req.user.id;

  if (!eventId) {
    return res.status(400).json({ message: 'Event ID wajib dikirim.' });
  }

  try {
    const [rows] = await db.query(`
      SELECT 
        r.*, 
        u.name AS user_name, 
        u.email, 
        e.name AS event_name
      FROM registrations r
      JOIN users u ON r.user_id = u.id
      JOIN events e ON r.event_id = e.id
      JOIN event_committees ec ON e.id = ec.event_id
      JOIN users cu ON ec.user_id = cu.id
      WHERE cu.id = ? AND cu.role_id = 2
    `, [financeId]);

    res.json({ success: true, data: rows });
  } catch (err) {
    console.error('Error fetching registrations for event:', err);
    res.status(500).json({ message: 'Gagal mengambil data registrasi' });
  }
};

const approvePayment = async (req, res) => {
  const { id } = req.params;

  try {
    console.log('1. Update payment status');
    await db.query('UPDATE payments SET status = ? WHERE registration_id = ?', ['confirmed', id]);

    console.log('2. Generate QR code');
    const qrText = `REGISTRATION-${id}`;
    const qrPath = `public/qrcodes/qr_${id}.png`;

    await QRCode.toFile(qrPath, qrText);

    console.log('3. Simpan QR ke tabel registrations');
    const qrRelativePath = `qrcodes/qr_${id}.png`;
    await db.query('UPDATE registrations SET qr_code = ?, status = ? WHERE id = ?', [qrRelativePath, 'confirmed', id]);

    res.json({ success: true, message: 'Pembayaran disetujui dan QR code dibuat.' });
  } catch (err) {
    console.error('❌ Approve error:', err); // PENTING: lihat console ini
    res.status(500).json({ message: 'Gagal menyetujui pembayaran.' });
  }
};

const rejectPayment = async (req, res) => {
  const { id } = req.params;
  try {
    await db.query('UPDATE payments SET status = ? WHERE registration_id = ?', ['cancelled', id]);
    await db.query('UPDATE registrations SET status = ? WHERE id = ?', ['cancelled', id]);

    res.json({ success: true, message: 'Pembayaran ditolak.' });
  } catch (err) {
    console.error('Reject error:', err);
    res.status(500).json({ message: 'Gagal menolak pembayaran.' });
  }
};

// ========== KODE BARU UNTUK FUNGSI TAMBAHAN ==========

// Upload receipt
const uploadReceipt = async (req, res) => {
  // console.log(req);
    try {
        // console.log("User adalah "+ user);
        const { event_id, user_id } = req.body;
        const userId = req.user?.id || 1;
        
        if (!req.file) {
            return res.status(400).json({
                success: false,
                message: 'No receipt file uploaded'
            });
        }

        if (!event_id) {
            return res.status(400).json({
                success: false,
                message: 'Event ID is required'
            });
        }

        // console.log(req.file);
        // Check if registration exists
        const [registrations] = await db.query(
            'SELECT * FROM registrations WHERE user_id = ? AND event_id = ?', 
            [userId, event_id]
        );
        
        if (!registrations || registrations.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'Registration not found'
            });
        }

        const registration = registrations[0];

        // Check if payment record exists
        const [payments] = await db.query(
            'SELECT * FROM payments WHERE registration_id = ?', 
            [registration.id]
        );

        let payment;
        if (!payments || payments.length === 0) {
            // Create new payment record
            let filePath = path.join('uploads/receipts', req.file.filename);
            // console.log('File path:', filePath);
            const [result] = await db.query(`
                INSERT INTO payments (registration_id, amount, status, notes, created_at, updated_at) 
                VALUES (?, ?, 'on-progress', ?, NOW(), NOW())
            `, [registration.id, 0, filePath]);
            
            // Get the created payment
            const [newPayment] = await db.query('SELECT * FROM payments WHERE id = ?', [result.insertId]);
            payment = newPayment[0];
        } else {
            let filePath = path.join('uploads/receipts', req.file.filename);
            payment = payments[0];
            
            // Update existing payment
            await db.query(`
                UPDATE payments 
                SET status = 'on-progress', notes = ?, updated_at = NOW() 
                WHERE id = ?
            `, [filePath, payment.id]);
            
            // Refresh payment data
            const [updatedPayment] = await db.query('SELECT * FROM payments WHERE id = ?', [payment.id]);
            payment = updatedPayment[0];
        }

        res.json({
            success: true,
            message: 'Receipt uploaded successfully',
            data: {
                payment_id: payment.id,
                status: payment.status,
                uploaded_at: payment.updated_at
            }
        });

    } catch (error) {
        console.error('Upload receipt error:', error);
        
        // Delete uploaded file if error occurs
        if (req.file) {
            try {
                await fs.unlink(req.file.path);
            } catch (unlinkError) {
                console.error('Error deleting file:', unlinkError);
            }
        }

        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Get QR Code for verified payment
const getQRCode = async (req, res) => {
    try {
        const { eventId } = req.params;
        const userId = req.user.id;

        // Find registration and payment with JOIN
        const [results] = await db.query(`
            SELECT r.*, p.status as payment_status, e.name as event_name
            FROM registrations r
            LEFT JOIN payments p ON r.id = p.registration_id
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ? AND r.event_id = ?
        `, [userId, eventId]);

        if (!results || results.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'Registration not found'
            });
        }

        const registration = results[0];

        // Check if payment is confirmed (sesuai status yang digunakan di kode lama)
        if (!registration.payment_status || registration.payment_status !== 'confirmed') {
            return res.status(403).json({
                success: false,
                message: 'Payment not verified yet'
            });
        }

        // Generate or get existing QR code
        let qrCodeData;
        
        if (!registration.qr_code) {
            // Create QR code data
            qrCodeData = {
                registration_id: registration.id,
                user_id: userId,
                event_id: eventId,
                token: uuidv4(),
                generated_at: new Date().toISOString()
            };

            // Update registration with QR code data
            await db.query(
                'UPDATE registrations SET qr_code = ? WHERE id = ?', 
                [JSON.stringify(qrCodeData), registration.id]
            );
        } else {
            // Parse existing QR code data (jika berupa JSON string)
            try {
                qrCodeData = JSON.parse(registration.qr_code);
            } catch (e) {
                // Jika bukan JSON, berarti format lama (path file)
                qrCodeData = {
                    registration_id: registration.id,
                    user_id: userId,
                    event_id: eventId,
                    qr_path: registration.qr_code,
                    generated_at: new Date().toISOString()
                };
            }
        }

        // Generate QR code image
        const qrCodeString = JSON.stringify(qrCodeData);
        const qrCodeBase64 = await QRCode.toDataURL(qrCodeString, {
            width: 300,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        });

        res.json({
            success: true,
            data: {
                qr_code: qrCodeBase64,
                event_name: registration.event_name,
                user_name: req.user.name,
                generated_at: qrCodeData.generated_at
            }
        });

    } catch (error) {
        console.error('Get QR code error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Get payment status for user's events
const getPaymentStatus = async (req, res) => {
    try {
        const userId = req.user.id;

        const [results] = await db.query(`
            SELECT r.event_id, e.name as event_name, 
                   COALESCE(p.status, 'pending') as payment_status,
                   p.id as payment_id, p.created_at as uploaded_at, p.updated_at as verified_at
            FROM registrations r
            LEFT JOIN payments p ON r.id = p.registration_id
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ?
        `, [userId]);

        const events = results.map(row => ({
            id: row.event_id,
            name: row.event_name,
            payment_status: row.payment_status,
            payment_id: row.payment_id,
            uploaded_at: row.uploaded_at,
            verified_at: row.payment_status === 'confirmed' ? row.verified_at : null
        }));

        res.json({
            success: true,
            data: {
                events: events
            }
        });

    } catch (error) {
        console.error('Get payment status error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Verify payment (Admin only) - menggunakan status 'confirmed' seperti kode lama
const verifyPayment = async (req, res) => {
    try {
        const { paymentId } = req.params;
        const adminId = req.user.id;

        // Check if user has admin role
        if (!req.user.roles || !req.user.roles.includes('admin')) {
            return res.status(403).json({
                success: false,
                message: 'Access denied. Admin role required.'
            });
        }

        // Check if payment exists
        const [payments] = await db.query('SELECT * FROM payments WHERE id = ?', [paymentId]);

        if (!payments || payments.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'Payment not found'
            });
        }

        // Update payment status to confirmed (sesuai kode lama)
        await db.query(`
            UPDATE payments 
            SET status = 'confirmed', notes = NULL, updated_at = NOW() 
            WHERE id = ?
        `, [paymentId]);

        // Get updated payment data
        const [updatedPayment] = await db.query('SELECT * FROM payments WHERE id = ?', [paymentId]);
        const payment = updatedPayment[0];

        res.json({
            success: true,
            message: 'Payment verified successfully',
            data: {
                payment_id: payment.id,
                status: payment.status,
                verified_at: payment.updated_at
            }
        });

    } catch (error) {
        console.error('Verify payment error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Reject payment with notes (Admin only) - menggunakan status 'cancelled' seperti kode lama
const rejectPaymentWithNotes = async (req, res) => {
    try {
        const { paymentId } = req.params;
        const { reason } = req.body;
        const adminId = req.user.id;

        // Check if user has admin role
        if (!req.user.roles || !req.user.roles.includes('admin')) {
            return res.status(403).json({
                success: false,
                message: 'Access denied. Admin role required.'
            });
        }

        // Check if payment exists
        const [payments] = await db.query('SELECT * FROM payments WHERE id = ?', [paymentId]);

        if (!payments || payments.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'Payment not found'
            });
        }

        // Update payment status to cancelled with reason (sesuai kode lama)
        await db.query(`
            UPDATE payments 
            SET status = 'cancelled', notes = ?, updated_at = NOW() 
            WHERE id = ?
        `, [reason || 'No reason provided', paymentId]);

        // Get updated payment data
        const [updatedPayment] = await db.query('SELECT * FROM payments WHERE id = ?', [paymentId]);
        const payment = updatedPayment[0];

        res.json({
            success: true,
            message: 'Payment rejected',
            data: {
                payment_id: payment.id,
                status: payment.status,
                rejected_at: payment.updated_at,
                rejection_reason: payment.notes
            }
        });

    } catch (error) {
        console.error('Reject payment error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Get payment details (Admin only)
const getPaymentDetails = async (req, res) => {
    try {
        const { paymentId } = req.params;

        // Check if user has admin role
        if (!req.user.roles || !req.user.roles.includes('admin')) {
            return res.status(403).json({
                success: false,
                message: 'Access denied. Admin role required.'
            });
        }

        // Get payment details with registration and event info
        const [results] = await db.query(`
            SELECT p.*, r.user_id, r.event_id, r.registration_code,
                   e.name as event_name, u.name as user_name, u.email as user_email
            FROM payments p
            JOIN registrations r ON p.registration_id = r.id
            JOIN events e ON r.event_id = e.id
            JOIN users u ON r.user_id = u.id
            WHERE p.id = ?
        `, [paymentId]);

        if (!results || results.length === 0) {
            return res.status(404).json({
                success: false,
                message: 'Payment not found'
            });
        }

        const paymentData = results[0];

        res.json({
            success: true,
            data: {
                payment: {
                    id: paymentData.id,
                    amount: paymentData.amount,
                    status: paymentData.status,
                    uploaded_at: paymentData.created_at,
                    verified_at: paymentData.status === 'confirmed' ? paymentData.updated_at : null,
                    rejected_at: paymentData.status === 'cancelled' ? paymentData.updated_at : null,
                    rejection_reason: paymentData.notes
                },
                event: {
                    id: paymentData.event_id,
                    name: paymentData.event_name
                },
                user: {
                    id: paymentData.user_id,
                    name: paymentData.user_name,
                    email: paymentData.user_email
                }
            }
        });

    } catch (error) {
        console.error('Get payment details error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

// Get all pending payments (Admin only)
const getPendingPayments = async (req, res) => {
    try {
        // Check if user has admin role
        if (!req.user.roles || !req.user.roles.includes('admin')) {
            return res.status(403).json({
                success: false,
                message: 'Access denied. Admin role required.'
            });
        }

        const [results] = await db.query(`
            SELECT p.*, r.registration_code, e.name as event_name, 
                   u.name as user_name, u.email as user_email
            FROM payments p
            JOIN registrations r ON p.registration_id = r.id
            JOIN events e ON r.event_id = e.id
            JOIN users u ON r.user_id = u.id
            WHERE p.status = 'pending'
            ORDER BY p.created_at DESC
        `);

        const pendingPayments = results.map(row => ({
            payment_id: row.id,
            amount: row.amount,
            status: row.status,
            uploaded_at: row.created_at,
            registration_code: row.registration_code,
            event_name: row.event_name,
            user_name: row.user_name,
            user_email: row.user_email
        }));

        res.json({
            success: true,
            data: {
                pending_payments: pendingPayments
            }
        });

    } catch (error) {
        console.error('Get pending payments error:', error);
        res.status(500).json({
            success: false,
            message: 'Internal server error',
            error: process.env.NODE_ENV === 'development' ? error.message : undefined
        });
    }
};

module.exports = {
    // Kode lama untuk Finance (JANGAN DIUBAH)
    getFinanceRegistrations,
    approvePayment,
    rejectPayment,
    
    // Kode baru untuk fungsi tambahan
    uploadReceipt,
    getQRCode,
    getPaymentStatus,
    verifyPayment,
    rejectPaymentWithNotes,
    getPaymentDetails,
    getPendingPayments
};