<?php 	
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
include 'assets/php/getPart.php'; ?>

<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>Our adventure story</TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
	<script>
		START = "<?php echo $start ?>";
		END = "<?php echo $end ?>";
		OPTION = "<?php echo $option_text ?>";
		OPTIONLIST = "<?php echo $optionList ?>";
		TITLE = "<?php echo $storyTitle ?>";
		PARTID = "<?php echo $storyPartID ?>"
		STORYID = "<?php echo $storyID ?>"
		LOGGEDIN = "<?php 
		if (isset($_SESSION['logged_in'])) { 
			echo $_SESSION['logged_in']; 
			} 
		else { 
			echo 0; 
		} ?>";
		// window.alert(LOGGEDIN);
	</script>

	<div class="wrapper">
	<?php include 'assets/inc/nav.inc'; ?>
		<div class="storywrapper">
			<div class="storyHeader">
				<div class="layerNumber">page <?php echo $layer; ?> <div class="author"><i><?php if ( $authorName != "anonymous") { echo "written by ". $authorName; } ?></i></div></div>
				<div class="storyTitle">
					<a href="storyinfo.php?ID=<?php echo $storyID; ?>&offset=0"><h2> <?php echo $storyTitle; ?></h2> </a>
					<a href="branchtree.php?ID=<?php echo $storyID; ?>">Branch tree</a>
				</div>
			</div>
			<img class="editButton hide" src="assets/img/edit_icon.png">
			<div id="starElement"> <p> <?php echo $amountOfLikes; ?> </p>
			<img class="starIcon" src=<?php 
			if ($like) { 
				echo "assets/img/star_full.png";
			} else { 
				echo "assets/img/star_empty.png";
			} ?>>
			<div id="starMessage"><?php echo $likeMessage;  ?></div>
</div>
			<div class="chooseMessage">

			<a style="display:<?php if ($startID == $parentID) {echo 'none'; } else { echo 'inline-block'; } ?>;" href="?storypart=<?php echo $startID; ?>">Go to beginning</a>
			<a href="?storypart=<?php echo $parentID; ?>">Go back</a>
				<i>
					<p> You chose...<br> <b> <?php echo $option_text; ?> </b>  </p>
				</i>
				
			</div>
			<br>
            <hr>

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
            <hr>
			 <i>
			<div class="questionPanel">
				<p> <?php echo $question_text; ?> </p>
			</div>
			</i>
			<div class="optionsList">
				<ul>
					<?php echo $optionList; ?>
				</ul>
				<div class="createnewPartButton">Create your own Path! </div>

			</div>

		</div>
		<!-- Create a new part field -->
	<form action="assets/php/createPart.php" method="post" class="createWrapper">
		<h3> Create a new Part </h3>
		<p>Option text </p> <input type="text" name="option" placeholder="what the reader can choose" required />
		<p>Consequence </p>
		<textarea name="consequence" cols="40" rows="5" required
			placeholder="Here you can write your consequence..."></textarea>
		<p>End of story <input type="checkbox" name="end" /> </p>
		<p>Question <input type="text" name="question" value="What do you do?" /> </p>

		<p>Image url <input type="text" name="image" placeholder="jpg, png, gif" /></p>

		<!-- hidden data to create a new part -->
		<input type="hidden" name="layer" value="<?php echo $layer; ?>">
		<input type="hidden" name="parentID" value="<?php echo $storyPartID; ?>">
		<input type="hidden" name="storyID" value="<?php echo $storyID; ?>">
		<input type="hidden" name="parentOptions" value="<?php echo $optionIDs; ?>">
        <input type="hidden" name="parentEnd" value="<?php echo $end; ?>">
        <input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
		<p><input type="submit" name="submit" class="createButton" value="Create!"/></p>
	</form>

<!-- Update part field -->
	<form action="assets/php/updatePart.php" method="post" class="updateWrapper hide">
		<div class="hideButton"> Hide</div>
		<h3> Update the current Part </h3>
		<p>Option text </p> <input type="text" name="option" placeholder="what the reader can choose" value = "<?php echo $option_text ?>" required />
		<p>Consequence </p>
		<textarea name="consequence" cols="40" rows="5" required
			placeholder="Here you can write your consequence..."><?php echo $content_textNL ?></textarea>
		<p>End of story <input class="update_end" type="checkbox" name="end" /> </p>
		<p>Question <input type="text" name="question" value= "<?php echo $question_text; ?>"" /> </p>

		<p>Image url <input type="text" name="image" placeholder="jpg, png, gif" value = "<?php echo $image ?>" /></p>

		<!-- hidden data to update the current part -->
		<input type="hidden" name="id" value="<?php echo $storyPartID; ?>">
		<input type="hidden" name="optionIDs" value="<?php echo $optionList; ?>">

		<p><input type="submit" class="createButton" value="Update!" /></p>
	</form> 
    </div>
    
	
	<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>