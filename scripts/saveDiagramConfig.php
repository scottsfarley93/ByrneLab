<?php
require_once("../database_access.php");
session_start();
if (isset($_POST['config'])){
	$c = $_POST['config'];
}else{
	$response = array("success" => "false", "error" => "true", "user"=>$user, message => "No configuration file received.");
	$R = json_encode($response);
	echo $R;
}
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}else{
	$user = '';
}
if (isset($_POST['core'])){
	$core = $_POST['core'];
}else{
	$response = array("success" => "false", "error" => "true", "user"=>$user, message => "No core received.");
	$R = json_encode($response);
	echo $R;
}
$coreURL = urlencode($core);
$n = time();
$n = (string)$n;
$fname = "../savedProjects/" . $user . "_" . $coreURL . "_". $n . ".cpn";
$f = fopen($fname, "w") or die("Unable to open the file: " . $fname);
fwrite($f, $c);
fclose($f);
$sql = "INSERT INTO `SavedProjects` Values (Default, '$user', '$core', Default, '$fname')";
$query = mysqli_query($connection, $sql);
if (!$query){
	$response = array("success" => "false", "error" => "true", "user"=>$user, "core"=>$coreURL, "timestamp"=>$n, message => "Database error.");
	$R = json_encode($response);
	echo $R;
}else{
	$response = array("success" => "true", "error" => "false", "user"=>$user, "core"=>$coreURL, "timestamp"=>$n);
	$R = json_encode($response);
	echo $R;
}
?>