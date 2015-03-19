<?php
require_once("../database_access.php");
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = "scottsfarley";
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