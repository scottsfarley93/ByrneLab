<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
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
$sql1 = "SELECT FileReference FROM `Datafiles` WHERE CoreID ='$coreName' AND User='$username' AND DatafileName='$fileName'";
$result1 = mysqli_query($connection, $sql1);
if(!$result1){
	die("Couldn't select file references from datafile table. Error: " . mysql_error($connection));
}else{
	while($row = mysqli_fetch_assoc($result1)){
		$fileRef = $row['FileReference'];
		if(!unlink($fileRef)){
			die("Couldn't delete data files.");
		}

	}
}
$deleteSql = "DELETE FROM `Datafiles` WHERE CoreID ='$coreName' AND User='$username' AND DatafileName='$fileName'";
$deleteResult = mysqli_query($connection, $deleteSql);
if(!$deleteResult){
	die("Couldn't delete associated datafile metadata.  Error: " .mysqli_error($connection));
}else{
	echo "Success";
}

?>