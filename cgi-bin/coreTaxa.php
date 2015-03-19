<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
}
if(isset($_POST['core'])){
	$coreName = strip_tags(stripslashes($_POST['core']));
}else{
	$coreName = "Test Core 4";
}

$sql = "SELECT DatafileName, FileReference FROM `Datafiles` WHERE CoreID='$coreName' AND User='$username'";
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Couldn't query database. Error:  " . mysqli_error($connection));
}
$response = array();
//iterate through files
while($row = mysqli_fetch_assoc($result)){
	$name = $row['DatafileName'];
	$file = $row['FileReference'];
	$file = fopen($file, "r");
	//get the header
	$header = fgetcsv($file);
	//push the header into the response
	$r = array("File"=>$name, "Taxa"=>$header);
	array_push($response, $r);
	fclose($file);
}
echo json_encode($response);
?>