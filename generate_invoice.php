<?php
	include_once('Database/connect.php');
	include_once('session.php');
	
	// Get booking ID from URL
	$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
	$currentUsername = $_SESSION['uname'];
	
	// Get user's email from registration table
	$userQuery = mysqli_query($con, "SELECT email FROM registration WHERE unm='$currentUsername'");
	
	if(mysqli_num_rows($userQuery) == 0) {
		echo "<script>alert('User not found! Please login again.'); window.close();</script>";
		exit;
	}
	
	$userRow = mysqli_fetch_assoc($userQuery);
	$currentUserEmail = $userRow['email'];
	
	// Fetch booking details for the current user only
	$bookingQuery = mysqli_query($con, "SELECT * FROM booking WHERE id='$bookingId' AND email='$currentUserEmail'");
	
	if(mysqli_num_rows($bookingQuery) == 0) {
		echo "<script>alert('Invalid booking or access denied!'); window.close();</script>";
		exit;
	}
	
	$booking = mysqli_fetch_assoc($bookingQuery);
	
	// Generate invoice number
	$invoiceNumber = "INV-" . date('Y') . "-" . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
	$invoiceDate = date('d M Y');
	$eventDate = date('d M Y', strtotime($booking['date']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoiceNumber; ?> - Classic Events</title>
    <link href="css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #007cba, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .invoice-body {
            padding: 30px;
        }
        .company-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .invoice-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .event-theme {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #007cba;
            border-radius: 10px;
            background-color: #f0f8ff;
        }
        .theme-image {
            max-width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin: 10px auto;
            display: block;
        }
        .price-highlight {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .print-buttons {
            text-align: center;
            margin: 30px 0;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .btn-print {
            background-color: #007cba;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-print:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 124, 186, 0.05);
            z-index: -1;
            pointer-events: none;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-buttons {
                display: none;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">CLASSIC EVENTS</div>
    
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-logo">🎉</div>
            <h1 style="margin: 0; font-size: 28px;">CLASSIC EVENTS</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Premium Event Management Services</p>
            <hr style="border-color: rgba(255,255,255,0.3); margin: 20px 0;">
            <h2 style="margin: 0;">INVOICE</h2>
            <p style="margin: 5px 0 0 0;">Invoice #<?php echo $invoiceNumber; ?></p>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="invoice-details">
                        <h4 style="color: #007cba; margin-bottom: 15px;">📋 Invoice Details</h4>
                        <p><strong>Invoice Number:</strong> <?php echo $invoiceNumber; ?></p>
                        <p><strong>Invoice Date:</strong> <?php echo $invoiceDate; ?></p>
                        <p><strong>Booking ID:</strong> #<?php echo $booking['id']; ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-details">
                        <h4 style="color: #007cba; margin-bottom: 15px;">👤 Customer Details</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['nm']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($booking['mo']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Event Theme Section -->
            <div class="event-theme">
                <h3 style="color: #007cba; margin-bottom: 20px;">🎨 Event Theme Details</h3>
                <?php if(!empty($booking['theme'])) { ?>
                    <img src="images/<?php echo $booking['theme']; ?>" alt="Event Theme" class="theme-image">
                <?php } ?>
                <h4 style="margin: 15px 0; color: #333;"><?php echo htmlspecialchars($booking['thm_nm']); ?></h4>
                <p style="color: #666; margin: 0;">📅 Event Date: <strong><?php echo $eventDate; ?></strong></p>
            </div>

            <!-- Pricing Details -->
            <div class="table-responsive" style="margin: 30px 0;">
                <table class="table table-bordered">
                    <thead style="background-color: #007cba; color: white;">
                        <tr>
                            <th>Description</th>
                            <th width="120">Quantity</th>
                            <th width="150">Unit Price</th>
                            <th width="150">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($booking['thm_nm']); ?> - Event Package</strong><br>
                                <small style="color: #666;">Complete event management and decoration</small>
                            </td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: right;"><?php echo number_format($booking['price']); ?></td>
                            <td style="text-align: right;"><strong><?php echo number_format($booking['price']); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total Price -->
            <div class="price-highlight">
                <h3 style="margin: 0;">💰 Total Amount: <?php echo number_format($booking['price']); ?></h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9;">All taxes and charges included</p>
            </div>

            <!-- Terms & Conditions -->
            <div style="margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                <h5 style="color: #007cba; margin-bottom: 15px;">📜 Terms & Conditions</h5>
                <ul style="margin: 0; padding-left: 20px; color: #666;">
                    <li>Payment confirmation required 48 hours before event date</li>
                    <li>Cancellation charges may apply as per company policy</li>
                    <li>All decorations will be set up 4 hours before event time</li>
                    <li>Any changes in requirements must be communicated 72 hours in advance</li>
                    <li>Final setup may vary slightly based on venue constraints</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div style="text-align: center; margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                <h5 style="color: #007cba; margin-bottom: 15px;">📞 Contact Information</h5>
                <p style="margin: 5px 0;"><strong>Phone:</strong> +92 3145046418</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> moinabid007@gmail.com</p>
                <!-- <p style="margin: 5px 0;"><strong>Website:</strong> www.classicevents.com</p> -->
            </div>

            <!-- Print Buttons -->
            <div class="print-buttons">
                <button onclick="window.print()" class="btn-print">
                    🖨️ Print Invoice
                </button>
                <button onclick="downloadPDF()" class="btn-print">
                    💾 Save as PDF
                </button>
                <button onclick="window.close()" class="btn-print" style="background-color: #6c757d;">
                    ❌ Close
                </button>
            </div>
        </div>

        <!-- Invoice Footer -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
            <p style="margin: 0; color: #666;">Thank you for choosing Classic Events! 🎉</p>
            <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">
                This is a computer-generated invoice. Generated on <?php echo date('d M Y, h:i A'); ?>
            </p>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Hide print buttons temporarily
            document.querySelector('.print-buttons').style.display = 'none';
            
            // Open print dialog (user can save as PDF)
            window.print();
            
            // Show buttons again after print dialog
            setTimeout(() => {
                document.querySelector('.print-buttons').style.display = 'block';
            }, 1000);
        }

        // Auto-focus for better printing experience
        window.onload = function() {
            window.focus();
        };
    </script>
</body>
</html>