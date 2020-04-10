<?php

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();

if ( $_SESSION['logged_in'] == 1 ) {
    // Makes it easier to read
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email']; 
    $active = $_SESSION['active'];
}

$description = htmlspecialchars($_POST['description']);
$showPreview = htmlspecialchars($_POST['showPreview']);

if ($showPreview == "on") {
    $showPreview = 1;
} else {
    $showPreview = 0;
}
include '../connect.php'; 

$_SESSION['previewImage'] = $showPreview;

$sql = "UPDATE `users` SET `description`='$description', showPreviewImage = $showPreview WHERE id = $userID LIMIT 1";
if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "Setting succesfully updated!";

} else {
    $_SESSION['message'] = "Something went wrong when uploading the data to the database";
}

    header("location: ../../../user/profile.php?user=" .$userID);
?>