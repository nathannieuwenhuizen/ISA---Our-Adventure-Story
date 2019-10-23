<?php 
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start(); 
?>
<!DOCTYPE HTML>
<HTML>


<HEAD>
    <TITLE>Story information </TITLE>
    <link rel="icon" type="image/png" href="./assets/img/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
    <script src="app.js"></script>
</HEAD>

<BODY>
    <?php 	include 'assets/php/getStoryInfo.php';
     ?>

    <script>
        NAME = "<?php echo $name ?>";
		STORYID = "<?php echo $storyID ?>"
	</script>


    <div class="wrapper">
        <?php 	include 'assets/inc/nav.inc'; ?>

        <div class="storywrapper">
            <div class="storyHeader">
                <div class="storyTitle">
                    <h2> <?php echo $name; ?> | Description and information</h2>
                </div>
            </div>
            <hr>


            <div class="storyInfoWrapper">

            <div class="half">
                <div class="infoPanel">
                    <div class= "<?php if (!$canEdit) { echo "hide"; } ?>">
                <img class="editButton" src="assets/img/edit_icon.png">
                <br>
<br> <br>
</div>
                    <h3>General information </h3>
                    <hr>
                    <p><b> Name </b> <?php echo $name; ?></p>
                    <p><b> Status: </b> <?php echo $status; ?></p>
                    <p><b> Author </b> <?php echo $storyAuthorName; ?></p>
                    <p><b> Created on </b> <?php echo $date; ?></p>
                    <p><b> Description </b> <br> <?php echo $description; ?></p>

                    <p><b> Amount of story parts </b> <?php echo $amountOfParts; ?></p>
                    <p><b> Amount of endings </b> <?php echo $amountOfEnds; ?></p>
                    <p><b> Amount of likes </b> <?php echo $amountOfLikes; ?></p>
                    <p><b> Deepest layer into the story </b> <?php echo $deepestLayer; ?></p>
                    <br>
                    <a class="startReadingButton" href="story.php?storypart=<?php echo $startID; ?>"> Start from beginning
                    </a>
                    <br>
                    <br>
                    <br>
                    <a class="startReadingButton" href="branchtree.php?ID=<?php echo $storyID; ?>"> Tree branch
                    </a>
                </div>

                <div class="infoPanel">
                    <h3>Story structure</h3>
                    <hr>

                    <table>
                        <tr>
                            <th>Layer</th>
                            <th>Amount of parts</th>
                        </tr>

                        <?php echo $layerTableValues; ?>
                    </table>
                </div>
            </div>
            <div class="half">
                <div class="infoPanel added-parts">

                    <h3> All parts </h3>
                    <p> Order by
                    <select id="orderByOptions">
    <option value="0" <?php if ($orderBy == 0) { echo "selected='selected'";} ?>><a href="./">Most recent</a></option>
    <option value="1" <?php if ($orderBy == 1) { echo "selected='selected'";} ?>>Oldest</option>
    <option value="2" <?php if ($orderBy == 2) { echo "selected='selected'";} ?>>Likes</option>
</select>
                    </p>
                    

                    <div class="navigationButtons">

<a class=<?php 
    if ($offset <= 0) {
        echo "hide";
    } else {
        echo "something";
    }
?> href="?ID=<?php echo $storyID; ?>&offset=0&orderby=<?php echo $orderBy; ?>"> << </a> 
<a class=<?php 
if ($offset <= 0) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo $offset - 1; ?>&orderby=<?php echo $orderBy; ?>"> < </a> 
    <b> <?php echo $offset + 1  ?> / <?php echo Round($amountOfParts / 10); ?> </b>
    
    <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo $offset + 1; ?>&orderby=<?php echo $orderBy; ?>"> ></a>
    <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo Round($amountOfParts / 10) - 1; ?>&orderby=<?php echo $orderBy; ?>"> >></a>
</div>
                    <ul class="storyList">
                        <?php echo  $addedPartsList;?>
                    </ul>
                    <?php echo $topAuthorsTable; ?>

                </div>

            </div>

</div>

        </div>
        <form action="assets/php/exclusive/editStory.php" method="post" class="updateWrapper hide">
        <div class="hideButton"> Hide</div>

		<h3> Update Story </h3>
		<p>Titel </p> <input type="text" name="storyTitle" placeholder="How will your story be called?" value="<?php echo $name; ?>"" required />
		<p>Description </p>
		<textarea name="storyDescription" cols="40" rows="5" required
            placeholder="What will the story be about? Or how do you think it will be about?" ><?php echo $description; ?></textarea>
            <p><input type="submit" class="createButton" value="Update!" /></p>
            <input type="hidden" name="id" value = "<?php echo $storyID; ?>">
	</form>


    </div>

    <?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>