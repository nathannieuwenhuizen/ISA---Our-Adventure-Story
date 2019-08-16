<?php
//MySQL Database Connect 
include '../connect.php'; 
include '../globalfunctions.php'; 

require '../patreon/src/API.php';
require '../patreon/src/Oauth.php';
include '../patreon/patreonCalls.php'; 

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();
/*
Creates the new story part
*/
$storyTitle = htmlspecialchars( removeAllTags( $_POST['storyTitle']), ENT_QUOTES);
$storyDescription = htmlspecialchars(removeScriptTags( $_POST['storyDescription']), ENT_QUOTES);
$id = htmlspecialchars($_POST['id']);

$sql = "UPDATE `storyInfo` SET `Name`='$storyTitle', `Description` = '$storyDescription' WHERE `ID` = $id LIMIT 1";

if (IsCreator($conn, $id)) {
    if (IsPledger(100)) {
        if ($conn->query($sql) === TRUE) {
            header("location: ../../../storyinfo.php?ID=". $id . "&offset=0");
            return;
        } else {
            $_SESSION['message'] = "Failed to update a story";
            echo $conn->error;
        }
    } else {
        $_SESSION['message'] = "You aren't pledged";
    }
} else {
    $_SESSION['message'] = "You aren't the creator of this story";
}
header("location: ../../../user/error.php");

?>