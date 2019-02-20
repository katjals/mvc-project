<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Mine bookinger</h1>
  <hr>

<?php
if (!empty($previousBookings) || !empty($futureBookings)){
  
  if (!empty($futureBookings)){ ?>
    <h3>Fremtidige bookinger</h3><?php
    foreach($futureBookings as $booking){ ?>
      <p>
        &#128339;
        "<span class="coloredText"><?php echo $booking->bikeTitle; ?></span>"
        fra
        <span class="coloredText"><?php echo $booking->startTime; ?></span> til
        <span class="coloredText"><?php echo $booking->endTime; ?></span>
      </p><?php
    }
  }
  
  if (!empty($previousBookings)){ ?>
    <h3>Tidligere bookinger</h3><?php
    foreach($previousBookings as $booking){ ?>
      <p>
        &#128339;
        "<span class="coloredText"><?php echo $booking->bikeTitle; ?></span>"
        fra
        <span class="coloredText"><?php echo $booking->startTime; ?></span> til
        <span class="coloredText"><?php echo $booking->endTime; ?></span>.
        <a class="link" href="?controller=ratings&action=createRating&id=<?php echo $booking->id; ?>">Rate</a>
      </p><?php
    }
  }
} else { ?>
  Du har endnu ikke foretaget en booking. Du kan finde og booke cykler
  <a class="link" href="?controller=bikes&action=selectTime">her</a>.<?php
} ?>

</div>

</body>
</html>
