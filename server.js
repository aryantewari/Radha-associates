// server.js
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config();

const Enquiry = require('./models/Enquiry'); // Import our schema
console.log("Imported Model Data:", Enquiry);

const app = express();

// Middleware
app.use(cors());
app.use(express.json()); // Parses incoming JSON requests

// MongoDB Connection
// Apni .env file mein MONGO_URI variable daalna mat bhoolna!
// MongoDB Connection (Naye Mongoose mein options ki zaroorat nahi hai)
mongoose.connect(process.env.MONGO_URI)
.then(() => console.log('MongoDB Connected to Legal Audience DB!'))
.catch(err => console.error('MongoDB connection error:', err));

// POST Route to handle Form Submissions
app.post('/api/contact', async (req, res) => {
    try {
        const { fullName, phone, email, practiceArea, message } = req.body;

        // Validation - Make sure all fields are present
        if (!fullName || !phone || !email || !practiceArea || !message) {
            return res.status(400).json({ error: 'Please fill in all fields.' });
        }

        // Save to Database
        const newEnquiry = new Enquiry({
            fullName,
            phone,
            email,
            practiceArea,
            message
        });

        await newEnquiry.save();

        res.status(201).json({ message: 'Enquiry submitted successfully!' });
    } catch (error) {
        console.error('Error saving enquiry:', error);
        res.status(500).json({ error: 'Server error, please try again later.' });
    }
});

// Start Server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});