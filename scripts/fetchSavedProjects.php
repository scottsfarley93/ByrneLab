<?php
require_once("../database_access.php");
//check user credentials and make sure they are logged in
session_start();
if(isset($_SESSION['user'])){
		$user = $_SESSION['user'];	
}else{
	$user = "";
}
if (isset($_GET['core'])){
	$core = $_GET['core'];
	if ($core == "all"){
		$sql = "SELECT * FROM `SavedProjects` WHERE User='$user' ORDER BY creationTimestamp DESC";
	}else{
		$sql = "SELECT * FROM `SavedProjects` WHERE User='$user'AND Core='$core' ORDER BY creationTimestamp DESC ";
	}
}else{
	$sql = "SELECT * FROM `SavedProjects` WHERE User='$user' ORDER BY creationTimestamp DESC";
}

//request all datafiles assigned to a user and order them by core and then by timestamp so they appear in a logical order
//timestamps should appear in a logical as long as things are working properly, so just order by core

$result = mysqli_query($connection, $sql);
$Response = array();
if(!$result){
	die("Error: ". mysqli_error($connection));
}
while($row = mysqli_fetch_assoc($result)){
	array_push($Response, $row);
}
echo json_encode($Response);
?>