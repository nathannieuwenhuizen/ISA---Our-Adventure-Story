<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 

//retreive database storypart
$storyList = getStoryList($conn, false);

function getStoryList($conn, $filter = false) {
    $sql = "";

    if ($filter) {
        $author = -1;
        if (isset($_SESSION['userID'])) {
            $author = $_SESSION['userID'];
        }
        $sql = "SELECT `ID`, `Name`, `Date` FROM storyinfo WHERE `AuthorID` = $author LIMIT 10";
    } else {
        $sql = "SELECT `ID`, `Name`, `Date` FROM storyinfo LIMIT 10";
    }
    $result = mysqli_query($conn, $sql);
    $list = "";
    //if there is any result
    if ($result && mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $dt = new DateTime($row["Date"]);

            $date = $dt->format('m-d-Y');
            //echo "id: " . $row["ID"]. "<br>";
            $list .= "<a href='storyinfo.php?ID=" . $row["ID"] . "&offset=0'><li>  <b>". $row["Name"] ." </b> <p>Created on " .  $date . " </p> </li> </a>";

        }

    }
    return $list;

}
$myStories = getStoryList($conn, true);

$conn->close();
?>