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
$sql1 = "SELECT FileReference FROM `Datafiles` WHERE CoreID ='$coreName' AND User='$username'";
$result1 = mysqli_query($connection, $sql1);
if(!$result1){
	die("Couldn't select file references from datafile table. Error: " . mysql_error($connection));
}else{
	while($row = mysqli_fetch_assoc($result1)){
		$fileName = $row['FileReference'];
		if(!unlink($fileName)){
			die("Couldn't delete datafiles.");
		};
	}
}
$sql2 = "DELETE FROM `Datafiles` WHERE CoreID = '$coreName' AND User='$username' ";
$result2 = mysqli_query($connection, $sql2);
if(!$result2){
	die("Couldn't delete associated datafile metadata.  Error: " .mysqli_error($connection));
}
$sql3 = "SELECT FileReference From `ChronologyFiles` WHERE CoreID='$coreName' AND User='$username'";
$result3 = mysqli_query($connection, $sql3);
if(!$result3){
	die("Couldn't select file references from chronology table. Error: " . mysqli_error($connection));
}else{
	while($row=mysqli_fetch_assoc($result3)){
		$fileName = $row['FileReference'];
		if(!unlink($fileName)){
			die("Couldn't delete chronology file.");
		};
	}
}
$sql4 = "DELETE FROM `ChronologyFiles` WHERE CoreID='$coreName' AND User='$username'";
$result4 = mysqli_query($connection, $sql4);
if(!$result4){
	die("Couldn't remove records from chronlogy table.  Error: ". mysqli_error($connection));
}
$sql = "DELETE FROM `Cores` WHERE CoreName = '$coreName' AND User='$username'";
$result = mysqli_query($connection,  $sql);
if(!$result){
	die("Couldn't delete core. Error: " . mysqli_error($connection));
}
echo "Success";
?>