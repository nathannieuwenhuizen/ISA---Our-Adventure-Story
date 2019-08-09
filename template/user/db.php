<?php
/* Database connection settings */
// require('../assets/php/connect.php');
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'ouradventurestorydb';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
