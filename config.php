<?php
// database credentials
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'hotel';

// create connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// check if the directory where we will save the room images is created
$IMAGES_SAVE_PATH = $_SERVER['DOCUMENT_ROOT'] . '/rooms_images/';

?>