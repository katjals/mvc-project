<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h2>Cyklen er booket!</h2>
  <hr>
  <p>Den kan hentes på følgende adresse:</p>
  <p><?php echo $address->street; ?>,</p>
  <p><?php echo $address->postalCode . " " . $address->city; ?>,</p>
  <p><?php echo $address->country; ?></p>
  
  <br>
  
  <p>Du kan kontakte ejeren, <b><?php echo $user->name; ?></b>,
    for at aftale afhentning og betaling på <b><?php echo $user->phoneNumber; ?></b>. </p>

</div>

</body>
</html>
