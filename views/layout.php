<DOCTYPE html>
    <html>
    <head>
      <title>Lej en ladcykel</title>
      <style>
          <?php require_once('stylesheet.css') ?>
      </style>
    </head>
    <body>
        <header>
            <a href="/../Hobby-projekt/mvc-project/">Home</a>
            <a href="?controller=bikes&action=index">Bikes</a>
            <a href="?controller=users&action=createUserForm">Create User</a>
            <a href="?controller=users&action=loginPage">Login</a>

        </header>
    
    <?php require_once('routes.php') ?>
    
    <footer>
    
    </footer>

    </body>
    </html>