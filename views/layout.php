<html>
<head>
  <title>Lej en ladcykel</title>
  <style>
    <?php require_once('stylesheet.css') ?>
  </style>
</head>
<body>
<header>
  <div class="navbar">
    <a href="/../../Hobby-projekt/mvc-project/">Hjem</a>
    <a href="?controller=users&action=loginPage">Login</a>
    <a href="?controller=users&action=createUserForm">Opret Bruger</a>
    <a href="?controller=bikes&action=registerBikeForm">Registrer cykel</a>
    <a href="?controller=bikes&action=index">Cykler til leje</a>
  </div>
</header>

<?php require_once(dirname(__DIR__).'/routes.php') ?>

<footer>

</footer>

</body>
</html>