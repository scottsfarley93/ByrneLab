<?php
session_start();
    require_once("../database_access.php");
	if(isset($_POST['name'])){
		$name = strip_tags(stripslashes($_POST['name']));
	}else{
		$name = "";
	}
	if(isset($_POST['email'])){
		$email = strip_tags(stripslashes($_POST['email']));
	}else{
		$email = "";
	}
	if(isset($_POST['password'])){
		$pass = hash('sha512',$_POST['password']);
	}else{
		$pass = "";
	}
$sql = "SELECT * FROM `Users` WHERE `Email` = '$email'";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) != 0){
	echo "A User with that Email Already Exists";
}else{
	$sql = "INSERT INTO `Users` VALUES (Default, '$email', '$name', '$pass', Default)";
	$result = mysqli_query($connection, $sql);
	if(!$result){
		die("Couldn't connect to user server: " . mysqli_error($connection));
	}else{
		echo "Signup Successful";
	}
}
?>