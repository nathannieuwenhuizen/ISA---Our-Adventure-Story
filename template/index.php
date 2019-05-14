<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>Our adventure story</TITLE>
	<link rel="icon" type="image/png" href="./assets/page_elements/profile.jpg" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
	<?php 	
include 'assets/php/connect.php'; 

include 'assets/php/getpart.php'; 

// include 'assets/inc/footer.inc'; 
?>
	<form action="assets/php/createPart.php" method="post">
		<h3> Create a new Part </h3>
		<p>Option </p> <input type="text" name="option" placeholder="what the reader can choose" required />
		<p>Consequence </p>
		<textarea name="consequence" cols="40" rows="5" required
			placeholder="Here you can write your consequence..."></textarea>
		<p>End of story? <input type="checkbox" name="end" /> </p>
		<h4> If it isn't the end of the story, what would you ask the reader to do next? </h4>
		<p>Question <input type="text" name="question" /> </p>

		<h4> Optional </h4>
		<p>Image url <input type="text" name="image"
				placeholder="URL of the image you can show with the consequence..." /></p>

		<!-- hidden data to create a new part -->
		<input type="hidden" name="layer" value="<?php echo $layer; ?>">
		<input type="hidden" name="parentID" value="<?php echo $storyID; ?>">
		<input type="hidden" name="parentOptions" value="<?php echo $optionIDs; ?>">
		<p><input type="submit" /></p>
	</form>

	<div class="wrapper">
		<h1> Our adventure story! </h1>
		<div class="storywrapper">
			<div class="storyHeader">
				<div class="storyTitle">
					<h2> <?php echo "story title, needs to be from sql"; ?></h2>
				</div>
				<div class="navigation"> </div>
			</div>
			<div class="chooseMessage">
				<p> You chose the option: <b> <?php echo $option_text; ?> </b> </p>
			</div>
			<div class="duoWrapper">
				<div class="leftside">
				<img src="<?php echo $image; ?>" />
				</div>
				<div class="rightside">
				<div class="contentPanel">
						<?php echo $content_text; ?>
					</div>
				</div>
			</div>
			<div class="questionPanel">
				<p> <?php echo $question_text; ?> </p>
			</div>
			<div class="optionsList">
				<ul>
				<?php echo $optionList; ?>
</ul>
				
			</div>

		</div>
	</div>

</BODY>

</HTML>