<?php
$hostname = "localhost";
$username = "calpalynUser";
$password = "BerkeleyPaleoecology";
$database = "calpalyn";


define("HOST", $hostname);     // The host you want to connect to.
define("USER", $username);    // The database username. 
define("PASSWORD", $password);    // The database password. 
define("DATABASE", $database);    // The database name.

$connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
if(!$connection){
	die("Couldn't connect to database server: " .mysqli_error($connection));
}

?>