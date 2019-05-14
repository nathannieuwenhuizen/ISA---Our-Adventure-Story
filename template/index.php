<!DOCTYPE HTML>
<HTML>

<HEAD>
	<TITLE>Our adventure story</TITLE>
	<link rel="icon" type="image/png" href="./assets/page_elements/profile.jpg"/>
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
<form  action="assets/php/createPart.php" method="post">
 <p>Option </p> <input type="text" name="option" />
 <p>Consequence </p><input type="textarea" name="consequence" />
 <p>Question </p> <input type="text" name="question"/>
 <p>Image url </p><input type="text" name="image"/>
 <p>End of story? </p> <input type="checkbox" name="end"/>
 <input type="hidden" name="layer" value="<?php echo $layer; ?>"> 
 <input type="hidden" name="parentID" value="<?php echo $storyID; ?>">
 <input type="text" name="parentOptions" value="<?php echo $optionIDs; ?>"> 
 <p><input type="submit" /></p>
</form>

<div class="wrapper">
<h1> Our adventure story! </h1>
<div class="storywrapper"> 
<div class="storyHeader"> 
	<div class="storyTitle"> <h2>  <?php echo "story title, needs to be from sql"; ?></h2> </div>
	<div class="navigation"> </div>
</div>
<div class="chooseMessage"> 
<p> You chose the option: <b> <?php echo $option_text; ?> </b> </p>
</div>
<div class="duoWrapper">
<div class="leftside"> 
<div class="contentPanel">
<?php echo $content_text; ?>
</div>
</div>
<div class="rightside"> 
	<img src="<?php echo $image; ?>"/>
</div>
</div>
<div class="questionPanel">
<p> <?php echo $question_text; ?> </p>
</div>
<div class="optionsList">
<?php echo $optionList; ?>
</div>

</div>
</div>

</BODY>

</HTML>