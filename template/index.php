<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>Our adventure story! </TITLE>
	<link rel="icon" type="image/png" href="./assets/img/book.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
	<?php 	
include 'assets/php/getPart.php'; 


?>
	<script>
		START = "<?php echo $start ?>";
		END = "<?php echo $end ?>";
		OPTION = "<?php echo $option_text ?>";
		OPTIONLIST = "<?php echo $optionList ?>";
		// window.alert(v);
	</script>

	<div class="wrapper">
		<div class="navigation">
			<ul>
				<li class="about">About</li>
				<li class ="story">Story</li>
			</ul>
			<a href="?storypart=1">
				<h1> Our adventure story</h1>
			</a>
			<h4>A choose your own adventure story created by the internet</h4>
		</div>
			
		<div class="aboutwrapper hide">
		<h2>About</h2>
			<p> Hello readers, this is an experiment I want to conduct with you guys.</p>
			<p>This choose your own adventure story will be written by you guys! I don't know if this story will be super huge or just one part only written by me.</p>
			<p>Also I want to see what crazy stuff you guys will come up with the parts you will write.</p>
			<br>
			<p> If you want to give me some feedback, you can try to read a story and write some create parts you can come up with. Also you can help me out by filling up the google form down below! </p>
			<p> I hope you enjoy this project I have so far, and maybe it will become bigger!</p>
			<br>
			<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfqG4y_UTqwWL_kP_D6iiOV-3ZHK3p8VT3jHO21FSAGt1Yb7Q/viewform?embedded=true" width="640" height="2008" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
		</div>
		<div class="storywrapper">
			<div class="storyHeader">
				<div class="layerNumber">page <?php echo $layer; ?></div>
				<div class="storyTitle">
					<h2> <?php echo $storyTitle; ?></h2>
				</div>
			</div>
			<img class="editButton hide" src="assets/img/edit_icon.png">

			<div class="chooseMessage">
				<a href="?storypart=<?php echo $parentID; ?>">Go back</a>
				<i>
					<p> You chose...<br> <b> <?php echo $option_text; ?> </b> </p>
				</i>
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
		<input type="hidden" name="parentID" value="<?php echo $storyID; ?>">
		<input type="hidden" name="parentOptions" value="<?php echo $optionIDs; ?>">
		<input type="hidden" name="parentEnd" value="<?php echo $end; ?>">
		<p><input type="submit" class="createButton" value="Create!" /></p>
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
		<input type="hidden" name="id" value="<?php echo $storyID; ?>">
		<input type="hidden" name="optionIDs" value="<?php echo $optionList; ?>">

		<p><input type="submit" class="createButton" value="Update!" /></p>
	</form> 

</BODY>

</HTML>