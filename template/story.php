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
	<?php 	include 'assets/php/getPart.php'; ?>
	<script>
		START = "<?php echo $start ?>";
		END = "<?php echo $end ?>";
		OPTION = "<?php echo $option_text ?>";
		OPTIONLIST = "<?php echo $optionList ?>";
		//window.alert(START);
	</script>

	<div class="wrapper">
	<?php include 'assets/inc/nav.inc'; ?>
		<div class="storywrapper">
			<div class="storyHeader">
				<div class="layerNumber">page <?php echo $layer; ?></div>
				<div class="storyTitle">
					<a href="storyinfo.php?ID=<?php echo $storyID; ?>&offset=0"><h2> <?php echo $storyTitle; ?></h2> </a>
				</div>
			</div>
			<img class="editButton hide" src="assets/img/edit_icon.png">

			<div class="chooseMessage">
				<a href="?storypart=<?php echo $parentID; ?>">Go back</a>
				<i>
					<p> You chose...<br> <b> <?php echo $option_text; ?> </b> </p>
				</i>
            </div>
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
    </div>
    <?php
        /*** begin the session ***/
        session_start();
 
        /*** create the form token ***/
        $form_token = uniqid();
 
        /*** add the form token to the session ***/
        $_SESSION['form_token'] = $form_token;
?>
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

</BODY>

</HTML>