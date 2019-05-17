<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>Our adventure story</TITLE>
	<link rel="icon" type="image/png" href="./assets/img/book.png" />
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
<script> 
 START = "<?php echo $start ?>"; 
 END = "<?php echo $end ?>"; 
// window.alert(v);
</script>

	<div class="wrapper">
		<a href="?storypart=1">
		<h1> Our adventure story! </h1>
		</a>

		<h4>A choose your own adventure story created by the internet</h4>
		<div class="storywrapper">
			<div class="storyHeader">
				<div class="layerNumber">page <?php echo $layer; ?></div>
				<div class="storyTitle">
					<h2> <?php echo $storyTitle; ?></h2>
				</div>
				<div class="navigation"> </div>
			</div>
			<div class="chooseMessage">
				<a href="?storypart=<?php echo $parentID; ?>">Go back</a>
				<i><p> You chose...<br> <b> <?php echo $option_text; ?> </b> </p></i>
			</div>
			<div class="duoWrapper">
				<div class="leftside">
					<img class="consequenceImage" src="<?php echo $image; ?>" />
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
				<div class="createnewPartButton">Create your own Path! </div>

			</div>

		</div>
	</div>
	<form action="assets/php/createPart.php" method="post" class="createWrapper">
		<h3> Create a new Part </h3>
		<p>Option text </p> <input type="text" name="option" placeholder="what the reader can choose" required />
		<p>Consequence </p>
		<textarea name="consequence" cols="40" rows="5" required
			placeholder="Here you can write your consequence..."></textarea>
		<p>End of story <input type="checkbox" name="end" /> </p>
		<p>Question <input type="text" name="question" value="What do you do?" /> </p>

		<p>Image url <input type="text" name="image"
				placeholder="jpg, png, gif" /></p>

		<!-- hidden data to create a new part -->
		<input type="hidden" name="layer" value="<?php echo $layer; ?>">
		<input type="hidden" name="parentID" value="<?php echo $storyID; ?>">
		<input type="hidden" name="parentOptions" value="<?php echo $optionIDs; ?>">
		<p><input type="submit" class="createButton" value="Create!"/></p>
	</form>

</BODY>

</HTML>