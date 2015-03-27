<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = "";
}
$sql = "SELECT DISTINCT CoreName FROM `Cores`  WHERE `User` ='$username'";
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Can't select. Error: " . mysqli_error($connection));
}
$response = array();
while($row = mysqli_fetch_assoc($result)){
	array_push($response, $row['CoreName']);
}
echo json_encode($response);
?>