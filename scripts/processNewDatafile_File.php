<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = '';
}
if(isset($_FILES['upload'])){
	$dest = "../datafiles/" . $username . "_";
	$fileName = $_FILES['upload']['name'];
	$dest .= $fileName;
	if ($_FILES['upload']['size'] > 10000000){
		die(-1);//file is too big
	}
	if(!move_uploaded_file($_FILES['upload']['tmp_name'], $dest)){
		die(0);//couldn't save the file
	}else{
		echo 1;
	}
}

?>
