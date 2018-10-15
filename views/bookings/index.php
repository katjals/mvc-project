<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Mine bookinger</h1>
  <hr>

<?php foreach($bookings as $booking){ ?>
    <p>
      &#128339; Booking af cyklen
      <b><?php echo $booking->title; ?></b>
      fra
      <b><?php echo $booking->startTime; ?></b> til
      <b><?php echo $booking->endTime; ?></b>
    </p>
<?php } ?>
</div>

</body>
</html>
