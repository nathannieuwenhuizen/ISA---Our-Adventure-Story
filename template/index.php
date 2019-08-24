<?php 
require 'assets/php/patreon/src/API.php';
require 'assets/php/patreon/src/OAuth.php';
		
require 'assets/php/patreon/patreonCalls.php';

//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start(); 
 
// CheckGetVariable();

?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
	<TITLE>Story list</TITLE>
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
					
		<div class="storyHeader">
				<div class="storyTitle">
					<h2>Your story</h2>
				</div>
			</div>
			<hr>
			<!-- <p>Create your own stories here!</p> -->
			<?php 
			// echo "is pledger: " .IsPledger(100);
			
			                    //TODO: REMOVE THESE LINES!
								echo 'coming soon...';
			// 					return;
			
			// if (IsPLedger(100)) {
			// 	if ($myStories != "") {
			// 		echo "<ul class='storyList'> " . $myStories. "</ul>";
			// 	} else {
			// 		echo '<a class="newStoryButton" href="newstory.php"> Start a new Story</a>';
			// 	}
			// } else {
			// 	CreateUnlockButton();
			// }	
			 ?>

						
						<hr>
		</div>
	</div>
	
	<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>