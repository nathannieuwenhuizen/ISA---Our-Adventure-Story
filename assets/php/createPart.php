<?php
include './connect.php'; 

/*
Creates the new story part
*/
$option = htmlspecialchars($_POST['option']);
$consequence = htmlspecialchars($_POST['consequence']);
$question = htmlspecialchars($_POST['question']);
$image = htmlspecialchars($_POST['image']);
$layer = $_POST['layer'] + 1;
$parentID = htmlspecialchars($_POST['parentID']);
$end = "0";
if (isset($_POST['end'])) {
    $end = "1";
}
echo $parentID;
$sql = "INSERT INTO `storyparts` (`ID`, `start`, `end`, `option_text`, `content_text`, `question_text`, `option_IDs`, `Date`, `layer`, `image`, `parentID`)" .
" VALUES (NULL, '0', '$end', '$option', '$consequence', '$question', '', CURRENT_TIMESTAMP, $layer, '$image', $parentID);";



$last_id = 7;
if ($conn->query($sql) === TRUE) {
   $last_id = $conn->insert_id;
   echo "New record created successfully. Last inserted ID is: " . $last_id . "<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
}

echo "<br><br><br>";
/*
Updates the option list of the parent ID
*/
$parentOptions = htmlspecialchars($_POST['parentOptions']);
if ( $parentOptions != "") {
    $parentOptions .= ", ";
}
$parentOptions .= $last_id;
//echo $parentOptions; 

//echo $sql;
$sql = "UPDATE `storyparts` SET `option_IDs` = '". $parentOptions . "' WHERE `storyparts`.`ID` = ". $parentID . ";";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully. Last inserted ID is: " . $last_id . "<br>";
 } else {
     echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
 }

/*
Redirect ot the new page
*/
//header("location: ../../?storypart=2");
//backup
echo "<br><a href='../../?storypart=". $last_id."'>go back to page</a>";
?>