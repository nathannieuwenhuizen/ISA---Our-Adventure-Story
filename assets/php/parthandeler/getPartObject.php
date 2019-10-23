<?php
//MySQL Database Connect 
include '../connect.php'; 
include '../globalfunctions.php'; 
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();

//retreive url var
$stringname = 'storypart';
$storyPartID = 1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyPartID = $_GET[$stringname];
}
if ($storyPartID < 1) {
    $storyPartID = 1;
}

//retreive database storypart
// $sql = "SELECT * FROM storyparts WHERE ID = $storyPartID LIMIT 1";
$sql = "SELECT * FROM storyparts LEFT JOIN users ON storyparts.authorID = users.id WHERE storyparts.ID = $storyPartID LIMIT 1";
$result = mysqli_query($conn, $sql);


$start;
$end;
$option_text = "";
$content_text;
$question_text;
$optionIDs;
$layer;
$image;
$parentID;
$storyID; 
$optionList = "";//defined in the next sql call
$authorID;
$authorName = 'anonymous';

//if there is any result
if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["ID"]. "<br>";
        $start = $row["start"];
        $end = $row["end"];
        $option_text .= removeAllTags($row["option_text"]);
        $content_text = html_entity_decode(( removeScriptTags($row["content_text"])));
        $question_text = ($row["question_text"]);
        $optionIDs = $row["option_IDs"];
        $layer = $row["layer"];
        $image = $row["image"];
        $parentID = $row["parentID"];
        $storyID = $row["storyID"];
        $authorID = $row["authorID"];
        if (isset($row["username"])) {
            $authorName = $row["username"];
        }

    }
} else {
    //there are no results
    // echo "0 results";
}

/*
Loads in the option ids and text associated with this story part.
 */
//create an array from the options.
$optionArray = explode(",", $optionIDs);

//additional merges if there are any
$sql = "SELECT toPart FROM `merges` WHERE fromPart = $storyPartID";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $new = array_push($optionArray, $row["toPart"]);
        //echo $row["toPart"];
    }
}


$sql = "SELECT option_text, ID FROM storyparts WHERE ID IN ( ";
for ($i = 0; $i < sizeof($optionArray); $i++) {
    $sql .= "'". (int)$optionArray[$i] . "'";
    if ($i != sizeof($optionArray) - 1) {
        $sql .= ", ";
    }
} 
$sql .= ") ORDER BY option_text ASC";
//echo $sql . "<br>";



$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        // echo "id: " . $row["ID"]. "<br>";
        $optionList .= "<li> <a id='".$row["ID"]."' href='?storypart=" . $row["ID"] . "'> ". $row["option_text"] ." </a> </li> ";
    }
} else {
    //there are no results
    //echo "0 results";
}
$canEdit = false;
if ($optionIDs == "" && $end == 0 && $start == 0) {
    $canEdit = true;
}



//check if user has liked the part
$likeMessage = "login to like";
$like = false;
if (isset($_SESSION['userID'])) {
    $userID =  $_SESSION['userID'];
    if (isset($_SESSION['logged_in'])) {
        $likeMessage = "add like";

        $sql = "SELECT * FROM likes WHERE storyPartID = $storyPartID  AND userID = $userID LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        //if there is any result
        if (mysqli_num_rows($result) > 0) {
                $likeMessage = "remove like";
                $like = true;
        }
    }
}

$amountOfLikes = GetAmountOfLikes($conn, $storyPartID);

function GetAmountOfLikes($conn, $storyPartID) {
    $amountOfLikes = "0 likes";
    $sql = "SELECT COUNT(storyPartID) FROM likes WHERE storyPartID = $storyPartID";
    $result = mysqli_query($conn, $sql);     
    //if there is any result
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            if ($row["COUNT(storyPartID)"] != 1) {
                $amountOfLikes = $row["COUNT(storyPartID)"] . " likes"; 
            } else {
                $amountOfLikes = "1 like";  
            }
        }
    }
    return $amountOfLikes;
}

$content_text = str_replace("\r", "", $content_text);
$content_text = str_replace("\n", " ABC ", $content_text);
$content_text = str_replace("<br>", " ABC ", $content_text);

$option_text = convert_smart_quotes($option_text); 
$content_text = convert_smart_quotes($content_text); 
$question_text = convert_smart_quotes($question_text); 

function convert_smart_quotes($string) 

{ 
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 

    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 

    return str_replace($search, $replace, $string); 
} 


$object = "";

$object .= '{
    "ID": "'. $storyPartID.'",
    "start": "'. $start.'",
    "end": "'. $end.'",
    "option_text": "'. htmlspecialchars($option_text).'",
    "content_text": "'. htmlspecialchars($content_text).'",
    "question_text": "'. htmlspecialchars($question_text).'",
    "optionIDs": "'. $optionIDs.'",
    "layer": "'. $layer.'",
    "image": "'. $image.'",
    "parentID": "'. $parentID.'",
    "authorName": "'. $authorName.'",
    "optionList": "'. $optionList.'",
    "canEdit": "'. $canEdit.'",
    "likeMessage": "'. $likeMessage.'",
    "like": "'. $like.'",
    "amountOfLikes": "'. $amountOfLikes.'"
}';
echo $object;
        


$conn->close();
?>