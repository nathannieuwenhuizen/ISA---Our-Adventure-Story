<!DOCTYPE HTML>
<HTML>
<?php session_start(); ?>
<HEAD>
	<TITLE></TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
<?php include 'assets/php/getStories.php'; ?>
	<div class="wrapper">
	<?php include 'assets/inc/nav.inc'; ?>

		<div class="storywrapper">
			<div class="storyHeader">
				<div class="storyTitle">
					<h2>List of stories</h2>
				</div>
			</div>
			<hr>

			<ul class="storyList"> 
						<?php echo  $storyList;?>
						</ul>
						<hr>
					<i> more will follow soon ;)</i>
		</div>
	</div>
	<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>