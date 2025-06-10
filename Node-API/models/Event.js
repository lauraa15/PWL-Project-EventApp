const mongoose = require('mongoose');

const eventSchema = new mongoose.Schema({
    event_type_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'EventType',
        required: true
    },
    name: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
    start_date: {
        type: Date,
        required: true
    },
    end_date: {
        type: Date,
        required: true
    },
    location: {
        type: String,
        required: true
    },
    poster_image: {
        type: String
    },
    registration_fee: {
        type: Number,
        default: 0
    },
    registration_type: {
        type: String,
        enum: ['event_only', 'session_only', 'both'],
        required: true
    },
    max_participants: {
        type: Number,
        required: true
    },
    current_participants: {
        type: Number,
        default: 0
    },
    registration_open_date: {
        type: Date,
        required: true
    },
    registration_close_date: {
        type: Date,
        required: true
    },
    certificate_type: {
        type: String,
        enum: ['per_event', 'per_session'],
        required: true
    },
    is_active: {
        type: Boolean,
        default: true
    },
    created_by: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    }
}, {
    timestamps: true
});

// Virtual for checking if event is full
eventSchema.virtual('isFull').get(function() {
    return this.current_participants >= this.max_participants;
});

// Virtual for checking if registration is open
eventSchema.virtual('isRegistrationOpen').get(function() {
    const now = new Date();
    return now >= this.registration_open_date && now <= this.registration_close_date;
});

const Event = mongoose.model('Event', eventSchema);

module.exports = Event;