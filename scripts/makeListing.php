<?php
require_once("../database_access.php");
if(isset($_POST['cpnFile'])){
	$cpn = $_POST['cpnFile'];
}else{
	die("No file given.");
}
$baseFolder = "../savedProjects/";
$cpnName = $baseFolder . $cpn;
$str = file_get_contents($cpnName);
$json = json_decode($str, true);
$taxa = $json['taxa'];
echo "<table>";
for ($i=0; $i<count($taxa); $i ++){
	$t = $taxa[$i];
	$vals = $t['valuesMatrix'];
	for ($w =0; $w<count($vals); $w++){
		$level = $vals[$w];
		$depth = floatval($level['depth']);
		$value = floatval($level['value']);
		$s = "<tr>";
		$s .= "<td>" . $depth . "</td>";
		$s .= "<td>" . $value . "</td>";
		echo $s;
		echo "</tr>";
	}
}
echo "</table>"

?>