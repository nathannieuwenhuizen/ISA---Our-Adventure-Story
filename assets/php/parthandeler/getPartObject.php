<?php
//MySQL Database Connect 
include '../connect.php'; 
include '../globalfunctions.php'; 

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
$sql = "SELECT * FROM storyparts WHERE ID = $storyPartID LIMIT 1";
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
$optionList = "";//defined from the next sql call
$authorID;

//if there is any result
if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["ID"]. "<br>";
        $start = $row["start"];
        $end = $row["end"];
        $option_text = removeAllTags($row["option_text"]);
        $content_text = html_entity_decode(( removeScriptTags($row["content_text"])));
        // $content_text = html_entity_decode(str_replace("\n", " ABC ",  removeScriptTags($row["content_text"])));
        $question_text = ($row["question_text"]);
        $optionIDs = $row["option_IDs"];
        $layer = $row["layer"];
        $image = $row["image"];
        $parentID = $row["parentID"];
        $storyID = $row["storyID"];
        $authorID = $row["authorID"];

    }
} else {
    //there are no results
    // echo "0 results";
}
// echo $option_text . "<br>";

$authorName = 'anonymous';
if ($authorID != -1) {
    $sql = "SELECT username FROM users WHERE id = $authorID LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    //if there is any result
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $authorName = $row["username"];
        }
    }
}

/*
Loads in the option ids and text associated with this story part.
 */
//create an array from the options.
$optionArray = explode(",", $optionIDs);
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

/*
Loads the story title from a different table;
*/
$storyTitle= "";
$startID= "";
$sql = "SELECT * FROM `storyinfo` WHERE  ID = $storyID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $storyTitle = $row["Name"];
        $startID = $row["Introduction_ID"];
        $creatorID = $row["AuthorID"];
    }
} else {
    //there are no results
    // echo "0 results";
}


require '../patreon/src/API.php';
require '../patreon/src/Oauth.php';
include '../patreon/patreonCalls.php'; 
session_start();

$canEdit = false;
//if user is creator
if (IsCreator($conn, $storyID)) {
    //if user is pledging
    if (IsPledger(100)) { 
        $canEdit = true;
    }
} elseif ($optionIDs == "" && $end == 0 && $start == 0) {
    $canEdit = true;
}
if (StoryIsOpen($conn, $storyID)) {
    // $canEdit = true;
    $status = 1;
    // echo "creator is supporting";
} else {
    $status = 0;
    $canEdit = false;
    // echo "creator is NOT supporting";

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
    "storyID": "'. $storyID.'",
    "authorName": "'. $authorName.'",
    "optionList": "'. $optionList.'",
    "canEdit": "'. $canEdit.'",
    "status": "'. $status.'",
    "likeMessage": "'. $likeMessage.'",
    "like": "'. $like.'",
    "startID": "'. $startID.'",
    "amountOfLikes": "'. $amountOfLikes.'",
    "storyTitle": "'. $storyTitle.'"
}';
echo $object;
        


$conn->close();
?>