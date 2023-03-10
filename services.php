<!DOCTYPE html>
<html>
<head>
	<title>Services</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
	<div class="container">
		<h2>Here you could have the look on the services we're providing</h2>
		
		<h2>Built-in Services</h2>
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
                include('config.php'); 
				// Retrieve built-in services from the services table
				$result = mysqli_query($conn, "SELECT * FROM services WHERE price IS NULL");
				while ($row = mysqli_fetch_assoc($result)) {
					echo '<tr>';
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>$' . number_format($row['price'], 2) . '</td>';
					echo '</tr>';
				}
				mysqli_close($conn);
				?>
			</tbody>
		</table>
		
		<h2>Paid Services</h2>
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				<?php
                include('config.php');
				// Retrieve paid services from the services table
				$result = mysqli_query($conn, "SELECT * FROM services WHERE price IS NOT NULL");
				while ($row = mysqli_fetch_assoc($result)) {
					echo '<tr>';
					echo '<td>' . $row['name'] . '</td>';
					echo '<td>$' . number_format($row['price'], 2) . '</td>';
					echo '</tr>';
				}
				mysqli_close($conn);
				?>
			</tbody>
		</table>
	</div>
</body>
</html>