<?php
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);

session_start();
require 'db.php';

?>
<!DOCTYPE html>
<html>
<head>
  <title>Error</title>
  <link rel="icon" type="image/png" href="../assets/img/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="../assets/style.css?v=2" type="text/css" />
	<script src="../app.js?v=2"></script>
</head>
<body>
<div class="wrapper">

<?php 	include '../assets/inc/nav_login.inc'; ?>

<div class="form">
    <h1>Error</h1>
    <p>
    <?php 
    
    if( isset($_SESSION['message']) ):
        echo $_SESSION['message'];    
    else:
        // header( "location: index.php" );
    endif;
    ?>
    </p>     
    <a href="index.php"><button class="button button-block"/>Home</button></a>
</div>
<?php 	include '../assets/inc/footer.inc'; ?>

</body>
</html>
