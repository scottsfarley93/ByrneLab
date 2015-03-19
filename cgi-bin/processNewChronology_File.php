<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = "scottsfarley";
}
if(isset($_FILES['upload'])){
	$coreName = $_FILES['upload']['name'];
}else{
	$coreName = "";
}

if(isset($_FILES['upload'])){
	$dest = "../datafiles/" . $username . "_" . $coreName . "_Chronology.csv";
	if ($_FILES['upload']['size'] > 10000){
		die(-1);//file is too big
	}
	if(!move_uploaded_file($_FILES['upload']['tmp_name'], $dest)){
		die(0);//couldn't save the file
	}else{
		echo 1;
	}
}




?>