<?php
//MySQL Database Connect 

include 'connect.php'; 
include 'globalfunctions.php'; 


require './patreon/src/API.php';
require './patreon/src/Oauth.php';
include './patreon/patreonCalls.php'; 
session_start();

/*
Creates the new story part
*/
$option = htmlspecialchars( removeAllTags( $_POST['option']), ENT_QUOTES);
$consequence = htmlspecialchars(removeScriptTags( $_POST['consequence']), ENT_QUOTES);
$question = htmlspecialchars(removeScriptTags($_POST['question']),ENT_QUOTES);
$image = htmlspecialchars($_POST['image']);
$layer = $_POST['layer'] + 1;
$parentID = htmlspecialchars($_POST['parentID']);
$storyID = htmlspecialchars($_POST['storyID']);
$parentEnd = htmlspecialchars($_POST['parentEnd']);
$end = "0";

if (isset($_POST['end'])) {
    $end = "1";
}

if ($parentEnd == "1" || strictEmpty($option) || strictEmpty($consequence)) {
    //header("location: ../../?storypart=". $parentID);
    return;
}
if (!StoryIsOpen($conn, $storyID)) {
    $_SESSION['message'] = "can't create a new part, story is closed";
    header("location: ../../user/error.php");
    // echo "appearantly the story is closed...";

    return;
} else {
    // echo "appearantly the story is open...";
}

//this prevents duplicate entries
$sql = "SELECT * FROM storyparts WHERE (parentID = $parentID AND content_text = '$consequence' AND option_text = '$option')  LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // echo "<br>A part already exist.<br>";
    header("location: ../../story.php?storypart=". $parentID);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error . "<br> pee pee!";
    
    $author = -1;
    if (isset($_SESSION['logged_in'])) {
        $author = $_SESSION['userID'];
    }
    $sql = "INSERT INTO `storyparts` (`ID`, `start`, `end`, `option_text`, `content_text`, `question_text`, `option_IDs`, `Date`, `layer`, `image`, `parentID`, `storyID`, `authorID`)" .
" VALUES (NULL, '0', '$end', '$option', '$consequence', '$question', '', NOW(), $layer, '$image', $parentID, $storyID, $author);";

    $last_id = -1;


    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        //echo "<br>New part created successfully.<br>";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }



    //echo "<br><br><br>";
    /*
    Updates the option list of the parent ID
    */
    $parentOptions = htmlspecialchars($_POST['parentOptions']);
    if ($last_id != -1) {
        if ( $parentOptions != "") {
            $parentOptions .= ", ";
        }
        $parentOptions .= $last_id;
    }

    // echo $sql;
    $sql = "UPDATE `storyparts` SET `option_IDs` = '". $parentOptions . "' WHERE `storyparts`.`ID` = ". $parentID . ";";
    if ($conn->query($sql) === TRUE) {
        //echo "<br>Optionlist is updated inserted ID is: " . $last_id . "<br>";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }


    //backup
    //echo "<br><a href='../../?storypart=". $last_id."'>go back to page</a>";
    /*
    Redirect ot the new page
    */
    header("location: ../../story.php?storypart=". $last_id);
    die();
 }

?>