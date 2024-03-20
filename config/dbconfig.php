<?php

// $dbhostname = "mysql.levivatravelandtours.com";
// $dbusername = "levivaadmin";
// $dbpassword = "Elvis2007!";
$dbhostname = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "levivatravel";

// Create connection
$conn = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>