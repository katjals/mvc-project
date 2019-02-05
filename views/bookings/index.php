<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Mine bookinger</h1>
  <hr>

<?php
if (!empty($bookings)){
  foreach($bookings as $booking){ ?>
    <p>
      &#128339;
      "<span class="coloredText"><?php echo $booking->bikeTitle; ?></span>"
      fra
      <span class="coloredText"><?php echo $booking->startTime; ?></span> til
      <span class="coloredText"><?php echo $booking->endTime; ?></span>
    </p>
  <?php }
} else { ?>
  Du har endnu ikke foretaget en booking. Du kan finde og booke cykler
  <a class="link" href="?controller=bikes&action=selectTime">her</a>.

<?php } ?>

</div>

</body>
</html>
