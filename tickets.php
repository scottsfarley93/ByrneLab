<?php
session_start();
if ($_SESSION['loggedIn'] == "TRUE"){
	$user = $_SESSION['user'];
}else{
	header("Location: default.html");
}
require_once("database_access.php");
$sql = "SELECT * FROM `Bugs`";
$response = mysqli_query($connection, $sql);
$numBugs = mysqli_num_rows($response);
$sql = "SELECT * FROM `Requests`";
$response = mysqli_query($connection, $sql);
$numRequests = mysqli_num_rows($response);
$sql = "SELECT * FROM `Bugs` WHERE `Resolved`= TRUE";
$response = mysqli_query($connection, $sql);
$numResolvedBugs = mysqli_num_rows($response);
$numOpenBugs = $numBugs - $numResolvedBugs;
$sql = "SELECT * FROM `Requests` WHERE `Resolved`= TRUE";
$response = mysqli_query($connection, $sql);
$numResolvedFeatures =mysqli_num_rows($response);
$numOpenFeatures = $numRequests - $numResolvedFeatures;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  	<style>
  	#draganddrop{
  		height: 100px;

  	}
  	.row{
  		margin-bottom:3%;
  	}
  	#browse{
  		height:100px;
  	}
  	.nonhover{
  		background-color: #6AAF6A;
  		border-style: dashed;
    	border-width: 5px;
    	border-color: black;
  	}
  	.hover{
  		border-style: solid;
    	border-width: 5px;
    	border-color:red;
  	}
  	input{
  		float:right;
  	}
  	#coreDropdown{
  		float:right;
  	}
  	.left-input{
  		float:left;
  	}
  	#header{
  		margin-top: 5%;
  	}
  	#header .btn{
  		margin-top: 15%;
  	}
	.right{
		float:right;
	}
	.left{
		float:left;
	}
  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Center</title>
	
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
          	<li><a href='tickets.php'>Ticket Center</a></li>
            <li><a href="scripts/logout.php">Logout</a></li>
          </ul>
      </div>
    </nav>


    <div class="container">
      	<div class='row'>
      	<div class='page-header'>
      		<div class='row' id='header'>
      			<span class='left'><h2>Welcome to the Ticket Center</h2></span>
      		<span class='right'>
      		<button class='btn-danger btn' data-target='#ticketModal' data-toggle='modal'>Take a Number</button>
      	</span>
      		</div>
      		
      	</div>
      	<hr />
      	<div class='col-sm-6'>
	      	<h4>Number of Open Bug Report Tickets: </h4><strong class='text-muted'><?php echo $numOpenBugs?></strong>
	      	<h4>Number of Resolved Bug Report Tickets: </h4><strong class='text-muted'><?php echo $numResolvedBugs?></strong>
	      	<h4>Number of Open Feature Request Tickets: </h4><strong class='text-muted'><?php echo $numOpenFeatures?></strong>
	      	<h4>Number of Resolved Feature Request Tickets: </h4><strong class='text-muted'><?php echo $numResolvedFeatures?></strong>
      	</div>
      	<div class='col-sm-6'>
      		<h2>Now Serving:</h2>
      		<strong>Bug Ticket: #<?php echo $numBugs?></strong><br />
      		<strong>Request Ticket: #<?php echo $numRequests?></strong>
      	</div>

      	</div>

    <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Submit a Ticket</h4>
	        
	      </div>
	      <div class="modal-body" id='modal-body'>
	      	<label for='type'>Ticket Type</label><br /><select name='type' id='typeSelect'>
	      		<option value='bug'>Bug</option>
	      		<option value='feature'>Feature Request</option>
	      	</select><br />
	      	<label for='desc'>Description:</label><br />
	      	<textarea id='featureDesc' name='desc' rows="15" cols="75"></textarea>
	      	
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-default nav-left" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" id='submitTicket'>Submit</button>
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
    $("#submitTicket").click(function(){
    	$.ajax({
    		url: "/scripts/submitTicket.php",
    		data: {
    			type: $("#typeSelect").val(),
    			desc: $("#featureDesc").val()
    		},
    		type: "POST",
    		error: function(){
    			alert("Error in submiting ticket request to server.  Please try again later.");
    		},
    		success: function(response){
    			console.log(response);
    			if ($.isNumeric(response)){
    				alert("You have submitted ticket #" + response + ".  Please use this ticket number in any inquires to the dev team.")
    				window.location.href = "tickets.php"
    			}else{
    				alert("There appears to be an error in the ticket submission process.  Code: \n" + response);
    			}
    		}
    	})
    })
    </script>
  </body>
</html>
