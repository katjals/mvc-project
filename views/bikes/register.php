<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<form method="post" action="?controller=bikes&action=register">
    <div class="container">
        <h1>Opret en ladcykel</h1>
        <p>Opret en ladcykel som andre kan leje.</p>
        <hr>
        
        <label for="title"><b>Beskrivende Titel</b></label>
        <input type="text" placeholder="Udfyld titel" name="title" required>
        
        <label for="description"><b>Beskriv den med flere ord</b></label>
        <input type="text" placeholder="Udfyld beskrivelse" name="description" required>
        
        <label for="price"><b>Pris i kr pr. dag</b></label>
        <input type="number" placeholder="Udfyld pris" name="price" required>
        
        <label for="postalCode"><b>Cyklens placering</b></label>
        <input type="number" placeholder="Udfyld postnummer" name="postalCode" required>
        <hr>
        <button type="submit" name="submit" class="registerbtn" >Opret</button>
    </div>
</form>

</body>
</html>
