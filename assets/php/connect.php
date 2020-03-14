<?php
$websiteURL = "http://localhost:3000/ISA---Our-Adventure-Story/builds/dev/";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouradventurestorydb";


// $websiteURL = "https://studenthome.hku.nl/~nathan.nieuwenhuizen/assets/ouradventurestory/";
// $servername = "remotemysql.com";
// $username = "1iA2pkiSy7";
// $password = "l9ICitDlpw";
// $dbname = "1iA2pkiSy7";
//port 3306


// $websiteURL = "http://ourinteractivetgcaption.hostingerapp.com/";
// $servername = "remotemysql.com";
// $username = "mTTXio0REs";
// $password = "43Kz77Z0Ql";
// $dbname = "mTTXio0REs";

// $websiteURL = "http://ourinteractivetgcaption.hostingerapp.com/";
// $servername = "localhost";
// $username = "u127270417_devnathan";
// $password = "schorpioen";
// $dbname = "u127270417_ourstoryDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>