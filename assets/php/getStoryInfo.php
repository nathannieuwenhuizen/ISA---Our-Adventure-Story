<?php
//MySQL Database Connect 
include 'assets/php/connect.php'; 
include 'assets/php/globalfunctions.php'; 

require 'assets/php/patreon/src/API.php';
require 'assets/php/patreon/src/OAuth.php';
include 'assets/php/patreon/patreonCalls.php'; 

//retreive url var
$storyID =  GetURLVariable('ID', 0, -1);

$offset = GetURLVariable('offset',0, -1);

$orderBy = GetURLVariable('orderby',0,  4);

$search = GetURLVariable('search',-1,-1, "");
$searchAmmount = 0;
$searchFilter = "";
if ($search != "") {
    if (strpos($search, ' ') !== false) {
        $parts = preg_split('/\s+/', $search);
        $searchFilter = "AND(";
        for ($i = 0; $i < count($parts); $i++) { 
            if ($i != 0) {
                $searchFilter .= " OR ";
            }

            $searchFilter .= "(storyparts.option_text like '%". $parts[$i] ."%')";
        }
        $searchFilter .= ")";
    } else {
        $searchFilter = "AND storyparts.option_text like '%". $search ."%'";

    }
}

//general info
$name;
$description;
$date;
$startID;
$amountOfLikes;


$sql = "SELECT storyinfo.Name, storyinfo.Description, storyinfo.Introduction_ID, storyinfo.AuthorID,  storyinfo.Date,
users.username, COUNT(likes.storyID) as likes  FROM storyinfo 
LEFT JOIN users ON storyinfo.AuthorID = users.id 
LEFT JOIN likes ON likes.storyID = storyinfo.ID  
WHERE storyinfo.ID = $storyID LIMIT 1";

//$sql = "SELECT * FROM storyinfo LEFT JOIN users ON storyinfo.AuthorID = users.id WHERE storyinfo.ID = $storyID LIMIT 1 ";

// $sql = "SELECT * FROM storyinfo WHERE ID = $storyID LIMIT 1 ";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $dt = new DateTime($row["Date"]);

        $date = $dt->format('m-d-Y');
        $name = $row["Name"];
        $description = $row["Description"];
        $startID = $row["Introduction_ID"];
        $storyAuthorName = $row['username'];
        $storyAuthorID = $row['AuthorID'];
        $amountOfLikes = $row['likes'];
        if ($storyAuthorName == "") {
            $storyAuthorName = "everyone";
        }
    }
}

$canEdit = false;
if (isset( $_SESSION['userID'])) {
    if ($storyAuthorID == $_SESSION['userID']) {
        $canEdit = true;
    }
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

} 

if ($search != "") {
    $sql = "SELECT COUNT(id) FROM `storyparts` WHERE `storyID` = $storyID $searchFilter";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $searchAmmount = $row["COUNT(id)"];
        }
    }
} else {
    $searchAmmount = $amountOfParts;
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

//list of parts
switch ($orderBy) {
    case 0:
    $sqlOrderBy = "`storyparts`.`Date`DESC";
    break;
    case 1:
    $sqlOrderBy = "`storyparts`.`Date` ASC";
    break;
    case 2:
    $sqlOrderBy = "COUNT(likes.storypartID) DESC";
    break;
    case 3:
    $sqlOrderBy = "RAND()";
    break;
    default:
    $sqlOrderBy = "`storyparts`.`Date`DESC";
    break;
}
$sqlOffset = $offset * 10;
$sql = "SELECT  storyparts.ID, storyparts.option_text, storyparts.Date, storyparts.image, storyparts.authorID, users.username , COUNT(likes.storypartID) as likes 
FROM `storyparts`
LEFT JOIN likes ON likes.storypartID = storyparts.ID  
LEFT JOIN users ON storyparts.authorID = users.id  WHERE storyparts.storyID = $storyID $searchFilter
GROUP BY storyparts.ID ORDER BY $sqlOrderBy LIMIT 10 OFFSET $sqlOffset" ;

//$sql = "SELECT storyparts.ID, storyparts.option_text, storyparts.Date, storyparts.image, storyparts.authorID, users.username FROM `storyparts` LEFT JOIN users ON storyparts.authorID = users.id WHERE `storyID` = $storyID ORDER BY `storyparts`.`Date` DESC LIMIT 10 OFFSET $sqlOffset ";
$result = mysqli_query($conn, $sql);

$addedPartsList = "";

//if there is any result
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $now = new DateTime();

    while($row = mysqli_fetch_assoc($result)) {
        $addeddate = GetIntervalRounded($row["Date"]);
        //echo "id: " . $row["ID"]. "<br>";

        //check if start
        $option =  $row["option_text"];
        if ($option == "") {
            $option = "-start of the story-";
        }

        //image check
        $image = "";
        if ($row["image"] != "") {
            $image .= "<section></section>";
        }

        //author check
        $writerName = '';
        $authorID = $row["username"];
        if ($authorID != "") {
            $writerName = " | written by " . $authorID;
        }
        
        //likes check
        $likesText = '';
        $likes = $row["likes"];
        if ($likes != 0) {
            $likesText = " | " . $likes . " likes";
        }
        
        //adds the html text to json object
        $addedPartsList .= "<a name='".$row["image"]."' href='story.php?storypart=" . $row["ID"] . "'><li>  <b>". $option ." </b> <p>Added " .  $addeddate . " ago" .$writerName . $likesText . " </p> ". $image." </li> </a>";

    }
 
} else {
    //there are no results
    $addedPartsList = "no results";
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

    $sql = "SELECT authorID, COUNT(*), username FROM `storyparts` LEFT JOIN users ON storyparts.authorID = users.id  WHERE NOT authorID = -1 AND storyID = $storyID GROUP BY authorID ORDER BY COUNT(*) DESC LIMIT 10";
    // $sql = "SELECT * FROM `users`";
    $table = "";
    $result = mysqli_query($conn, $sql);
    // $authorName = $id;
    //if there is any result
    $amount = 0;
    $authorTableValues = "";
    if ($result && mysqli_num_rows($result) > 0) {
        // output data of each row
        // echo "there is result"; 
        while($row = mysqli_fetch_assoc($result)) {
            $amount++;
            $authorTableValues .= "<tr><td><a href ='./user/profile.php?user=" . $row["authorID"] . "'> " .  $row["username"]. " </a></td><td>";
        }
    } else {
        // echo "there is no result";
    }
    if ($amount > 2) {
        $table = "<hr><table class ='top3users'><tr><th>Top ".$amount." most active users</th></tr>".$authorTableValues."</table>"; 
    }
    return $table;
}

$conn->close();
?>