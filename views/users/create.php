<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<form method="post" action="?controller=users&action=create">
    <div class="container">
        <h1>Opret en bruger</h1>
        <p>Opret en bruger for at leje og udleje ladcykler.</p>
        <hr>
        
        <label for="name"><b>Navn</b></label>
        <input type="text" placeholder="Udfyld navn" name="name" required>
        
        <label for="phone"><b>Telefonnummer</b></label>
        <input type="number" placeholder="Udfyld dansk telefonnummer" name="phoneNumber" required>
        
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Udfyld email" name="email" required>
        
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Udfyld password" name="password" required>
      
        <label for="roles"><b>Brugertype</b></label><br><br>
        <input type="checkbox" name="roles[]" value="owner"> Jeg vil udleje mine cykler<br><br>
        <input type="checkbox" name="roles[]" value="renter"> Jeg vil leje andres cykler<br>
        <!--      gemmes på typen vehicle=Bike&vehicle=Car-->
      
      <hr>
        <button type="submit" name="submit" class="registerbtn" >Opret</button>
    </div>
</form>

</body>
</html>
