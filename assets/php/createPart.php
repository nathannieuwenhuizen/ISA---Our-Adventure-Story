<?php
include './connect.php'; 

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
" VALUES (NULL, '0', $end, $option, $consequence, $question, '', CURRENT_TIMESTAMP, $layer, $image, $parentID);";


echo $sql;
// if ($conn->query($sql) === TRUE) {
//    $last_id = $conn->insert_id;
//    echo "New record created successfully. Last inserted ID is: " . $last_id;
// } else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }

header("location: ../../?storyID=2");
echo "<a href='../../?storypart=2'>go back to page</a>";
?>