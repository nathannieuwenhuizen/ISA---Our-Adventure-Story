<?php

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();

//MySQL Database Connect 
include 'connect.php'; 
include 'globalfunctions.php'; 

require './patreon/src/API.php';
require './patreon/src/OAuth.php';
include './patreon/patreonCalls.php'; 


/*
Creates the new story part
*/
$option = htmlspecialchars(removeScriptTags($_POST['option']), ENT_QUOTES);
$consequence = htmlspecialchars(removeScriptTags($_POST['consequence']), ENT_QUOTES);
$question = htmlspecialchars(removeScriptTags($_POST['question']),ENT_QUOTES);
$image = htmlspecialchars($_POST['image']);
$optionIDs = htmlspecialchars($_POST['optionIDs']);
$end = "0";
$id = htmlspecialchars($_POST['id']);
$storyID = htmlspecialchars($_POST['storyID']);

if (isset($_POST['end'])) {
    $end = "1";
}


if (!StoryIsOpen($conn, $storyID)) {
    $_SESSION['message'] = "can't update part, story is closed";
    header("location: ../../user/error.php");
    return;
}

//if options already exist
if ($optionIDs != "") {
    $end = "0";
    //then only the pledged creator can edit it
    if (IsCreator($conn, $storyID)) {
        if (IsPledger(100)) {
            UpdatePart($conn, $id, $option, $consequence, $question, $image, $end);
        } else {
            // echo "im not the pledger";
        }
    } else {
        // echo "im not the creator";
    }
} else {
    //normal part
    UpdatePart($conn, $id, $option, $consequence, $question, $image, $end);
}

function UpdatePart($conn, $id, $option, $consequence, $question, $image, $end) {
    $sql = "UPDATE `storyparts` SET `end`='$end', `option_text` = '$option', `content_text` = '$consequence', `question_text` = '$question', `image` = '$image' WHERE id = $id LIMIT 1";
    if ($conn->query($sql) === TRUE) {
    //    echo "<br>New part created successfully.<br>";
    } else {
        // echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }

}
//backup
//echo "<br><a href='../../?storypart=". $id."'>go back to page</a> <br>";
// echo $sql;



header("location: ../../story.php?storypart=". $id);

?>