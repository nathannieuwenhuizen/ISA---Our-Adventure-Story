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
        $author = 0;
        if (isset($_SESSION['userID'])) {
            $author = $_SESSION['userID'];
        }
        $sql = "SELECT `ID`, `Name`, `Date`, `AuthorID` FROM storyinfo WHERE `AuthorID` = $author LIMIT 10";
    } else {
        $sql = "SELECT `ID`, `Name`, `Date`, `AuthorID` FROM storyinfo LIMIT 10";
    }
    $result = mysqli_query($conn, $sql);
    $list = "";
    //if there is any result
    if ($result && mysqli_num_rows($result) > 0) {
        // output data of each row

        while($row = mysqli_fetch_assoc($result)) {
            $dt = new DateTime($row["Date"]);

            $creatorName = getUserName($conn,$row['AuthorID']);
            $creatorText = "";
            if ($creatorName != "") {
                $creatorText= "<p>Started by " . $creatorName ." </p>";
            }

            $date = $dt->format('m-d-Y');
            $amount = amountofParts($conn, $row['ID']);
            $cureentSotryID =  $row['ID'];
            // echo "....story function return: ". StoryIsOpen($conn, $cureentSotryID);
            // if (!StoryIsOpen($conn, $cureentSotryID)){
            //     $status = "CLOSED";
            // } else {
            //     $status ="OPEN";
            // }
            // //echo "id: " . $row["ID"]. "<br>";
            $list .= "<a href='".$basePath."storyinfo.php?ID=" . $row["ID"] . "&offset=0'>
            <li><div class='name'>  <b>". $row["Name"] ." </b> </div> 
            <div class='info'>
            <p>Created on " .  $date . " </p> ". 
            "<p>" .  $amount . " parts long</p> ". 
            // "<p>Status " .  $status . "</p> ". 
            $creatorText." </div></li> </a>";
        }

    }
    return $list;
}

function NaviagationButtons($val, $total, $link = "?offset=") {
    echo '<div class="navigationButtons"> ';
    if ($val > 0) {
        echo '<a class="something"href="'.$link.'0"><< </a> ';
        echo '<a class="something"href="' . $link . ($val - 1) . '">< </a>';
    }
    echo '<b>' . ($val + 1) . ' / ' . Max(1, Round($total / 10)) . '</b>';
    if ($val + 1 < Round( $total / 10)) {
        echo '<a class="something" href="'. $link . ($val + 1) . ' "> > </a>';
        echo '<a class="something" href="'. $link .  (Round($total / 10) - 1) . '"> >> </a>';
    }
    echo '</div>';
}
function PartList($result) {

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
            $addedPartsList .= "<a href='../story.php?storypart=" . $row["ID"] . "'><li>  <b>". $option ." </b> <p>Added " .  $addeddate . " ago </p> ". $image." </li> </a>";

        }

    } else {
        //there are no results
        $addedPartsList = "0 results";
    }
    return $addedPartsList;
}


function getUserName($conn, $id) {
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

function amountofParts($conn, $storyID) {
    $amountOfParts = 0;
    $sql = "SELECT COUNT(id) FROM `storyparts` WHERE `storyID` = $storyID";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $amountOfParts += $row["COUNT(id)"];
        }
    }
    return $amountOfParts;
}