<!DOCTYPE HTML>
<HTML>
<?php require "assets/php/connect.php" ?>
<?php require "assets/php/global.php";?>

<HEAD>
	<TITLE>About</TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css?v=3" type="text/css" />
	<script src="app.js?v=3"></script>
</HEAD>
 
<BODY>
    
	<div class="wrapper">
	<?php 	include 'assets/inc/nav.inc'; ?>
		<div class="aboutwrapper">
		<div class= "left">

		
		<h2>About</h2>
			<p> Hello readers, this is an experiment I want to conduct with you guys.</p>
			<p>This Adventure story will be written by you guys! I don't know if this story will be super huge or just one part only written by me.</p>
			<p>Also I want to see what crazy stuff you guys will come up with the parts you will write.</p>
			<br>
			<p> If you want to give me some feedback, you can try to read a story and write some create parts you can come up with. Also you can help me out by filling up the google form down below! </p>
			<p> I hope you enjoy this project I have so far, and maybe it will become bigger!</p>
			<?php if ($websiteName != 'Adventure Stories') {
				echo '			<p> There is also a <b> <a href="https://discord.gg/dAyDwak"> discord group </a> </b>where you can connect with other readers and writers on this website.</p>				';
			} ?>
			
			<br>

			</div>
			<div class= "right">
			<img src="assets/img/logo.png">
			</div>
			
			<div style="clear: both"> </div>
			<?php 
			if ($websiteName == 'Adventure Stories') { 
				echo '
				<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSel43NxzYX_dqhS_wl_O54nfGlR_4Be8XL5AMmhz3BMZbA5sA/viewform?embedded=true" width="640" height="1447" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>

				';
				
			}else {
				echo '					
				<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSe0HTjp416ZUUWe2DX0LimO5l_JBDJRrFDHQpxsaHuEHnHOgQ/viewform?embedded=true" width="640" height="1677" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>					
					';
			} ?>

			</div>
			
<br>
<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>