<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = '';
}
$sql = "SELECT * FROM `Cores` WHERE User = '$username'";
$result = mysqli_query($connection, $sql);
if(!$result){
	die("Couldn't select from database. Error: " . mysqli_error($connection));
}

$response = array();
while($row = mysqli_fetch_assoc($result)){
	$core = array("Core"=>array(), "Datafiles"=>array(), "Chronology"=>array());
	//get variables from the core table
	$coreName = $row['CoreName'];
	$siteName = $row['SiteName'];
	$MinAge = $row['MinAge'];
	$MaxAge = $row['MaxAge'];
	$MinDepth =$row['MinDepth'];
	$MaxDepth = $row['MaxDepth'];
	$Latitude = $row['Latitude'];
	$Longitude = $row['Longitude'];
	$WaterDepth = $row['WaterDepth'];
	$DateCored = $row['DateCored'];
	$core['Core'] = array(
		"CoreName"=>$coreName,
		"siteName"=>$siteName,
		"MinAge"=>$MinAge,
		"MaxAge"=>$MaxAge,
		'MinDepth'=>$MinDepth,
		"MaxDepth"=>$MaxDepth,
		"Latitude"=>$Latitude,
		"Longitude"=>$Longitude,
		"WaterDepth"=>$WaterDepth,
		"Date Cored"=>$DateCored
	);
	//get details on each datafile
	$sql1 = "SELECT * FROM `Datafiles` WHERE CoreID = '$coreName' AND User='$username'";
	$result1 = mysqli_query($connection, $sql1);
	$datafiles = array();
	while ($row1 = mysqli_fetch_assoc($result1)){
		$datafileName = $row1['DatafileName'];
		$NumLevels = $row1['NumLevels'];
		$NumTaxa = $row1['NumTaxa'];
		$MinDepth = $row1['MinDepth'];
		$MaxDepth = $row1['MaxDepth'];
		$lastModified = $row1['LastModified'];
		$uploaded = $row1['Uploaded'];
		$version = $row1['Version'];
		$datafileDetails = array(
				"Name"=>$datafileName,
				"NumLevels"=>$NumLevels,
				"NumTaxa"=>$NumTaxa,
				"MinDepth"=>$MinDepth,
				"MaxDepth"=>$MaxDepth,
				"FileLastModified"=>$lastModified,
				"FileUploaded"=>$uploaded,
				"FileVersion"=>$version
		);
		
		array_push($datafiles, $datafileDetails);
		
	}
	array_push($core['Datafiles'], $datafiles);
	//get chronology file details
	$sql2 = "SELECT * FROM `ChronologyFiles` WHERE CoreID = '$coreName' AND User='$username'";
	$result2 = mysqli_query($connection, $sql2);
	$chronology = array();
	while($row2 = mysqli_fetch_assoc($result2)){
		$numChronLevels = $row2['NumLevels'];
		$minChronDepth = $row2['MinDepth'];
		$maxChronDepth = $row2['MaxDepth'];
		$MinAge = $row2['MinAge'];
		$MaxAge = $row2['MaxAge'];
		$chronModified = $row2['LastModified'];
		$chronUploaded = $row2['Uploaded'];
		$chronVersion = $row2['Version'];
		$core['Chronology'] = array(
			"ChronLevels"=>$numChronLevels,
			"minChronDepth"=>$minChronDepth,
			"maxChronDepth"=>$maxChronDepth,
			"minChronAge"=>$MinAge,
			"maxChronAge"=>$MaxAge,
			"chronModified"=>$chronModified,
			"chronUploaded"=>$chronUploaded,
			"chronVersion"=>$chronVersion,
		);
	}
	array_push($response, $core);
}
echo json_encode($response);

?>