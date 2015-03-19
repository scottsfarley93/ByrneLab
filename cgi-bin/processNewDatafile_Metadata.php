<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
}
if(isset($_POST['fileID'])){
	$fileID = strip_tags(stripslashes($_POST['fileID']));
}else{
	$fileID = "";
}
if(isset($_POST['CoreName'])){
	$coreName = strip_tags(stripslashes($_POST['CoreName']));
}else{
	$coreName = "";
}
if(isset($_POST['numTaxa'])){
	$numTaxa = strip_tags(stripslashes($_POST['numTaxa']));
}else{
	$numTaxa = 0;
}
if(isset($_POST['numLevels'])){
	$numLevels = stripslashes(strip_tags($_POST['numLevels']));
}else{
	$numLevels = 0;
}
if(isset($_POST['minDepth'])){
	$minDepth = stripslashes(strip_tags($_POST['minDepth']));
}else{
	$minDepth = -9999;
}
if(isset($_POST['maxDepth'])){
	$maxDepth = stripslashes(strip_tags($_POST['maxDepth']));
}else{
	$maxDepth = -9999;
}
if(isset($_POST['fileName'])){
	$fileName = strip_tags($_POST['fileName']);
}else{
	$fileName = "None";
}
if(isset($_POST['lastModified'])){
	$lastModified = strip_tags(stripslashes($_POST['lastModified']));
	$lastModTime = strtotime($lastModified);
	$lastModFormat = date("Y-m-d", $lastModTime);
}else{
	$lastModFormat = "1900-01-01";
}
$nowFormat = date("Y-m-d");
$fileReference = "../datafiles/" . $username . "_" . $fileName;
$checkSql = "SELECT * FROM `Datafiles` WHERE CoreID = '$coreName' AND User='$username' AND DatafileName='$fileID'";
$checkResult = mysqli_query($connection, $checkSql);
if(!$checkResult){
	die("Error: " . mysqli_error($connection));
}
$checkRows = mysqli_num_rows($checkResult);
if ($checkRows == 0){
	$sql = "INSERT INTO `Datafiles` VALUES (Default, '$fileID', '$coreName', $numLevels, $numTaxa, '$fileReference', $minDepth, $maxDepth, '$lastModFormat', '$nowFormat', 1, '$username')";
}else{
	$sql = "UPDATE `Datafiles` SET NumTaxa=$numTaxa, NumLevels=$numLevels, MaxDepth=$maxDepth, MinDepth=$minDepth, Uploaded='$nowFormat', Version=Version+1 WHERE CoreID='$coreName' AND DatafileName='$fileID' AND User='$username'";
}

$result = mysqli_query($connection, $sql);
if(!$result){
		die("Couldn't complete database insertion. Error: " . mysqli_error($connection));
	}else{
		echo "Success";
	}


?>
