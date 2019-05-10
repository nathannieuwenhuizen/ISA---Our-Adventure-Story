<?php

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
//echo $storyID; 



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

$optionList = "";//defined from the next sql call

//if there is any result
if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["ID"]. "<br>";
        $start = $row["start"];
        $end = $row["end"];
        $option_text = $row["option_text"];
        $content_text = $row["content_text"];
        $question_text = $row["question_text"];
        $optionIDs = $row["option_IDs"];
        $layer = $row["layer"];
        $image = $row["image"];
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
$sql = "SELECT option_text, ID FROM storyparts WHERE ID = ";
for ($i = 0; $i < sizeof($optionArray); $i++) {
    $sql .= "'". (int)$optionArray[$i] . "'";
    if ($i != sizeof($optionArray) - 1) {
        $sql .= " AND ";
    }
}
echo $sql . "<br>";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["ID"]. "<br>";
        $optionList .= "<li> <a href='?storypart=2'> ". $row["option_text"] ." </a> </li> ";
        
    }
} else {
    //there are no results
    echo "0 results";
}
echo "list of options: " . $optionList;

// echo "<br>" . $start;
// echo "<br>" . $end;
// echo "<br>" . $option_text;
// echo "<br>" . $content_text;
// echo "<br>" . $question_text;
// echo "<br>" . $optionIDs;
// echo "<br>" . $layer;
// echo "<br>" . $image;


$conn->close();
?>