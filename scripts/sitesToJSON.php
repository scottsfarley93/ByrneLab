<?php
require_once("../database_access.php");
session_start();
if(isset($_SESSION['user'])){
	$username = $_SESSION['user'];
}else{
	$username = "";
}
$sql = "SELECT * FROM Cores WHERE User='$username' AND Latitude > -9999 AND Longitude > -9999";
$result = mysqli_query($connection, $sql);
$geojson = array('type'=>'FeatureCollection', 'features'=>array());
while($row=mysqli_fetch_assoc($result)){
	$minAge = $row['MinAge'];
	
	$maxAge = $row['MaxAge'];
	if($minAge == -9999){
		$minAge = 'None';
	}
	if($maxAge == -9999){
		$maxAge = "None";
	}
	$coreName = $row['CoreName'];
	$sql = "SELECT * FROM `Datafiles` WHERE User='$username' AND CoreID='$coreName' ";
	$result1 = mysqli_query($connection, $sql);
	$numDatafiles = mysqli_num_rows($result1);
	$sql = "SELECT Version FROM `ChronologyFiles` WHERE User='$username' AND CoreID='$coreName'";
	$result2 = mysqli_query($connection, $sql);
	$chron = mysqli_num_rows($result2);
	if($chron == 0){
		$chron = "No";
	}else{
		$chron = "Yes";
	}
	$cored = $row['DateCored'];
	$cored = strtotime($cored);
	$cored = date("F j, Y", $cored);
	$marker = array(
	'type'=>'Feature',
		'geometry'=>array(
			'type'=>'Point',
			'coordinates'=>array(
				floatval($row['Longitude']),
				floatval($row['Latitude'])
			)
		),
		'properties'=> array(
		'title' =>$row['CoreName'],
		'marker-color'=>'#f00',
		'marker-size'=>'small',
		'Site'=>$row['SiteName'],
		'Core Name'=>$row['CoreName'],
		'Latitude'=>floatval($row['Latitude']),
		'Longitude'=>floatval($row['Longitude']),
		'Min Age:'=>$minAge,
		'MaxAge'=>$maxAge,
		'Cored'=>$cored,
		'Datafiles'=>$numDatafiles,
		'Chronology File'=>$chron,
		)
	);
	array_push($geojson['features'], $marker);
}
echo json_encode($geojson);


?>