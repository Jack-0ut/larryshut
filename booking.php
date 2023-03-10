<?php
include('config.php');
include('templates/base.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_GET['room_id'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $price = $_POST['price'];
    $stmt = $conn->prepare("
        INSERT INTO bookings (room_id, guest_name, guest_email, checkin_date, checkout_date, price, booking_date)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param('dssssd', $room_id, $guest_name, $guest_email, $checkin_date, $checkout_date, $price);

    if ($stmt->execute()) {
        header('Location: /success_booking.php');
        exit();
    } else {
        $error = 'Error: ' . $stmt->error;
    }
}

$room_id = $_GET['room_id'];
$stmt = $conn->prepare("
    SELECT *
    FROM rooms
    WHERE id = ?
");
$stmt->bind_param('d', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
?>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <div style="text-align: center;">
            <h2>Book Room <?php echo $room['room_number']; ?></h2>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="guest_name">Guest Name:</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" required>
            </div>
            <div class="form-group">
                <label for="guest_email">Guest Email:</label>
                <input type="email" class="form-control" id="guest_email" name="guest_email" required>
            </div>
            <div class="form-group">
                <label for="checkin_date">Check-in Date:</label>
                <input type="date" class="form-control" id="checkin_date" name="checkin_date" oninput="updatePrice()" required>
            </div>
            <div class="form-group">
                <label for="checkout_date">Check-out Date:</label>
                <input type="date" class="form-control" id="checkout_date" name="checkout_date" oninput="updatePrice()" required>
            </div>
            <input type="hidden" id="price" name="price" value="">
            <div class="form-group" id="total-price"></div>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary mt-3">Book Room</button>
            </div>
        </form>
        </div>
    </div>
</div>


<script>

function updatePrice() {
  // Get the check-in and check-out dates
  var checkinDate = new Date(document.getElementById("checkin_date").value);
  var checkoutDate = new Date(document.getElementById("checkout_date").value);

  // Calculate the number of nights
  var oneDay = 24 * 60 * 60 * 1000; // hours * minutes * seconds * milliseconds
  var numNights = Math.round(Math.abs((checkoutDate - checkinDate) / oneDay));

  // Get the room ID from the URL
  var urlParams = new URLSearchParams(window.location.search);
  var roomID = urlParams.get('room_id');

  // Fetch the room price from the server
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "get_room_price.php?id=" + roomID, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var roomPrice = parseFloat(xhr.responseText);

      // Calculate the total price
      var totalPrice = numNights * roomPrice;

      // Update the total price element
      document.getElementById("total-price").innerHTML = "Total price: $" + totalPrice.toFixed(2);
      document.getElementById("price").value = totalPrice.toFixed(2); 
    }
  };
  xhr.send();
}

</script>
</html>