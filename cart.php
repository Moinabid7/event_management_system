<?php
	include('Database/connect.php');
	include('Database/advanced_email.php'); // Include advanced email functionality
	include('session.php');		
	include("header.php");
	$q=mysqli_query($con,"select * from temp");
	while($f=mysqli_fetch_row($q))
	{
		$id=$f[0];
		$image=$f[1];
		$name=$f[2];
		$price=$f[3];
	}
	if(isset($_POST['submit']))
	{
		$name=$_POST['nm'];
		$mo=$_POST['mo'];
		$date=$_POST['date'];
		
		// Get current user's email from registration table
		$current_username = $_SESSION['uname'];
		$user_query = mysqli_query($con, "SELECT email FROM registration WHERE unm='$current_username'");
		
		if(mysqli_num_rows($user_query) > 0) {
			$user_row = mysqli_fetch_assoc($user_query);
			$email = $user_row['email'];
		} else {
			echo "<script>alert('User not found! Please login again.');</script>";
			echo '<script type="text/javascript">window.location="login.php";</script>';
			exit;
		}	
		$q=mysqli_query($con,"select * from temp");
		$im="";
		$nm="";
		$pri=0;
		$r=mysqli_num_rows($q);
		
		// Check if temp table is empty
		if($r == 0) {
			echo "<script>alert('Please select a theme first! Go to Gallery → Choose Theme → Book Now');</script>";
			echo '<script type="text/javascript">window.location="gallery.php";</script>';
			exit;
		}
		
		// Process each item in temp table
		while($res=mysqli_fetch_array($q))
		{
				$id=$res[0];
				$im=$res[1];
				$nm=$res[2];
				$pri=$res[3];
				
				// Simple, direct insert query
				$q1=mysqli_query($con,"INSERT INTO booking(nm,email,mo,theme,thm_nm,price,date) VALUES('$name','$email','$mo','$im','$nm','$pri','$date')");
				if($q1)
				{
						// Prepare event details for email
						$eventDetails = array(
							'customer_name' => $name,
							'email' => $email,
							'mobile' => $mo,
							'theme_name' => $nm,
							'event_date' => $date,
							'price' => $pri,
							'theme_image' => $im
						);
						
						// Initialize advanced email sender
						$emailSender = new AdvancedEmailSender();
						
						// Send confirmation email to customer
						$customerEmailSent = $emailSender->sendBookingConfirmation($email, $name, $eventDetails);
						
						// Send notification email to admin
						$adminEmailSent = $emailSender->sendAdminNotification($eventDetails);
						
						// Log the booking
						$emailSender->logBooking($eventDetails);
						
						// Show success message with email status
						if($customerEmailSent && $adminEmailSent) {
							echo "<script>alert('🎉 Your Event is Booked Successfully! \\n✅ Confirmation emails have been sent to both you and admin.');</script>";
						} else if($customerEmailSent) {
							echo "<script>alert('🎉 Your Event is Booked Successfully! \\n✅ Confirmation email sent to your inbox.');</script>";
						} else if($adminEmailSent) {
							echo "<script>alert('🎉 Your Event is Booked Successfully! \\n✅ Admin has been notified.');</script>";
						} else {
							echo "<script>alert('🎉 Your Event is Booked Successfully! \\n⚠️ Email notifications may be delayed.');</script>";
						}
						
						// Clear temp table after successful booking
						mysqli_query($con, "DELETE FROM temp");
						echo '<script type="text/javascript">window.location="index.php";</script>';
				}
				else
				{
						echo "<script>alert('Booking Failed! Please try again.');</script>";
				}
		}
	}		
	$qry=mysqli_query($con,"select * from temp where id=".$id);
	$row=mysqli_fetch_row($qry);	
?>

<script>
$(function()
{
	$("#datepicker").datepicker
	({
		changeMonth:true,
		changeYear:true
	});
});
</script>

<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">BOOKING</h3>
<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="nm" pattern="[A-Za-z\s]{2,30}" title="Only Letter For Name" id="focusedinput" placeholder="Name" required="">
								</div>
							</div>

							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm">Mobile no</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1 input-sm" name="mo" id="smallinput" pattern="([7-9]{1})+([0-9]{9})" title="Only Number" maxlength="10" placeholder="Mobile no" required=""/>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Your Theme :</label>
								<div class="col-sm-8">
								<img src="./images/<?php echo $row[1]; ?>" height="200"  width="300"/></div>		
							</div>
							<div class="form-group">
								<label for="disabledinput" class="col-sm-2 control-label">Theme Name :</label>
								<div class="col-sm-8">
									<input disabled="" type="text" class="form-control1" value="<?php echo $row[2]; ?>" name="price" id="focusedinput" placeholder="Theme Price" >
								</div>
							</div>
							<div class="form-group">
								<label for="disabledinput" class="col-sm-2 control-label">Theme Price :</label>
								<div class="col-sm-8">
								<input disabled="" type="text" class="form-control1" value="<?php echo $row[3]; ?>" name="price" id="focusedinput" placeholder="Theme Price" >
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm">Event Date</label>
								<div class="col-sm-8">
									<input type="date" class="form-control1 input-sm" name="date" id="smallinput" placeholder="DD/MM/YYYY" required=""/>
								</div>
							</div>
					<div class="contact-w3form" align="center">
					<a href="book.php">
					<input type="submit" name="submit" class="btn"  value="BOOK"></a>
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
<?php 
	include_once("footer.php");
?>