<?php
//This file saves references to configuration files min the database so they may be accessed later
//Naming Convention:  "savedProjects/" + userName + '_' + coreName + "_" + timestamp + ".cpn";
session_start();
if (isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}else{
	$user = "";
}
if (isset($_POST['core'])){
	$core = $_POST['core'];
}else{
	$core = "";
}
if(isset($_POST['creationTime'])){
	$time = $_POST['creationTime'];//should be unix timestamp
}else{
	$time = 0;
}
$fileName = "savedProjects/" .$user."_" . $core ."_".$time . ".cpn";
require_once("../database_access.php");
//tables fields are index, username, coreName, creationTimestamp, lastDrawnTimestamp, fileReference
$sql = "INSERT INTO `SavedProjects Values(Default, '$user', $time, Default, '$fileName')";
$result = mysqli_query($connection, $sql);
if(!$result){
	die ("Error");
}else{
	echo "Success";
}

?>