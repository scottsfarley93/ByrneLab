<?php
session_start();
require_once("../database_access.php");

if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}else{
	$user = "";
}
if(isset($_GET['files'])){
	$files = $_GET['files'];
}else{
	$files = array();
}

if(isset($_GET['core'])){
	$core = $_GET['core'];
}else{
	$core = "";
}
$numFiles = count($files);
$Result = array();
$totalMinDepth = 999999999999;
$totalMaxDepth = -999999999999;
$fileLookup = array();
for ($i=0; $i<$numFiles; $i++){
	$f = $files[$i];
	$sql = "SELECT * FROM `Datafiles` WHERE User='$user' AND CoreID='$core' AND DatafileName='$f'";
	$result = mysqli_query($connection, $sql);
	if(!$result){
		$Response['Success'] = "False";
		$Respose["Error"] = "True";
		$Response['ErrorMessage'] = mysli_error($connection);
		echo json_encode($Response);
	}else{
		$row = mysqli_fetch_assoc($result);
		$name = $row['DatafileName'];
		$minDepth = intval($row['MinDepth']);
		$maxDepth = intval($row['MaxDepth']);
		$fileReference = $row['FileReference'];
		//calculate total min and max depth
		if ($maxDepth > $totalMaxDepth){
			$totalMaxDepth= $maxDepth;
		}
		if($minDepth < $totalMinDepth){
			$totalMinDepth = $minDepth;
		}
		$a = array("Name"=> $f, "File"=>$fileReference);
		array_push($fileLookup, $a);
	}
}
$Result['fileLookup'] = $fileLookup;
$Result['MinDepth'] = $totalMinDepth;
$Result['MaxDepth'] = $totalMaxDepth;
$Result['Success'] = "True";
$Result['Error'] = "False";
$Result['Method'] = "GET";
$Result['ScriptVersion'] = "1.0.0";
echo json_encode($Result);

?>
