<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 

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

    }

} else {
    //there are no results
    // echo "0 results";
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
$sql = "SELECT `ID`, `option_text`, `Date` FROM `storyparts` WHERE `storyID` = $storyID ORDER BY `storyparts`.`Date` DESC LIMIT 10 OFFSET $sqlOffset ";
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
        $addedPartsList .= "<a href='story.php?storypart=" . $row["ID"] . "'><li>  <b>". $option ." </b> <p>Added " .  $addeddate . " ago </p> </li> </a>";

    }

} else {
    //there are no results
    $addedPartsList = "0 results";
}

function GetIntervalRounded ($date) {
    $now = new DateTime();
    $current = new DateTime($date);
    $dif = date_diff($now, $current);
    $result =  $result = $dif->format('%s') . " seconds";

    if ($dif->format('%i') != 0) {
        $result = $dif->format('%i') . " minutes";
    }
    if ($dif->format('%h') != 0) {
        $result = $dif->format('%h') . " hours";
    }
    if ($dif->format('%d') != 0) {
        $result = $dif->format('%d') . " days";
    }
    if ($dif->format('%m') != 0) {
        $result = $dif->format('%m') . " months";
    }
    if ($dif->format('%y') != 0) {
        $result = $dif->format('%y') . " months";
    }
    return $result;
}

$conn->close();
?>