<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = '';
}
if(isset($_POST['coreID'])){
	$coreID = $_POST['coreID'];
}else{
	$coreID = "";
}
$sql = "SELECT * FROM `ChronologyFiles` WHERE CoreID = '$coreID' AND User='$username'";
$result = mysqli_query($connection, $sql);
$numRows = mysqli_num_rows($result);

?>