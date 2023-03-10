<?php
include('config.php');

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Get the room price from the database
    $sql = "SELECT price FROM rooms WHERE id = $room_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $price = $row['price'];
        echo $price;
    } else {
        // Room not found
        echo "Error: Room not found";
    }
} else {
    // Room ID not specified
    echo "Error: Room ID not specified";
}

$conn->close();
?>
