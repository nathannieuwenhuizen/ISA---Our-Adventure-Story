<?php
$websiteURL = "http://localhost:3000/ISA---Our-Adventure-Story/builds/dev/";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouradventurestorydb";

// $websiteURL = "https://studenthome.hku.nl/~nathan.nieuwenhuizen/assets/ouradventurestory/";
// $servername = "remotemysql.com";
// $username = "NBEf0XGIkY";
// $password = "5PpbZUQgNX";
// $dbname = "NBEf0XGIkY";

$websiteURL = "https://ourinteractivetgcaption.000webhostapp.com/";
$servername = "remotemysql.com";
$username = "mTTXio0REs";
$password = "43Kz77Z0Ql";
$dbname = "mTTXio0REs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>