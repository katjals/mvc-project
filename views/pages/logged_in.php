<html>
<head>
</head>
<body>
<header>
    <div class="navbar headnav">
        <a href="?controller=users&action=logout">Log ud</a>
        <?php
        if (GenericCode::checkUserPermission(["renter"], true)){ ?>
          <a href="?controller=bikes&action=selectTime">Cykler til leje</a>
        <?php }
        if (GenericCode::checkUserPermission(["owner"], true)){ ?>
          <a href="?controller=bikes&action=myBikes">Mine cykler</a>
        <?php }
        if (GenericCode::checkUserPermission(["renter"], true)){ ?>
          <a href="?controller=bookings&action=index">Mine bookinger</a>
        <?php } ?>
    </div>
</header>
</body>
</html>

