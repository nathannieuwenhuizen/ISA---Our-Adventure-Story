<?php
$websiteURL = "http://localhost:3000/ISA---Our-Adventure-Story/builds/dev/";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouradventurestorydb";
$name = "TG Captions";

// $websiteURL = "https://studenthome.hku.nl/~nathan.nieuwenhuizen/assets/ouradventurestory/";
// $servername = "remotemysql.com";
// $username = "NBEf0XGIkY";
// $password = "5PpbZUQgNX";
// $dbname = "NBEf0XGIkY";
// $name = "Adventure Stories";

// $websiteURL = "https://ourinteractivetgcaption.000webhostapp.com/";
// $servername = "remotemysql.com";
// $username = "mTTXio0REs";
// $password = "43Kz77Z0Ql";
// $dbname = "mTTXio0REs";
// $name = "TG Captions";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


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

?>