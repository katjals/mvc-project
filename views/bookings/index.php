<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Mine bookinger</h1>
<hr>

<?php foreach($bookings as $booking){ ?>
    <p>
        &#128690;
        <?php echo $bookings->startTime; ?>,
        <?php echo $bookings->bike['title']; ?>
    </p>
<?php } ?>
</div>

</body>
</html>
