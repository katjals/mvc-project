<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h2>Info om cyklen:</h2>
  <hr>
  
  <h3><?php echo $bike->title; ?></h3>
  <h4>Placeret i postummer: <?php echo $bike->postalCode; ?></h4>
  <p><?php echo $bike->description; ?></p>
  <p>Pris pr time: <?php echo $bike->price; ?> kr</p>

</div>

</body>
</html>
