<?php
//This file saves references to configuration files min the database so they may be accessed later
//Naming Convention:  "savedProjects/" + userName + '_' + coreName + "_" + timestamp + ".cpn";
session_start();
//echo var_dump($_POST);
if (isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}else{
	$user = "";
}
if (isset($_POST['core'])){
	$core = $_POST['core'];
}else{
	die("No core received");
}
if(isset($_POST['createTime'])){
	$time = $_POST['createTime'];//should be unix timestamp
}else{
	die ("No timestamp  received");
}
$fileName = "../savedProjects/" .$user."_" . $core ."_".$time . ".cpn";
require_once("../database_access.php");
//tables fields are index, username, coreName, creationTimestamp, lastDrawnTimestamp, fileReference
$sql = "INSERT INTO `SavedProjects`  Values (Default, '$user', '$core', $time, Default, '$fileName')";
$result = mysqli_query($connection, $sql);
if(!$result){
	echo mysqli_error($connection);
	die("Error");
}else{
	echo "Success";
}

?>