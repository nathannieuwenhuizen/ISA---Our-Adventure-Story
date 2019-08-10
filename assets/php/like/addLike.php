<?php
//MySQL Database Connect 
require '../connect.php'; 

session_start();

//retreive url var
$stringname = 'id';
$storyPartID = -1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyPartID = $_GET[$stringname];
}

//retreive url var
$stringname = 'story';
$storyID = -1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyID = $_GET[$stringname];
}
 

$object = '{"result": "0", "message" : "failed to add like"}';
if ($storyPartID != -1 && $storyID != -1) {
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        // echo $userID;
        $sql = "INSERT INTO `likes` (`storypartID`, `userID`, `storyID`, `date`) VALUES ('$storyPartID', '$userID', $storyID, NOW());";
    
        if ($conn->query($sql) === TRUE) {
            // $last_id = $conn->insert_id;
            $object = '{"result": "1", "message" : "remove like!"}';
    
        } else {
            $object = '{"result": "0", "message" : "no internet connection"}';

        }
    }else {
        $object = '{"result": "0", "message" : "you are somehow not logged in"}';

    }
} 

 // output data 
echo $object;
$conn->close();
?>