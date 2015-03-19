<?php
    require_once("../database_access.php");
	if(isset($_POST['fName'])){
		$fileName = strip_tags(stripslashes($_POST['fName']));
	}else{
		$fileName = "";
	}
	if(isset($_POST['SiteName'])){
		$siteName = strip_tags(stripslashes($_POST['SiteName']));
	}else{
		$siteName = "";
	}
	
	$sql = "SELECT * FROM "
?>