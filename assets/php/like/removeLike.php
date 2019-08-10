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
 

$object = '{"result": "0", message" : "failed to remove like"}';
if ($storyPartID != -1) {
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        // echo $userID;
        $sql = "DELETE FROM `likes` WHERE `storypartID` = $storyPartID AND userID = $userID";

        if ($conn->query($sql) === TRUE) {
            // $last_id = $conn->insert_id;
            $object = '{"result" : "1", "message" : "add like!"}';
    
        }
    }
} 

echo $object;
$conn->close();
?>