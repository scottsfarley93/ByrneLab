<?php
session_start();
require_once("../database_access.php");
if (isset($_POST['username'])){
	$username = strip_tags(stripslashes($_POST['username']));
}else{
	$username = "";	
}
if(isset($_POST['password'])){
	$enteredPass = hash('sha512', $_POST['password']);
}else{
	$enteredPass = "";
}
$sql = "SELECT * FROM `Users` WHERE `Username` = '$username'";
$result = mysqli_query($connection, $sql);
if (!$result){
	die(-1);
}
if (mysqli_num_rows($result) == 1){
	$user = mysqli_fetch_assoc($result);
	$storedPass = $user['Password'];
	if ($storedPass == $enteredPass){
		$_SESSION['loggedIn'] = "TRUE";
		$_SESSION['user'] = $username;
		echo "1";
	}else{
		echo "0";
	}
}else{
	echo "0";
}

?>