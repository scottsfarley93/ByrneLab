<?php
require_once("../database_access.php");
if(isset($_POST['type'])){
	$type= $_POST['type'];
}else{
	$type="";
}
if(isset($_POST['desc'])){
	$desc = $_POST['desc'];
}else{
	$desc = "";
}
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = "";
}
if($type == 'bug'){
	$sql = "INSERT INTO `Bugs` VALUES (Default, '$username', 'bug', '$desc', Default, '0000-00-00 00:00:00', '0', FALSE)";
}else{
	$sql = "INSERT INTO `Requests` VALUES (Default, '$username', 'feature', '$desc', Default, '0000-00-00 00:00:00', '0', FALSE)";
}
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Couldn't complete insertion.");
}else{
	echo mysqli_insert_id($connection);
}
?>