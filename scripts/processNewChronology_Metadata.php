<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = "scottsfarley";
}
if(isset($_POST['coreName'])){
	$coreName = strip_tags(stripslashes($_POST['coreName']));
}else{
	$coreName = "";
}
if(isset($_POST['numLevels'])){
	$numLevels = strip_tags(stripslashes($_POST['numLevels']));
}else{
	$numLevels = 0;
}
if(isset($_POST['minDepth'])){
	$minDepth = strip_tags(stripslashes($_POST['minDepth']));
}else{
	$minDepth = -9999;
}
if(isset($_POST['maxDepth'])){
	$maxDepth = strip_tags(stripslashes($_POST['maxDepth']));
}else{
	$maxDepth = -9999;
}
if(isset($_POST['maxAge'])){
	$maxAge = strip_tags(stripslashes($_POST['maxAge']));
}else{
	$maxAge = -9999;
}
if(isset($_POST['minAge'])){
	$minAge = strip_tags(stripslashes($_POST['minAge']));
}else{
	$minAge = -9999;
}
if(isset($_POST['lastModified'])){
	$lastModified = strip_tags(stripslashes($_POST['lastModified']));
	$lastModTime = strtotime($lastModified);
	$lastModFormat = date("Y-m-d", $lastModTime);
}else{
	$lastModFormat = "1900-01-01";
}
//file reference 
$dest = "../datafiles/" . $username . "_" . $coreName . "_Chronology.csv";
//now
$now = date('Y-m-d');
//check to see we need to update or insert
$sql = "SELECT * FROM `ChronologyFiles` WHERE CoreID='$coreName' AND User = '$username'";
$result = mysqli_query($connection, $sql);
$numRows = mysqli_num_rows($result);
if($numRows == 0){
	//start a new one
	$sql = "INSERT INTO `ChronologyFiles` VALUES (Default, '$coreName', $numLevels, $minDepth, $maxDepth, $minAge, $maxAge, '$dest', '$lastModFormat', '$now', 1, '$username')";
	$result = mysqli_query($connection, $sql);
	if(!$result){
		die("Couldn't complete database insertion. Error: " . mysqli_error($connection));
	}else{
		echo "Success";
	}
}else{
	//update an old one
	$row = mysqli_fetch_assoc($result);
	$oldVersion = $row['Version'];
	$newVersion = $oldVersion + 1;
	$sql = "UPDATE `ChronologyFiles` SET NumLevels=$numLevels, MinDepth=$minDepth, MaxDepth=$maxDepth, MinAge=$minAge, MaxAge=$maxAge, FileReference='$dest', LastModified='$lastModFormat', Uploaded='$now', Version=$newVersion";
	$sql .= " WHERE User='$username' AND CoreID='$coreName'";
	$result = mysqli_query($connection, $sql);
	if(!$result){
		die("Couldn't update the database. Error: " . mysqli_error($connection));
	}else{
		echo "Success";
	}
	
}

?>