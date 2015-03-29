<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}else{
	$user = '';
}
if (isset($_GET['config'])){
	$c = $_GET['config'];
}else{
	$response = array("success" => "false", "error" => "true", "user"=>$user, 'message' => "No configuration file received.");
	$R = json_encode($response);
	die($R);
}
if (isset($_GET['core'])){
	$core = $_GET['core'];
}else{
	$response = array("success" => "false", "error" => "true", "user"=>$user, message => "No core received.");
	$R = json_encode($response);
	die( $R);
}
$coreURL = urlencode($core);
$n = time();
$n = (string)$n;
$fname = "../savedProjects/" . $user . "_" . $coreURL . "_". $n . ".cpn";
$f = fopen($fname, "w") or die("Unable to open the file: " . $fname);
fwrite($f, $c);
fclose($f);
$sql = "INSERT INTO `SavedProjects` Values (Default, '$user', '$core', $n, Default, '$fname')";
$query = mysqli_query($connection, $sql);
if (!$query){
	$response = array("success" => "false", "error" => "true", "user"=>$user, "core"=>$coreURL, "timestamp"=>$n, message => "Database error: " . mysqli_error($connection));
	$R = json_encode($response);
	echo $R;
}else{
	$response = array("success" => "true", "error" => "false", "user"=>$user, "core"=>$coreURL, "timestamp"=>$n);
	$R = json_encode($response);
	echo $R;
}
?>