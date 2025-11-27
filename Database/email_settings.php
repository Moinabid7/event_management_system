<?php
// Email configuration file for Classic Events Management System

// Email Settings - Update these according to your requirements
define('ADMIN_EMAIL', 'your_admin_email@example.com'); // Change to your admin email
define('COMPANY_NAME', 'Classic Events Management');
define('COMPANY_EMAIL', 'noreply@classicevents.com'); // Change to your company email
define('COMPANY_PHONE', '+91-XXXXXXXXXX'); // Change to your phone number

// SMTP Settings (recommended for XAMPP and production)
define('SMTP_ENABLED', true); // Set to true to use SMTP (recommended)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your_email@example.com'); // Your Gmail address
define('SMTP_PASSWORD', 'abc'); // Your Gmail app password

// Email Templates Configuration
define('SEND_CUSTOMER_EMAIL', true); // Set to false to disable customer emails
define('SEND_ADMIN_EMAIL', true); // Set to false to disable admin emails
define('LOG_BOOKINGS', true); // Set to false to disable booking logs

// Debug Mode (set to true for testing)
define('EMAIL_DEBUG', true); // Shows email status messages
?>