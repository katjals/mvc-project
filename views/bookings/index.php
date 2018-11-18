<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Mine bookinger</h1>
  <hr>

<?php foreach($bookings as $booking){ ?>
    <p>
      &#128339;
      "<span class="coloredText"><?php echo $booking->title; ?></span>"
      fra
      <span class="coloredText"><?php echo $booking->startTime; ?></span> til
      <span class="coloredText"><?php echo $booking->endTime; ?></span>
    </p>
<?php } ?>
</div>

</body>
</html>
