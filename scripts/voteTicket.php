<?php
require_once("../database_access.php");
if(isset($_POST['ticNum'])){
	$ticNum = $_POST['ticNum'];
}else{
	$ticNum =0;
}
if(isset($_POST['oper'])){
	$oper = $_POST['oper'];
}else{
	$oper = "add";
}
if(isset($_POST['type'])){
	$type = $_POST['type'];
}else{
	$type = "";
}
if($type == "bug"){
	if ($oper == "add"){
		$sql = "UPDATE `Bugs` SET `Votes` = `Votes` + 1 WHERE `RecordID` = $ticNum";
	}else{
		$sql = "UPDATE `Bugs` SET `Votes` = `Votes` - 1 WHERE `RecordID` = $ticNum";
	}
}else if($type =='feature'){
	if ($oper == "add"){
		$sql = "UPDATE `Requests` SET `Votes` = Votes + 1 WHERE `RecordID`= $ticNum";
	}else{
		$sql = "UPDATE `Requests` SET `Votes` = `Votes` - 1 WHERE `RecordID` = $ticNum";
	}
}
$response = mysqli_query($connection, $sql);
if(!$response){
	die("Could not complete update: " . mysqli_error($connection));
}else{
	echo("Success");
}

?>