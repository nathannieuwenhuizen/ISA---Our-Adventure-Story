<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouradventurestorydb";
// $servername = "remotemysql.com";
// $username = "NBEf0XGIkY";
// $password = "5PpbZUQgNX";
// $dbname = "NBEf0XGIkY";
// $servername = "remotemysql.com";
// $username = "mTTXio0REs";
// $password = "43Kz77Z0Ql";
// $dbname = "mTTXio0REs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>