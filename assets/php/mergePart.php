<?php
//MySQL Database Connect 
require './connect.php'; 

session_start();

//retreive url var
$stringname = 'mergeFrom';
$mergeFromID = -1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $mergeFromID = $_GET[$stringname];
}
//retreive url var
$stringname = 'mergeTo';
$mergeToID = -1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $mergeToID = $_GET[$stringname];
}

//retreive url var
$stringname = 'story';
$storyID = -1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyID = $_GET[$stringname];
}

$object = '{"result": "0", "message" : "failed to merge path  '.$storyID . $mergeToID . $mergeFromID.' "}';
if ($mergeFromID != -1 && $storyID != -1) {
    $userID = -1;
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
    }

    // no duplicate merges
    $sql = "SELECT fromPart FROM `merges` WHERE fromPart = '$mergeFromID' AND toPart = '$mergeToID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) <= 0) {
        $sql = "INSERT INTO `merges` (`storyID`, `fromPart`, `toPart`, `date`, `userID`) VALUES ('$storyID', '$mergeFromID', $mergeToID, NOW(), '$userID');";

        if ($conn->query($sql) === TRUE) {
            // $last_id = $conn->insert_id;
            $object = '{"result": "1", "message" : "merge succesful!"}';
    
        } else {
            $object = '{"result": "0", "message" : "no internet connection"}';
    
        }
    } else {
        $object = '{"result": "0", "message" : "there is already a merge to this part"}';
    }

} 

 // output data 
echo $object;
$conn->close();
?>