<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 
include 'assets/php/globalfunctions.php'; 

//retreive database storypart
$storyList = getStoryList($conn, false);


$myStories = getStoryList($conn, true);

$conn->close();
?>