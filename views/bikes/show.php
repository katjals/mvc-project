<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>


  <div class="container">
    
    <h1>Vil du booke <?php echo $bike->title; ?>?</h1>
    <hr>
    <p><?php echo $bike->description; ?></p>
    <p>Starttidspunkt: <?php echo $startDate; ?></p>
    <p>Sluttidspunkt: <?php echo $endDate; ?></p>
    <p>Samlet pris: <e id="price"> Ikke beregnet i </e> kr.</p>
  
    <br>
    <hr>
  
    <form method="post" action="?controller=bikes&action=book">

      <input type="hidden" name="endDate" value="<?php echo $endDate; ?>">
     
      <input type="hidden" name="startDate" value="<?php echo $startDate; ?>">
      
      <button type="submit" name="bikeId" value="<?php echo $bike->id; ?>" class="registerbtn">Book nu</button>
    </form>

  </div>
  
  <script>
    function calculate_price()
    {
      var timeStart = new Date( "<?php echo $startDate; ?>" );
      var timeEnd = new Date("<?php echo $endDate; ?>");
  
      var hours = diff_hours(timeStart, timeEnd);
  
      var pricePrHour =  "<?php echo $bike->price; ?>";
      var price = hours * pricePrHour;
      
      document.getElementById("price").innerHTML = price.toString();
    }

    function diff_hours(dt2, dt1)
    {
      var diff =(dt2.getTime() - dt1.getTime()) / 1000;
      diff /= (60 * 60);
      return Math.abs(Math.round(diff));
    }

    window.onpaint = calculate_price();

  </script>

</body>
</html>
