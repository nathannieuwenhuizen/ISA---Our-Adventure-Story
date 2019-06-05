<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 

//retreive database storypart
$sql = "SELECT `ID`, `Name`, `Date` FROM storyinfo LIMIT 10";
$result = mysqli_query($conn, $sql);

$storyList = "";

//if there is any result
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $dt = new DateTime($row["Date"]);

        $date = $dt->format('m-d-Y');
        //echo "id: " . $row["ID"]. "<br>";
        $storyList .= "<a href='storyinfo.php?ID=" . $row["ID"] . "&offset=0'><li>  <b>". $row["Name"] ." </b> <p>Created on " .  $date . " </p> </li> </a>";

    }

} else {
    //there are no results
    $storyList = "0 results";
}

$conn->close();
?>