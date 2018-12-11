<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<form method="post" action="?controller=bikes&action=register">
    <div class="container">
        <h1>Rediger ladcykel</h1>
        <hr>
        <input type="hidden" name="id" value="<?php echo $bike->id; ?>" >
    
        <label for="title"><b>Beskrivende Titel</b></label>
        <input type="text" placeholder="Udfyld titel" name="title" value="<?php echo $bike->title; ?>" required>
        
        <label for="description"><b>Beskriv den med flere ord</b></label>
        <input type="text" placeholder="Udfyld beskrivelse" name="description" value="<?php echo $bike->description; ?>" required>
        
        <label for="price"><b>Pris i kr pr. time</b></label>
        <input type="number" placeholder="Udfyld pris" name="price" value="<?php echo $bike->price; ?>" required>
        
        <label for="postalCode"><b>Cyklens placering</b></label>
        <input type="number" placeholder="Udfyld postnummer" name="postalCode" value="<?php echo $bike->postalCode; ?>" required>
        <hr>
        <button type="submit" name="submit" class="registerbtn" >Rediger</button>
    </div>
</form>

</body>
</html>
