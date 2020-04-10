<?php 
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start(); 

require 'assets/php/global.php';

?>

<!DOCTYPE HTML>
<HTML>
<?php require "assets/php/connect.php" ?>

<HEAD>
	<TITLE>Circular branch tree</TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css?v=<?php  echo $appVersion; ?>" type="text/css" />
	<script src="app.js?v=<?php  echo $appVersion; ?>"></script>
</HEAD>

<BODY>

<?php include 'assets/php/getStoryInfo.php'; ?>

	<div class="wrapper">
	<?php 	include 'assets/inc/nav.inc'; ?>
		<div class="blogWrapper">

		<h2><a href="storyinfo.php?ID=<?php echo $storyID; ?>&offset=0"><?php echo $name; ?>  </a>| Circular branch tree</h2>
		<hr>
		<p> Click on a point to view details or to go to that part. </P>
		<div id = "canvasHolder">
		<div id="loading"> <p> loading... </p> </div>
		<canvas id="branchCanvas" width="4000" height= "4000">  no canvas supported</canvas>
		<canvas id="HbranchCanvas" width="4000" height= "4000">  no canvas supported</canvas>
		<a id="startReadingButton" href="story.php?storypart=<?php echo $startID; ?>"> Go to part! </a> 

</div>

</div>			
<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>