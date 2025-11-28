<?php
// Advanced email sender with PHPMailer support for Classic Events Management System
// This version supports real SMTP sending and fallback to PHP mail()

// Include email settings
require_once __DIR__ . '/email_settings.php';

// Include PHPMailer files if they exist
if (file_exists(__DIR__ . '/../phpmailer/src/PHPMailer.php')) {
    require_once __DIR__ . '/../phpmailer/src/Exception.php';
    require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../phpmailer/src/SMTP.php';
}

// Use statements must be at the top level
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AdvancedEmailSender {
    
    private $mail;
    
    public function __construct() {
        if (SMTP_ENABLED && class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $this->setupPHPMailer();
        }
    }
    
    private function setupPHPMailer() {
        try {
            if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                throw new Exception('PHPMailer class not found');
            }
            $this->mail = new PHPMailer(true);
            
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = SMTP_HOST;
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = SMTP_USERNAME;
            $this->mail->Password   = SMTP_PASSWORD;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = SMTP_PORT;
            
            // Default sender
            $this->mail->setFrom(COMPANY_EMAIL, COMPANY_NAME);
            $this->mail->isHTML(true);
            
            if (EMAIL_DEBUG) {
                error_log("PHPMailer configured successfully");
            }
        } catch (Exception $e) {
            if (EMAIL_DEBUG) {
                error_log("PHPMailer configuration error: " . $e->getMessage());
            }
        }
    }
    
    public function sendBookingConfirmation($customerEmail, $customerName, $eventDetails) {
        if (!SEND_CUSTOMER_EMAIL) {
            if (EMAIL_DEBUG) error_log("Customer email disabled in settings");
            return false;
        }
        
        try {
            $subject = 'Event Booking Confirmation - ' . COMPANY_NAME;
            $message = $this->getCustomerEmailTemplate($customerName, $eventDetails);
            
            // Use PHPMailer if available and SMTP is enabled
            if (SMTP_ENABLED && isset($this->mail)) {
                return $this->sendWithPHPMailer($customerEmail, $customerName, $subject, $message);
            }
            
            // Fallback to PHP mail()
            $headers = $this->getEmailHeaders();
            $result = mail($customerEmail, $subject, $message, $headers);
            
            if (EMAIL_DEBUG) {
                if ($result) {
                    error_log("Customer email sent via PHP mail() to: " . $customerEmail);
                } else {
                    error_log("Failed to send customer email via PHP mail() to: " . $customerEmail);
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            if (EMAIL_DEBUG) error_log("Error sending customer email: " . $e->getMessage());
            return false;
        }
    }
    
    public function sendAdminNotification($eventDetails) {
        if (!SEND_ADMIN_EMAIL) {
            if (EMAIL_DEBUG) error_log("Admin email disabled in settings");
            return false;
        }
        
        try {
            $subject = 'New Event Booking - ' . COMPANY_NAME;
            $message = $this->getAdminEmailTemplate($eventDetails);
            
            // Use PHPMailer if available and SMTP is enabled
            if (SMTP_ENABLED && isset($this->mail)) {
                return $this->sendWithPHPMailer(ADMIN_EMAIL, 'Admin', $subject, $message);
            }
            
            // Fallback to PHP mail()
            $headers = $this->getEmailHeaders();
            $result = mail(ADMIN_EMAIL, $subject, $message, $headers);
            
            if (EMAIL_DEBUG) {
                if ($result) {
                    error_log("Admin email sent via PHP mail() to: " . ADMIN_EMAIL);
                } else {
                    error_log("Failed to send admin email via PHP mail() to: " . ADMIN_EMAIL);
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            if (EMAIL_DEBUG) error_log("Error sending admin email: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendWithPHPMailer($email, $name, $subject, $message) {
        try {
            // Clear previous recipients
            $this->mail->clearAddresses();
            
            // Recipients
            $this->mail->addAddress($email, $name);
            
            // Content
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;
            
            $this->mail->send();
            
            if (EMAIL_DEBUG) {
                error_log("Email sent via PHPMailer to: " . $email);
            }
            
            return true;
        } catch (Exception $e) {
            if (EMAIL_DEBUG) {
                error_log("PHPMailer error: " . $e->getMessage());
            }
            return false;
        }
    }
    
    private function getEmailHeaders() {
        return "From: " . COMPANY_NAME . " <" . COMPANY_EMAIL . ">\r\n" .
               "Reply-To: " . COMPANY_EMAIL . "\r\n" .
               "MIME-Version: 1.0\r\n" .
               "Content-Type: text/html; charset=UTF-8\r\n" .
               "X-Mailer: PHP/" . phpversion();
    }
    
    private function getCustomerEmailTemplate($customerName, $eventDetails) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #f4f4f4; padding: 20px; text-align: center; border-bottom: 3px solid #007cba; }
                .content { padding: 30px 20px; }
                .booking-details { background-color: #f9f9f9; padding: 20px; border-left: 4px solid #007cba; margin: 20px 0; }
                .footer { background-color: #333; color: white; padding: 15px; text-align: center; }
                .highlight { color: #007cba; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='color: #007cba; margin: 0;'>" . COMPANY_NAME . "</h2>
                    <h3 style='color: #666; margin: 10px 0 0 0;'>Booking Confirmation</h3>
                </div>
                <div class='content'>
                    <p>Dear <span class='highlight'>{$customerName}</span>,</p>
                    <p>Thank you for choosing <strong>" . COMPANY_NAME . "</strong>! Your event booking has been <span class='highlight'>successfully confirmed</span>.</p>
                    
                    <div class='booking-details'>
                        <h4 style='color: #007cba; margin-top: 0;'>Booking Details:</h4>
                        <p><strong>Event Theme:</strong> {$eventDetails['theme_name']}</p>
                        <p><strong>Event Date:</strong> {$eventDetails['event_date']}</p>
                        <p><strong>Price:</strong> " . number_format($eventDetails['price']) . "</p>
                        <p><strong>Customer Name:</strong> {$eventDetails['customer_name']}</p>
                        <p><strong>Contact Number:</strong> {$eventDetails['mobile']}</p>
                        <p><strong>Email:</strong> {$eventDetails['email']}</p>
                    </div>
                    
                    <p>We are excited to make your event memorable and special!</p>
                    <p>Our team will contact you within 24-48 hours to discuss further details about your event planning.</p>
                    <p>If you have any questions or special requests, please feel free to contact us.</p>
                    
                    <p style='margin-top: 30px;'>Best regards,<br><strong>" . COMPANY_NAME . " Team</strong></p>
                </div>
                <div class='footer'>
                    <p style='margin: 0;'>&copy; 2025 " . COMPANY_NAME . ". All rights reserved.</p>
                    <p style='margin: 5px 0 0 0;'>Contact: " . COMPANY_EMAIL . " | Phone: " . COMPANY_PHONE . "</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getAdminEmailTemplate($eventDetails) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #007cba; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .booking-details { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                .alert { background-color: #ff4444; color: white; padding: 10px; text-align: center; margin-bottom: 20px; border-radius: 5px; }
                .highlight { color: #007cba; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin: 0;'>New Event Booking Alert</h2>
                </div>
                <div class='content'>
                    <div class='alert'>
                        <strong>URGENT: New Booking Received!</strong>
                    </div>
                    
                    <p>Dear Admin,</p>
                    <p>A new event booking has been made on the <strong>" . COMPANY_NAME . "</strong> system.</p>
                    
                    <div class='booking-details'>
                        <h4 style='color: #007cba; margin-top: 0;'>Customer & Booking Details:</h4>
                        <p><strong>Customer Name:</strong> <span class='highlight'>{$eventDetails['customer_name']}</span></p>
                        <p><strong>Email:</strong> {$eventDetails['email']}</p>
                        <p><strong>Mobile:</strong> {$eventDetails['mobile']}</p>
                        <p><strong>Event Theme:</strong> {$eventDetails['theme_name']}</p>
                        <p><strong>Event Date:</strong> <span class='highlight'>{$eventDetails['event_date']}</span></p>
                        <p><strong>Total Price:</strong> <span class='highlight'>" . number_format($eventDetails['price']) . "</span></p>
                        <p><strong>Booking Time:</strong> " . date('Y-m-d H:i:s') . "</p>
                    </div>
                    
                    <p><strong>Action Required:</strong> Please login to the admin panel to view more details and manage this booking.</p>
                    <p><strong>Follow-up:</strong> Contact the customer within 24-48 hours to confirm and discuss event details.</p>
                    
                    <p style='margin-top: 30px;'>Best regards,<br><strong>" . COMPANY_NAME . " System</strong></p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    // Log booking for admin tracking
    public function logBooking($eventDetails) {
        if (!LOG_BOOKINGS) {
            return false;
        }
        
        try {
            $logFile = __DIR__ . '/booking_logs.txt';
            $logEntry = date('Y-m-d H:i:s') . " - New Booking: {$eventDetails['customer_name']} ({$eventDetails['email']}) - {$eventDetails['theme_name']} - {$eventDetails['price']}\n";
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            
            if (EMAIL_DEBUG) {
                error_log("Booking logged: {$eventDetails['customer_name']} - {$eventDetails['theme_name']}");
            }
            return true;
        } catch (Exception $e) {
            if (EMAIL_DEBUG) {
                error_log("Error logging booking: " . $e->getMessage());
            }
            return false;
        }
    }
}
?>