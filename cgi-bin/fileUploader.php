<?php
session_start();
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
}
if(isset($_FILES['upload'])){
	$dest = "../datafiles_" . $username . "_";
	$fileName = $_FILES['upload']['name'];
	$dest .= $fileName;
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