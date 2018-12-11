<?php

$nav = "";
if (isset($_SESSION['username'])){
    $nav = "logged_in.php";
} else {
  $nav  = "default_nav.php";
}
?>

<html>
<head>
  <title>Lej en ladcykel</title>
  <style>
    <?php require_once('stylesheet.css') ?>
  </style>
</head>
<body>
  <h1 id="title"> LEJ EN LADCYKEL</h1>

<?php

// navigation bar
require(dirname(__DIR__).'/views/pages/'.$nav);

// direct to the correct page
require_once(dirname(__DIR__).'/routes.php')

?>

<footer>

</footer>

</body>
</html>