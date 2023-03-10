<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larry's Hut</title>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

</head>

</head>
<body>
<?php
  include('config.php');
  // Prepare a query to retrieve all the rooms and their images
  $query = "
  SELECT rooms.*, room_images.image_url
  FROM rooms
  LEFT JOIN room_images ON rooms.id = room_images.room_id
  ";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Fetch all the rooms and their images into an associative array
  $rooms = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $room_id = $row['id'];
    if (!isset($rooms[$room_id])) {
        $rooms[$room_id] = array(
            'id' => $room_id,
            'room_type' => $row['room_type'],
            'description' => $row['description'],
            'price' => $row['price'],
            'images' => array()
        );
    }
    if ($row['image_url']) {
        $rooms[$room_id]['images'][] = array('image_url' => $row['image_url']);
    }
  }
  $rooms = array_values($rooms);
?>

<header>
  <?php include 'navbar.php'; ?>
  <!-- Heading Section -->
  <div class="container mt-5">
      <div class="row">
      <div class="col-md-12 text-center">
          <h1>This is Larry's Hut</h1>
          <p>It's a normal place, we got the bathrooms,beds, TVs and Larry's doing great chicken and pancakes</p>
          <p>If you already want it, don't lose the moment, choose the room and click on the 'Book the room' Button</p>
      </div>
      </div>
  </div>
</header>

<section>
  <div class="container">
    <div class="row text-center">
      <div class="col-md-12">
        <h2>Our Rooms</h2>
        <p>Take a look at our available rooms</p>
        <!-- Sorting Tool -->
        <div class="form-group">
          <label for="sort-by">Sort by:</label>
          <select class="form-control" id="sort-by">
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
          </select>
        </div>
      </div>
    </div>

    <div class="card-deck row">
      <?php foreach ($rooms as $room) : ?>
        <div class="col-md-6">
          <div class="card">
            <div id="slider-<?php echo $room['id']; ?>" class="carousel slide card-img-slider" data-ride="carousel">
              <ol class="carousel-indicators">
                <?php $image_counter = 0; ?>
                <?php foreach ($room['images'] as $image) : ?>
                  <li data-target="#slider-<?php echo $room['id']; ?>" data-slide-to="<?php echo $image_counter; ?>" <?php echo ($image_counter == 0) ? 'class="active"' : ''; ?>></li>
                  <?php $image_counter++; ?>
                <?php endforeach; ?>
              </ol>
              <div class="carousel-inner">
                <?php $image_counter = 0; ?>
                <?php foreach ($room['images'] as $image) : ?>
                  <div class="carousel-item <?php echo ($image_counter == 0) ? 'active' : ''; ?>">
                    <img src="<?php echo $image['image_url']; ?>" class="d-block w-100" alt="<?php echo $room['room_type']; ?> Photo">
                  </div>
                  <?php $image_counter++; ?>
                <?php endforeach; ?>

                <!-- Allow to slide forward and backward through the images-->
              </div>
              <a class="carousel-control-prev" href="#slider-<?php echo $room['id']; ?>" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              </a>
              <a class="carousel-control-next" href="#slider-<?php echo $room['id']; ?>" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
              </a>
            </div>

            <!--Here we show information about the hotel room -->
            <div class="card-body">
              <h5 class="card-title"><?php echo $room['room_type']; ?></h5>
              <p class="card-description"><?php echo $room['room_description']; ?></p>
              <p class="card-text">$<?php echo $room['price']; ?> per night</p>
              <a href="booking.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary float-right">Book the room</a>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
</div>
</section>

<style>
.card-deck {
  display: flex;
  flex-wrap: wrap;
}

.card-img-slider {
  height: 300px;
  overflow: hidden;
}

.card-img-slider .carousel-item img {
  height: 300px;
  object-fit: cover;
  width: 100%;
}
</style>

</body>
<script>
// For slider image display
$(document).ready(function(){
  $('.card-img-slider').on('init', function(){
    $(this).closest('.carousel').css('display', 'block');
  });
  
  $('.card-img-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    dots: true,
    infinite: true,
    adaptiveHeight: true
  });
});

// Get the room card deck element
const roomCardDeck = document.querySelector('.card-deck');

// Get the select element for sorting
const sortBySelect = document.querySelector('#sort-by');

// Listen for changes to the select element
sortBySelect.addEventListener('change', () => {
  // Get the selected value from the select element
  const selectedValue = sortBySelect.value;

  // Sort the room listings based on the selected value
  if (selectedValue === 'price-asc') {
    sortRoomsByPriceAsc();
  } else if (selectedValue === 'price-desc') {
    sortRoomsByPriceDesc();
  }
});

// Function to sort the room listings by price (low to high)
function sortRoomsByPriceAsc() {
  const roomCards = Array.from(roomCardDeck.children);
  roomCards.sort((a, b) => {
    const aPrice = parseInt(a.querySelector('.card-text').textContent.substring(1));
    const bPrice = parseInt(b.querySelector('.card-text').textContent.substring(1));
    return aPrice - bPrice;
  });
  roomCards.forEach(card => roomCardDeck.appendChild(card));
}

// Function to sort the room listings by price (high to low)
function sortRoomsByPriceDesc() {
  const roomCards = Array.from(roomCardDeck.children);
  roomCards.sort((a, b) => {
    const aPrice = parseInt(a.querySelector('.card-text').textContent.substring(1));
    const bPrice = parseInt(b.querySelector('.card-text').textContent.substring(1));
    return bPrice - aPrice;
  });
  roomCards.forEach(card => roomCardDeck.appendChild(card));
}
</script>
</html>