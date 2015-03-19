<?php
require_once("database_access.php");
session_start();
if (isset($_SESSION['user'])){
	$sessionUser = $_SESSION['user'];
}else{
	$sessionUser = 'scottsfarley';
}
if (isset($_GET['user'])){
	$user = $_GET['user'];
	//check to make sure that the user who created this diagram is the one trying to diagram it.  Not that secure but it is a start.
	//TODO: shared projects or public projects
	if ($user != $sessionUser){
		header("Location: createPlot.php");
		die();
	}
}else{
	$user = 'None';
}

if(isset($_GET['core'])){
	$core=$_GET['core'];
}else{
	$core='None';
}

if(isset($_GET['creationTime'])){
	$t = $_GET['creationTime'];
}else{
	$t = "None";
}

//if the necessary parameters are not set redirect the users to the diagram creation page --> we dont want users opening this page unless there is a predefined conf file

if ($t == "None" or $core == "None" or $user == "None"){
	header("Location: createPlot.php");
	die();
}
?>
<html>
	<head>
		<title>
			Pollen Diagram
		</title>
	</head>
	<body>
		<h1>We are cooking with gas, now!</h1>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script>
	//lookup the configuration file for this diagram
		function getQueryVariable(variable) {
		    var query = window.location.search.substring(1);
		    var vars = query.split('&');
		    for (var i = 0; i < vars.length; i++) {
		        var pair = vars[i].split('=');
		        if (decodeURIComponent(pair[0]) == variable) {
		            return decodeURIComponent(pair[1]);
		        }
		    }
		    console.log('Query variable %s not found', variable);
		}
		coreName = getQueryVariable("core");
		userName = getQueryVariable("user");
		timestamp = getQueryVariable("creationTime");
		fName = "savedProjects/" + userName + '_' + coreName + "_" + timestamp + ".cpn";
		console.log(fName);
		$.ajax({
			url: fName,
			dataType: "application/json",
			contentType: "application/json",
			error: function(error){
				alert(error);
			},
			success: function(response){
				alert(response);
			}
		})
		
	</script>
</html>