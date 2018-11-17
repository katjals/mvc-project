<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>


  <div class="container">
    <h1>Om cyklen:</h1>
    <hr>
  
    <h3><?php echo $bike->title; ?></h3>
    <p><?php echo $bike->description; ?></p>
    <p>Placeret i postnummer: <?php echo $bike->postalCode; ?></p>
    <p>Pris pr. time: <?php echo $bike->price; ?> kr.</p>
  
    <br>
    <hr>
  
    <form method="post" action="?controller=bikes&action=book">
      <p>Lejen gælder for i dag kl. 12. Hvornår vil du aflevere cyklen?</p>
      <input
        type="text"
        id="basicDate"
        name="endDate"
        placeholder="Vælg afleverings tidspunkt"
        onChange="calculate_price(this.value);"
        data-input>
      
      <p>
        Samlet pris:
        <br>
        <e id="price"> Vælg slut tidspunkt for at få prisen i </e>
        kr.
      </p>
      <button type="submit" name="bikeId" value="<?php echo $bike->id; ?>" class="registerbtn">Book nu</button>
    </form>

  </div>
  
  <script>
    $("#basicDate").flatpickr({
      enableTime: true,     //date and time
      time_24hr: true,
      altInput: true,       //show format (altFormat) is different than saved format (dateFormat)
      altFormat: "d F, Y H:i",
      dateFormat: "Y-m-d H:i",
      minDate: "today",
      minuteIncrement: 30
    });
    
    function calculate_price(endTime) {
      var timeStart = new Date();
      timeStart.setHours(12,0,0,0);
      
      var timeEnd = new Date(endTime);
  
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
  </script>

</body>
</html>
