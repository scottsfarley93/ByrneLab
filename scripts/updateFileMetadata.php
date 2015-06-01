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
if(isset($_POST['fileName'])){
	$fileName = strip_tags(stripslashes($_POST['fileName']));
}else{
	$fileName = "";
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
if(isset($_POST['numTaxa'])){
	$numTaxa = floatval(strip_tags(stripslashes($_POST['numTaxa'])));
}else{
	$numTaxa = -9999;
}
if(isset($_POST['numLevels'])){
	$numLevels = floatval(strip_tags(stripslashes($_POST['numLevels'])));
}else{
	$numLevels = -9999;
}
$sql = "UPDATE `Datafiles` SET MinDepth=$minDepth, MaxDepth=$maxDepth, NumLevels=$numLevels, NumTaxa=$numTaxa WHERE CoreID = '$coreName' AND User='$username' ";
$sql .= "AND DatafileName='$fileName'";
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Error: " . mysqli_error($connection));
}else{
	echo "Success";
}


?>