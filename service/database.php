<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "eht";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}


?>