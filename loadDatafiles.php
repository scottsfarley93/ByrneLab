<?php
session_start();
require_once("database_access.php");
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = 'scottsfarley';
}
$sql = "SELECT * FROM `Datafiles` WHERE `User` = '$username' ORDER BY SiteName DESC ";
$result = mysqli_query($connection, $sql);
$files = array();
$sites = array();
$siteFiles = array();
$currentSite = null;
$num = 0;
while($row = mysqli_fetch_assoc($result)){
	$siteName = $row['SiteName'];
	$fileName = $row['Nickname'];
	if ($siteName == ""){
		$siteName = "Not specified";
	}
	if ($siteName != $currentSite){
		//do site things
		array_push($sites, $siteName);
		if ($num != 0){
			array_push($files, $siteFiles);
		}
		$currentSite = $siteName;
		
		//do file name things
		
		$siteFiles = array();
		if ($fileName != "") {
			array_push($siteFiles, $fileName);
		}
		
	}else{
if ($fileName != "") {
			array_push($siteFiles, $fileName);
		}
	}
	$num=$num + 1;
}
//catch the end case
array_push($files, $siteFiles);

$i = 0;
echo "<ul>";
foreach($sites as $site){
	echo "<li>" . $site . "</li>";
	$theseFiles = $files[$i];
	if (count($theseFiles != 0)){
		echo "<ul>";
		foreach($theseFiles as $f){
			echo "<li>" . $f . "</li>";
		}
		echo "</ul>";
	}else{
		echo "<ul><li>No files stored for this site</li></ul>";
	}
	$i = $i + 1;

}
	echo "</ul>";

?>