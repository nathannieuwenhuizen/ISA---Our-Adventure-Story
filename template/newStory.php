<?php 	
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();

// include 'assets/php/getPart.php';
?>

<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>New story</TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
	<div class="wrapper">
	<?php include 'assets/inc/nav.inc'; ?>

    <form action="assets/php/exclusive/createStory.php" method="post">
    <div  class="updateWrapper">
		<h3> Create a new Story </h3>
		<p>Titel </p> <input type="text" name="storyTitle" placeholder="How will your story be called?" required />
		<p>Description </p>
		<textarea name="storyDescription" cols="40" rows="5" required
			placeholder="What will the story be about? Or how do you think it will be about?"></textarea>
        </div>
        
        <div  class="updateWrapper">
		<h3>Write the first part </h3>
		<p>Introduction </p>
		<textarea name="consequence" cols="40" rows="5" required
			placeholder="Here you can write your consequence..."></textarea>
		<p>Question <input type="text" name="question" value="What do you do?" required/> </p>

		<p>Image url <input type="text" name="image" placeholder="jpg, png, gif" /></p>

		<p><input type="submit" name="submit" class="createButton" value="Create!"/></p>
        </div>
	</form>


    </div>
	<?php 	include 'assets/inc/footer.inc'; ?>
</BODY>

</HTML>