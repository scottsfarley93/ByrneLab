<?php
require_once("../database_access.php");
if(isset($_POST['ticNum'])){
	$ticNum = $_POST['ticNum'];
}else{
	$ticNum =0;
}
if(isset($_POST['type'])){
	$type = $_POST['type'];
}else{
	$type = "";
}
if($type == "bug"){
		$sql = "UPDATE `Bugs` SET `Resolved` = TRUE WHERE `RecordID` = $ticNum";
}else if($type =='feature'){
		$sql = "UPDATE `Requests` SET `Resolved` = TRUE WHERE `RecordID`= $ticNum";
}
$response = mysqli_query($connection, $sql);
if(!$response){
	die("Could not complete update: " . mysqli_error($connection));
}else{
	echo("Success");
}

?>