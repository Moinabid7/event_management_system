<?php
	include_once('Database/connect.php');
	include_once('session.php');		
	include_once("header.php");
	
	// Get current user's username from session
	$currentUsername = $_SESSION['uname'];
	
	// Get user's email from registration table
	$userQuery = mysqli_query($con, "SELECT email FROM registration WHERE unm='$currentUsername'");
	
	if(mysqli_num_rows($userQuery) == 0) {
		echo "<script>alert('User not found! Please login again.'); window.location.href='login.php';</script>";
		exit;
	}
	
	$userRow = mysqli_fetch_assoc($userQuery);
	$currentUserEmail = $userRow['email'];
	
	// Fetch user's bookings using email
	$bookingsQuery = mysqli_query($con, "SELECT * FROM booking WHERE email='$currentUserEmail' ORDER BY id DESC");
	$bookingCount = mysqli_num_rows($bookingsQuery);
?>

<div class="banner about-bnr">
	<div class="container">
		<h2 class="w3ls-hdg" style="color: white; text-align: center;">My Bookings</h2>
	</div>
</div>

<div class="codes">
	<div class="container"> 
		<h3 class='w3ls-hdg' align="center">📋 My Event Bookings</h3>
		
		<?php if($bookingCount > 0) { ?>
			<div class="alert alert-info">
				<strong>📊 Total Bookings:</strong> <?php echo $bookingCount; ?> | 
				<strong>👤 Account:</strong> <?php echo $currentUserEmail; ?> (<?php echo $currentUsername; ?>)
			</div>
			
			<div class="grid_3 grid_5">
				<br/>
				<div class="table-responsive">
					<table class='table table-bordered table-striped'>
						<thead class='thead-dark'>
							<tr style="background-color: #007cba; color: white;">
								<th>🆔 Booking ID</th>
								<th>🎨 Theme</th>
								<th>📝 Theme Name</th>
								<th>💰 Price</th>
								<th>📅 Event Date</th>
								<th>📞 Status</th>
								<th>🧾 Invoice</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							while($booking = mysqli_fetch_assoc($bookingsQuery)) {
								// Determine booking status based on event date
								$eventDate = $booking['date'];
								$currentDate = date('Y-m-d');
								$isUpcoming = strtotime($eventDate) > time();
								$statusBadge = $isUpcoming ? 
									'<span class="badge" style="background-color: #28a745; color: white;">📅 Upcoming</span>' : 
									'<span class="badge" style="background-color: #ffc107; color: black;">✅ Completed</span>';
							?>
								<tr>
									<td><strong>#<?php echo $booking['id']; ?></strong></td>
									<td>
										<?php if(!empty($booking['theme'])) { ?>
											<img src="images/<?php echo $booking['theme']; ?>" 
												 alt="Theme" 
												 style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
										<?php } else { ?>
											<div style="width: 80px; height: 60px; background-color: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
												<span style="color: #666;">🎭 No Image</span>
											</div>
										<?php } ?>
									</td>
									<td><strong><?php echo htmlspecialchars($booking['thm_nm']); ?></strong></td>
									<td><strong style="color: #007cba;"><?php echo number_format($booking['price']); ?></strong></td>
									<td>
										<?php echo date('d M Y', strtotime($booking['date'])); ?><br>
										<small style="color: #666;"><?php echo date('l', strtotime($booking['date'])); ?></small>
									</td>
									<td><?php echo $statusBadge; ?></td>
									<td>
										<a href="generate_invoice.php?booking_id=<?php echo $booking['id']; ?>" 
										   target="_blank" 
										   class="btn btn-primary btn-sm"
										   style="background-color: #007cba; border: none; padding: 8px 15px; border-radius: 5px; text-decoration: none; color: white;">
											🧾 Generate Invoice
										</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				
				<div class="row" style="margin-top: 30px;">
					<div class="col-md-6">
						<div class="alert alert-success">
							<h4>💡 Need Help?</h4>
							<p>📞 Call us: +92 3145046418</p>
							<p>📧 Email: moinabid007@gmail.com</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="alert alert-info">
							<h4>📋 Invoice Features</h4>
							<p>✅ View detailed booking information</p>
							<p>🖨️ Print invoice</p>
							<p>💾 Save as PDF</p>
						</div>
					</div>
				</div>
			</div>
			
		<?php } else { ?>
			<div class="alert alert-warning text-center">
				<h4>📭 No Bookings Found</h4>
				<p>You haven't made any bookings yet.</p>
				<br>
				<a href="gallery.php" class="btn btn-primary" style="background-color: #007cba; border: none; padding: 12px 30px;">
					🎉 Book Your First Event
				</a>
			</div>
		<?php } ?>
		
	</div>
</div>

<style>
.table th {
	text-align: center;
	vertical-align: middle;
}
.table td {
	text-align: center;
	vertical-align: middle;
}
.badge {
	padding: 5px 10px;
	border-radius: 15px;
	font-size: 12px;
}
.btn:hover {
	transform: translateY(-1px);
	transition: all 0.2s;
}
.alert {
	border-radius: 8px;
}
.table-responsive {
	box-shadow: 0 2px 10px rgba(0,0,0,0.1);
	border-radius: 8px;
	overflow: hidden;
}
</style>

<?php 
	include_once("footer.php");
?>