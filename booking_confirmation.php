<!DOCTYPE html>
<html>
<head>
  <title>Booking Confirmation</title>
</head>
<body>
  <?php include 'navbar.php'; ?>
  <h1>Booking Confirmation</h1>

  <p>Your booking for room <?php echo $room_id; ?> is confirmed. Here are the details:</p>

  <p><strong>Check-in date:</strong> <?php echo $checkin_date; ?></p>
  <p><strong>Check-out date:</strong> <?php echo $checkout_date; ?></p>
  <p><strong>Price per night:</strong> $<?php echo $price_per_night; ?></p>
  <p><strong>Number of nights:</strong> <?php echo $days; ?></p>
  <p><strong>Total price:</strong> $<?php echo $total_price; ?></p>

  <p>Thank you for your booking! Your booking ID is <?php echo $booking_id; ?>.</p>

</body>
</html>