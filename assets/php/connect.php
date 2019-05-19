<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "ouradventurestorydb";
$servername = "remotemysql.com";
$username = "NBEf0XGIkY";
$password = "5PpbZUQgNX";
$dbname = "NBEf0XGIkY";
// $servername = "remotemysql.com";
// $username = "mTTXio0REs";
// $password = "43Kz77Z0Ql";
// $dbname = "mTTXio0REs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//retreive url var
$stringname = 'storypart';
$storyID = 1;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $storyID = $_GET[$stringname];

}
if ($storyID < 1) {
    $storyID = 1;
}



//retreive database storypart
$sql = "SELECT * FROM storyparts WHERE ID = $storyID";
$result = mysqli_query($conn, $sql);


$start;
$end;
$option_text;
$content_text;
$question_text;
$optionIDs;
$layer;
$image;
$parentID;
$optionList = "";//defined from the next sql call

//if there is any result
if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["ID"]. "<br>";
        $start = $row["start"];
        $end = $row["end"];
        $option_text = $row["option_text"];
        $content_text = html_entity_decode(nl2br($row["content_text"]));
        $question_text = $row["question_text"];
        $optionIDs = $row["option_IDs"];
        $layer = $row["layer"];
        $image = $row["image"];
        $parentID = $row["parentID"];
    }
} else {
    //there are no results
    echo "0 results";
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
        //echo "id: " . $row["ID"]. "<br>";
        $optionList .= "<li> <a href='?storypart=" . $row["ID"] . "'> ". $row["option_text"] ." </a> </li> ";
    }
} else {
    //there are no results
    //echo "0 results";
}

/*
Loads the story title from a different table;
*/
$storyTitle= "";
$sql = "SELECT * FROM `storyinfo`";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $storyTitle = $row["Name"];
    }
} else {
    //there are no results
    echo "0 results";
}


$conn->close();
?>