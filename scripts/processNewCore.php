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
	$minAge = strip_tags(stripslashes($_POST['minAge']));
	if($minAge == ""){
		$minAge = -9999;
	}
}else{
	$minAge = -9999;
}
if(isset($_POST['maxAge'])){
	$maxAge = strip_tags(stripslashes($_POST['maxAge']));
	if($maxAge == ""){
		$maxAge = -9999;
	}
}else{
	$maxAge = -9999;
}
if(isset($_POST['minDepth'])){
	$minDepth = strip_tags(stripslashes($_POST['minDepth']));
	if($minDepth == ""){
		$minDepth = -9999;
	}
}else{
	$minDepth = -9999;
}
if(isset($_POST['maxDepth'])){
	$maxDepth = strip_tags(stripslashes($_POST['maxDepth']));
	if($maxDepth == ""){
		$maxDepth = -9999;
	}
}else{
	$maxDepth = -9999;
}
if(isset($_POST['lat'])){
	$latitude = floatval(strip_tags(stripslashes($_POST['lat'])));
	if($latitude == ""){
		$latitude = -9999;
	}
}else{
	$latitude = -9999;
}
if(isset($_POST['lon'])){
	$longitude = floatval(strip_tags(stripslashes($_POST['lon'])));
	if($longitude == ""){
		$longitude = -9999;
	}
}else{
	$longitude = -9999;
}
if(isset($_POST['waterDepth'])){
	$waterDepth = strip_tags(stripslashes($_POST['waterDepth']));
	if($waterDepth == ""){
		$waterDepth = -9999;
	}
}else{
	$waterDepth = -9999;
}
if(isset($_POST['dateCored'])){
	$dateCored = strip_tags(stripslashes($_POST['dateCored']));
	if($dateCored == ""){
		$dateCored = "1900-01-01";
	}
}else{
	$dateCored = "1900-01-01";
}
if(isset($_POST['protect'])){
	$protect = $_POST['protect'];
	if($protect == 'on'){
		$protect = 1;
	}else{
		$protect = 0;
	}
}else{
	$protect = 0;
}
if(isset($_POST['pass'])){
	$pass = hash('sha512', $_POST['pass']);
}else{
	$pass = "";
}
$sql = "INSERT INTO `Cores` VALUES (Default, '$coreName', '$siteName', $minAge, $maxAge, $minDepth, $maxDepth, $latitude, $longitude, $waterDepth, '$dateCored', '$username', $protect, '$pass')";
$result = mysqli_query($connection, $sql);
if(!$result){
	echo "MAX DEPTH : " . $maxDepth;
	die("Couldn't complete data insertion. Error message: " . mysqli_error($connection));
}else{
	echo "Success";
}


?>
