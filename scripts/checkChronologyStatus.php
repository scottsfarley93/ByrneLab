<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
}
if(isset($_POST['coreID'])){
	$coreID = $_POST['coreID'];
}else{
	$coreID = "";
}
$sql = "SELECT * FROM `ChronologyFiles` WHERE CoreID = '$coreID' AND User='$username'";
$result = mysqli_query($connection, $sql);
$numRows = mysqli_num_rows($result);
echo $numRows;
?>