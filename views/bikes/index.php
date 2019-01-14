<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h1>Cykler, som er ledige nu</h1>
  <p>Cyklerne er sorteret efter dit postnummer: <?php echo $postalCode ?></p>
  <hr>
    
    <?php
    if (!empty($bikes)){
    foreach($bikes as $bike){ ?>
      <p>
        &#128690;
          <?php echo $bike->title; ?>,
          <?php echo $bike->postalCode; ?>
        <a class="link" href="?controller=bikes&action=getBike&id=<?php echo $bike->id; ?>&page=book">Læs mere</a>
      </p>
    <?php }
    } else { ?>
      Der er ingen ledige cykler.
    <?php } ?>
    
</div>

</body>
</html>
