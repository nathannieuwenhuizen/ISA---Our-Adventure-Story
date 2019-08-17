<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 
include 'assets/php/globalfunctions.php'; 

require 'assets/php/patreon/src/API.php';
require 'assets/php/patreon/src/Oauth.php';
include 'assets/php/patreon/patreonCalls.php'; 

//retreive url var
$stringname = 'ID';
$storyID = 1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyID = $_GET[$stringname];
}
if ($storyID < 0) {
    $storyID = 0;
}

$stringname = 'offset';
$offset = 1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $offset = $_GET[$stringname];
}
if ($offset < 0) {
    $offset = 0;
}


//general info
$name;
$description;
$date;
$startID;

$sql = "SELECT * FROM storyinfo WHERE ID = $storyID LIMIT 1 ";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $dt = new DateTime($row["Date"]);

        $date = $dt->format('m-d-Y');
        $name = $row["Name"];
        $description = $row["Description"];
        $startID = $row["Introduction_ID"];
        $storyAuthorName = getAuthorName($conn, $row['AuthorID']);
        $storyAuthorID = $row['AuthorID'];
        if ($storyAuthorName == "") {
            $storyAuthorName = "everyone";
        }
    }
}

$canEdit = false;
if ($storyAuthorID == $_SESSION['userID']) {
    $canEdit = true;
}
if (!StoryIsOpen($conn, $storyID)) {
    $canEdit = false;
    $status = "CLOSED";
} else {
    $status = "OPEN";
}

$amountOfParts = 0;
$deepestLayer = 0;
$layerTableValues = "";
//layer count info
$sql = "SELECT layer, COUNT(id) FROM `storyparts` WHERE `storyID` = $storyID GROUP BY layer";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $amountOfParts += $row["COUNT(id)"];
        $deepestLayer = $row["layer"];
        $layerTableValues .= "<tr><td>" . $row["layer"] . "</td><td>" . $row["COUNT(id)"] . "</td></tr>";
    }

} else {
    //there are no results
    // echo "0 results";
}

$amountOfEnds = 0;
$sql = "SELECT COUNT(`end`) FROM `storyparts` WHERE `end` = 1 AND `storyID` = $storyID";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $amountOfEnds = $row["COUNT(`end`)"];
    }

}

$sqlOffset = $offset * 10;
$sql = "SELECT `ID`, `option_text`, `Date`, `image`, `authorID` FROM `storyparts` WHERE `storyID` = $storyID ORDER BY `storyparts`.`Date` DESC LIMIT 10 OFFSET $sqlOffset ";
$result = mysqli_query($conn, $sql);

$addedPartsList = "";

//if there is any result
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $now = new DateTime();

    while($row = mysqli_fetch_assoc($result)) {
        $addeddate = GetIntervalRounded($row["Date"]);
        //echo "id: " . $row["ID"]. "<br>";
        $option =  $row["option_text"];
        if ($option == "") {
            $option = "-start of the story-";
        }
        $image = "";
        if ($row["image"] != "") {
            $image .= "<section></section>";
        }

        $writerName = '';

        $authorID = getAuthorName($conn, $row["authorID"]);
        if ($authorID != "") {
            $writerName = " | written by " . $authorID;
        }
        
        $addedPartsList .= "<a href='story.php?storypart=" . $row["ID"] . "'><li>  <b>". $option ." </b> <p>Added " .  $addeddate . " ago" .$writerName." </p> ". $image." </li> </a>";

    }

} else {
    //there are no results
    $addedPartsList = "0 results";
}

function getAuthorName($conn, $id) {
    if ($id != -1) {
        $sql = "SELECT username FROM users WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        // $authorName = $id;
        //if there is any result
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                return $row["username"];
            }
        }
    }
    return "";
}

$topAuthorsTable = GetTop3Authors($conn, $storyID);

function GetTop3Authors($conn, $storyID)
{

    $sql = "SELECT authorID, COUNT(*) FROM `storyparts` WHERE NOT authorID = -1 AND storyID = $storyID GROUP BY authorID ASC LIMIT 10";
    // $sql = "SELECT * FROM `users`";
    $table = "";
    $result = mysqli_query($conn, $sql);
    // $authorName = $id;
    //if there is any result
    $amount = 0;
    $authorTableValues = "";
    if ($result && mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "there is result";
        while($row = mysqli_fetch_assoc($result)) {
            $amount++;
            $authorTableValues .= "<tr><td>" . getAuthorName($conn, $row["authorID"]) . "</td><td>";
        }
    } else {
        echo "there is no result";
    }
    if ($amount > 0) {
        $table = "<table class ='top3users'><tr><th>Top ".$amount." most active users</th></tr>".$authorTableValues."</table>"; 
    }
    return $table;
}

$conn->close();
?>