<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<form method="post" action="?controller=ratings&action=saveRating">
    <div class="container">
        <h1>Rate cyklen og bookingen</h1>
        <hr>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" >
    
        <label for="score"><b>Score</b></label>
        <input type="number" min="0" max="5" step="1" name="score" required/>
        
        <label for="comment"><b>Kommentar</b></label>
        <input type="text" placeholder="Giv eventuelt en uddybende kommentar" name="comment">
        
        <hr>
        <button type="submit" name="submit" class="registerbtn" >Opret</button>
    </div>
</form>

</body>
</html>
