<html lang="en">
<head>
</head>
<body>

<div class="container">
  <h2>Cyklen er booket!</h2>
  
  <hr>
  
  <p>Du kan kontakte ejeren, <b><?php echo $user->name; ?></b>,
    for at aftale afhentning og betaling på <b><?php echo $user->phoneNumber; ?></b>. </p>
  <br>
  
  <p>Cyklen kan hentes på følgende adresse:</p>
  <p><?php echo $address->street; ?>, <?php echo $address->postalCode . " " . $address->city; ?>, <?php echo $address->country; ?></p>

</div>

</body>
</html>
