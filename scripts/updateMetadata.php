<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = '';
}
if(isset($_POST['coreName'])){
	$coreName = strip_tags(stripslashes($_POST['coreName']));
}else{
	$coreName = "";
}
if(isset($_POST['siteName'])){
	$siteName = strip_tags(stripslashes($_POST['siteName']));
}else{
	$siteName = "";
}
if(isset($_POST['minAge'])){
	$minAge = floatval(strip_tags(stripslashes($_POST['minAge'])));
}else{
	$minAge = -9999;
}
if(isset($_POST['maxAge'])){
	$maxAge = floatval(strip_tags(stripslashes($_POST['maxAge'])));
}else{
	$maxAge = -9999;
}
if(isset($_POST['minDepth'])){
	$minDepth = floatval(strip_tags(stripslashes($_POST['minDepth'])));
}else{
	$minDepth = -9999;
}
if(isset($_POST['maxDepth'])){
	$maxDepth = floatval(strip_tags(stripslashes($_POST['maxDepth'])));
}else{
	$maxDepth = -9999;
}
if(isset($_POST['lat'])){
	$latitude = floatval(strip_tags(stripslashes($_POST['lat'])));
}else{
	$latitude = -9999;
}
if(isset($_POST['lon'])){
	$longitude = floatval(strip_tags(stripslashes($_POST['lon'])));
}else{
	$longitude = -9999;
}
if(isset($_POST['dateCored'])){
	$dateCored = strip_tags(stripslashes($_POST['dateCored']));
}else{
	$dateCored = "1900-01-01";
}
if(isset($_POST['waterDepth'])){
	$waterDepth = strip_tags(stripslashes($_POST['waterDepth']));
}
$sql = "UPDATE `Cores` SET SiteName='$siteName', MinAge=$minAge, MaxAge=$maxAge, MinDepth=$minDepth, MaxDepth=$maxDepth, Latitude=$latitude, Longitude=$longitude, WaterDepth=$waterDepth, DateCored='$dateCored' WHERE CoreName = '$coreName' AND User='$username'";
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Error: " . mysqli_error($connection));
}else{
	echo "Success";
}


?>