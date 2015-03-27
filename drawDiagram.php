<?php
require_once("database_access.php");
session_start();
//check credentials and get session user
session_start();
if ($_SESSION['loggedIn'] == "TRUE"){
	$user = $_SESSION['user'];
	$sessionUser = $_SESSION['user'];
}else{
	header("Location: default.html");
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
<?php
if (isset($GET['core'])){
	$core = $GET['core'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<style>
  	#plot{
  		padding: 10%;
  	}
  	#plot{
  		margin: 10%;
  	}
  	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}
	.axis {
   font: 10px sans-serif;
 }
	  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Plot</title>
  <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    
    

  </head>

  <body>

    <!-- Fixed navbar -->
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand">Calpalyn II</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Datafiles <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="addNewCore.php">Add new core</a></li>
                <li><a href="addNewDatafile.php">Add new datafile</a></li>
                <li><a href="addNewChronology.php">Add new chronology</a></li>
                <li class='divider'></li>
                <li><a href="manageData.php">Manage existing files</a></li>
              </ul>
            </li>
            <li><a href="createPlot.php">Create Plot</a></li>
            <li><a href="savedProjects.html">Saved Projects</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="cgi-bin/logout.php">Logout</a></li>
          </ul>
      </div>
    </nav>
    <div class="container ">
    	<div id='plot'>
    		
    	</div>
    </div> <!-- /container -->
<footer>
        <div class="container" >

            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li>
                            <a href="#">Home</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="plot.html">Plot</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                       <a href="#">About</a>
                        </li>
                        
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="http://geography.berkeley.edu">Geography at Berkeley</a>
                        </li>
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; Scott Farley 2015.</p>
                    <p class='copyright text-muted small'>All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="js/drawDiagram.js"></script>
    
  </body>
</html>
