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
    <?php 	include 'assets/php/getStoryInfo.php'; ?>
    <script>
        NAME = "<?php echo $name ?>";
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
                    <h3>General information </h3>
                    <hr>
                    <p><b> Name </b> <?php echo $name; ?></p>
                    <p><b> Created on </b> <?php echo $date; ?></p>
                    <p><b> Author </b> Everyone</p>
                    <p><b> Description </b> <br> <?php echo $description; ?></p>
                    <p><b> Amount of story parts </b> <?php echo $amountOfParts; ?></p>
                    <p><b> Amount of endings </b> <?php echo $amountOfEnds; ?></p>
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

                    <h3> Recently added parts </h3>
                    <hr>

                    <div class="navigationButtons">

<a class=<?php 
    if ($offset <= 0) {
        echo "hide";
    } else {
        echo "something";
    }
?> href="?ID=<?php echo $storyID; ?>&offset=0"> << </a> 
<a class=<?php 
if ($offset <= 0) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo $offset - 1; ?>"> < </a> 
    <b> <?php echo $offset + 1  ?> / <?php echo Round($amountOfParts / 10); ?> </b>
    
    <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo $offset + 1; ?>"> ></a>
    <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?ID=<?php echo $storyID; ?>&offset=<?php echo Round($amountOfParts / 10) - 1; ?>"> >></a>
</div>
                    <ul class="storyList">
                        <?php echo  $addedPartsList;?>
                    </ul>
                </div>
            </div>
</div>

        </div>
    </div>

    <?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>