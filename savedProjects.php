<?php
session_start();
//check credentials and get session user
session_start();
if ($_SESSION['loggedIn'] == "TRUE"){
	$user = $_SESSION['user'];
	$sessionUser = $_SESSION['user'];
}else{
	header("Location: default.html");
}
?>
<!DOCTYPE html>
<html lang="en">
	<style>
		#savedProjectsList{
			margin-top: 250px;
			padding: 100px;
		}
	</style>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saved Projects</title>
	
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    


  </head>

  <body>

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
            <li><a href="savedProjects.php">Saved Projects</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="scripts/logout.php">Logout</a></li>
          </ul>
      </div>
    </nav>


    <div class="container">
    	<div class='row'>
    	<div id='savedProjectsList'>
    		<select id='projectsDropdown'>
    			<option val='0'>--Select--</option>
    		</select>
    	</div>
    	</div>
    	
		<div class='modal fade' id='details-modal' tabindex="-1" role='dialog' aria-hide='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class='modal-title'>Project Details</h4>
					<div class='modal-body'>
						Reserved for later use.
					</div>
					<div class='modal-footer'>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id='viewDiagram' >View Diagram</button>
					</div>
				</div>
			</div>
		</div>
	</div>
    </div> <!-- /container -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li>
                            <a href="default.html">Home</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                       <a href="#">Reference Document</a>
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
    <script>
    	$.ajax({
    		url: "scripts/fetchSavedProjects.php",
    		contentType: "json",
    		error: function(){
    			console.log("Ajax Error in fetching saved projects");
    		},
    		success: function(response){
    			$("#savedProjectsList").empty();
    			response = JSON.parse(response);
    			numResults = response.length;
    			var currentCore = "";
    			var start = true;
    			appendString = "";
    		}
    	})
    </script>
     
  </body>
</html>
