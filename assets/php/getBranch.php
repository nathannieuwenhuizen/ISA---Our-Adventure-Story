<?php
//MySQL Database Connect 
include './connect.php'; 

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


//branch object
$object = "";
$sql = "SELECT ID, option_text, layer, parentID, `end`  FROM `storyparts` WHERE storyID = $storyID ORDER BY layer, parentID LIMIT 10000";
$result = mysqli_query($conn, $sql);

$counter = 0;

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $object .= '[';
    // echo $result;
    while($row = mysqli_fetch_assoc($result)) {
        $object .= "{";
        $object .= '"id" : "'. $row['ID']  .'",';
        $object .= '"option" : "'. $row['option_text']  .'",';
        $object .= '"layer" : "'. $row['layer']  .'",';
        $object .= '"end" : "'. $row['end']  .'",';
        $object .= '"parentID" : "'. $row['parentID']  .'"';
        if (++$counter == mysqli_num_rows($result)) {
            $object .= "}";
        } else {
            $object .= "},";
        }
       }
    $object .= "]";


} else {
    //there are no results
    echo "";
}


 // output data of each row

echo $object;
$conn->close();
?>