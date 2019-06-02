<?php
//MySQL Database Connect 
include 'connect.php'; 

/*
Creates the new story part
*/
$option = htmlspecialchars($_POST['option'], ENT_QUOTES);
$consequence = htmlspecialchars($_POST['consequence'], ENT_QUOTES);
$question = htmlspecialchars($_POST['question'],ENT_QUOTES);
$image = htmlspecialchars($_POST['image']);
$optionIDs = htmlspecialchars($_POST['optionIDs']);
$end = "0";
$id = htmlspecialchars($_POST['id']);

if (isset($_POST['end'])) {
    $end = "1";
}

if ($optionIDs == "") {

$sql = "UPDATE `storyparts` SET `end`='$end', `option_text` = '$option', `content_text` = '$consequence', `question_text` = '$question', `image` = '$image' WHERE id = $id LIMIT 1";


if ($conn->query($sql) === TRUE) {
   //echo "<br>New part created successfully.<br>";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
}
//backup
//echo "<br><a href='../../?storypart=". $id."'>go back to page</a> <br>";
//echo $sql;

}

header("location: ../../?storypart=". $id);

?>