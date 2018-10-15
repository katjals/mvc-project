<html lang="en">
<head>
</head>
<body>
<header>
    <div class="navbar">
        <a href="?controller=bikes&action=registerBikeForm">Registrer ny cykel</a>
    </div>
</header>
<div class="container">
    <h1>Mine cykler</h1>
    <hr>
    
    
    <?php
    if ($bikes){
        foreach($bikes as $bike){ ?>
            <p>
                &#128690;
                <?php echo $bike->title; ?>,
                <?php echo $bike->postalCode; ?>,
                <?php echo $bike->description; ?>,
                <?php echo $bike->price; ?> kr pr dag.
            </p>
        <?php }
    } else {  ?>
        Du har endnu ikke registreret en cykel.
    <?php } ?>
    
    
</div>

</body>
</html>
