<?php
/* Log out process, unsets and destroys session variables */
session_start();
session_unset();
session_destroy(); 
require 'db.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Error</title>
  <?php 	include '../assets/inc/head.inc'; ?>
</head>

<body>
<div class="wrapper">

<?php 	include '../assets/inc/nav_login.inc'; ?>

    <div class="form">
          <h1>Thanks for stopping by</h1>
              
          <p><?= 'You have been logged out!'; ?></p>
          
          <a href="index.php"><button class="button button-block"/>Home</button></a>

    </div>

    <?php 	include '../assets/inc/footer.inc'; ?>

</body>
</html>
