<?php
session_start();
require 'db.php';

?>
<!DOCTYPE html>
<html>
<head>
  <title>Error</title>
  <?php 	include '../assets/inc/head.inc'; ?>
</head>
<body>
<div class="wrapper">

<?php 	include '../assets/inc/nav_login.inc'; ?>

<div class="form">
    <h1>Error</h1>
    <p>
    <?php 
    if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ):
        echo $_SESSION['message'];    
    else:
        header( "location: index.php" );
    endif;
    ?>
    </p>     
    <a href="index.php"><button class="button button-block"/>Home</button></a>
</div>
<?php 	include '../assets/inc/footer.inc'; ?>

</body>
</html>
