<?php
require_once('../database_access.php');
    session_start();
	if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];
	}else{
		$username = 'scottsfarley';
	}
if(isset($_POST['nickname'])){
	$nickname = strip_tags(stripslashes($_POST['fileName']));
}else{
	$nickname = "";
}
if(isset($_POST['lat'])){
	$lat = floatval(strip_tags(stripslashes($_POST['lat'])));
}else{
	$lat = -1;
}
if(isset($_POST['lon'])){
	$lon = floatval(strip_tags(stripslashes($_POST['lon'])));
}else{
	$lon = -1;
}
if(isset($_POST['protect'])){
	$protect = $_POST['protect'];
	if ($protect == 'on'){
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
if(isset($_POST['site'])){
	$siteName = strip_tags(stripslashes($_POST['site']));
}else{
	$siteName = "";
}
if(isset($_POST['fileName'])){
	$dest = "../datafiles/" . $username . "_";
	$fName = stripslashes(strip_tags($_POST['fileName']));
	$dest .= $fName;
}else{
	$dest = "";
}

$sql = "INSERT INTO `Datafiles` VALUES(Default, '$username', '$dest', $lat, $lon, $protect, '$pass', '$siteName', '$nickname')";
$request = mysqli_query($connection, $sql);
if(!$request){
	die("Couldn't update file metadata. Error: " . mysqli_error($connection));
}else{
	header("Location: ../manageData.html");
}


?>