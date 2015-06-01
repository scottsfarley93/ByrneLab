<?php
    session_start();
	$_SESSION['user'] = "";
	$_SESSION['loggedIn'] = "FALSE";
	$_SESSION = array();
	session_destroy();
	header("Location: ../default.html");
	echo var_dump($_SESSION);
?>