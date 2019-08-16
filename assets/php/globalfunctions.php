<?php

function removeScriptTags($data) {
    return  strip_tags($data, '<p><h1><h2><h3><h4><h5><a><b><i>');
}
function removeAllTags($data) {
    return  strip_tags($data);
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
function strictEmpty($var) {
    // Delete this line if you want space(s) to count as not empty
    $var = trim($var);
    if(isset($var) === true && $var === '') {
        return true;
        // It's empty
    }
    else {
        return false;
        // It's not empty
    }
}

//checks id the visitor is the creator of story id
function IsCreator($conn, $id) {
    $sql = "SELECT AuthorID FROM storyinfo WHERE ID = $id LIMIT 1 ";
    $result = mysqli_query($conn, $sql);

    $storyAuthorID = "";
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $storyAuthorID = $row['AuthorID'];
        }
    }
    if (isset($_SESSION['userID'])) {
        if ($storyAuthorID == $_SESSION['userID']) {
            return true;
        }
    }
    return false;
}

function getStoryList($conn, $filter = false, $basePath = "./") {
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
            $list .= "<a href='".$basePath."storyinfo.php?ID=" . $row["ID"] . "&offset=0'><li>  <b>". $row["Name"] ." </b> <p>Created on " .  $date . " </p> </li> </a>";

        }

    }
    return $list;

}

?>