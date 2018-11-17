<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Cykler, som er ledige nu</h1>
  <p>Cyklerne er sorteret efter dit postnummer: <?php echo $postalCode ?></p>
  <hr>
    
    <?php foreach($bikes as $bike){ ?>
      <p>
        &#128690;
        <?php echo $bike->title; ?>,
        <?php echo $bike->postalCode; ?>
        <a class="link" href="?controller=bikes&action=show&id=<?php echo $bike->id; ?>">Læs mere</a>
      </p>
    <?php } ?>
</div>

</body>
</html>
