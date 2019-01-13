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
                <?php echo $bike->description; ?>,
                <?php echo $bike->price; ?> kr. pr. dag.
                <a class="link" href="?controller=bikes&action=getBike&id=<?php echo $bike->id; ?>&page=edit">Rediger</a>
            </p>
        <?php }
    } else {  ?>
        Du har endnu ikke registreret en cykel.
        Du kan oprette dine cykler <a class="link" href="?controller=bikes&action=registerBikeForm">her</a>.
    <?php } ?>
    
    
</div>

</body>
</html>
