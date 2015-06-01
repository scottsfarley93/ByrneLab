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
$sql1 = "SELECT FileReference FROM `ChronologyFiles` WHERE CoreID ='$coreName' AND User='$username' ";
$result1 = mysqli_query($connection, $sql1);
if(!$result1){
	die("Couldn't select file references from chronology table. Error: " . mysql_error($connection));
}else{
	while($row = mysqli_fetch_assoc($result1)){
		$fileRef = $row['FileReference'];
		if(!unlink($fileRef)){
			die("Couldn't delete chronology file.");
		};
	}
}
$sql2 = "DELETE FROM `ChronologyFiles` WHERE CoreID = '$coreName' AND User='$username'";
$result2 = mysqli_query($connection, $sql2);
if(!$result2){
	die("Couldn't delete associated chronology metadata.  Error: " .mysqli_error($connection));
}
echo "Success";
?>