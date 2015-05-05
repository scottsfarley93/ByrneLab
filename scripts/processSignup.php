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
	if(isset($_POST['username'])){
		$username = $_POST['username'];
	}else{
		$username = "";
	}
$sql = "SELECT * FROM `Users` WHERE `Username` = '$username'";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) != 0){
	echo "0";
}else{
	$sql = "INSERT INTO `Users` VALUES (Default, '$email', '$name', '$pass', Default, '$username')";
	$result = mysqli_query($connection, $sql);
	if(!$result){
		die(-1);
	}else{
		//simulate login by setting session values
		$_SESSION['user'] = $username;
		$_SESSION['loggedIn'] = "TRUE";
		echo "1";
	}
}
?>