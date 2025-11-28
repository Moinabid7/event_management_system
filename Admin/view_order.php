<?php
				include_once('../Database/connect.php');
				include_once('session.php');
				include_once("header.php");
				$list=mysqli_query($con,"select * from booking");
				echo "<div class='codes'>
				<div class='container'>
				<h3 class='w3ls-hdg' align='center'>BOOKING ORDERS</h3>
				<div class='alert alert-info'>
					<strong>📧 Email Notifications:</strong> Confirmation emails are automatically sent to customers and admin when bookings are made.
				</div>
				<div class='grid_3 grid_5 '><br/>
					<table class='table table-bordered table-striped' >
						<thead class='thead-dark'>
							<tr>
								<th>Booking ID</th>
								<th>Customer Name</th>
								<th>Email</th>
								<th>Mobile No</th>
								<th>Theme Preview</th>
								<th>Theme Name</th>
								<th>Price (₹)</th>
								<th>Event Date</th>
								<th>Actions</th>
							</tr>
						</thead>";
						
				while($q = mysqli_fetch_row($list))
				{
					$eventDate = date('d M Y', strtotime($q[7]));
					$isUpcoming = strtotime($q[7]) > time();
					$statusBadge = $isUpcoming ? '<span class="badge badge-success">Upcoming</span>' : '<span class="badge badge-warning">Past</span>';
					
					echo '<tbody><tr> 
							<td><span class="badge badge-primary">#'.$q[0].'</span></td>
							<td><strong>'.$q[1].'</strong></td>
							<td><a href="mailto:'.$q[2].'">'.$q[2].'</a></td>
							<td><a href="tel:'.$q[3].'">'.$q[3].'</a></td>
							<td><img src="../images/'.$q[4].'" height="100" width="150" class="img-thumbnail"></td>
							<td>'.$q[5].'</td>
							<td>₹'.number_format($q[6]).'</td>
							<td>'.$eventDate.' '.$statusBadge.'</td>
							<td>';
				?>
				<a href="delete_book.php?id=<?php echo $q[0];?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this booking?')">🗑️ Delete</a>
				</td></tr><?php } ?>
				</tbody></table></div></div></div>";
		<?php
				include_once("footer.php");
?>