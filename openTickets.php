<?php
session_start();
if ($_SESSION['loggedIn'] == "TRUE"){
	$user = $_SESSION['user'];
}else{
	header("Location: default.html");
}
require_once("database_access.php");
$sql = "SELECT * FROM `Bugs` WHERE `Resolved` = False ORDER BY `Votes` DESC ";
$bugResponse = mysqli_query($connection, $sql);
if(!$bugResponse){
	die("SQL Error: " . mysqli_error($connection));
}
$sql = "SELECT * FROM `Requests` WHERE `Resolved` = False ORDER BY `Votes` DESC ";
$featureResponse = mysqli_query($connection, $sql);
if(!$featureResponse){
	die("SQL Error: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  	<style>
  	#draganddrop{
  		height: 100px;

  	}
  	.glyphicon-check{
  		color:green;
  	}
  	.up{
  		background-color:green;
  	}
  	.down{
  		background-color:red;
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
      			<span class='left'><h2>Open Tickets</h2></span>
      			<span class='right'><button class='btn-danger btn' id='backButton'>Ticket Central</button></span>
      			
      		</div>
      	</div>
      	<hr />
      	<div class='col-sm-6'>
      		<h4>Bug Reports</h4>
      		<?php
      		while($row = mysqli_fetch_assoc($bugResponse)){
      				$str = "<span>" . $row['Description'] ;
					$str .=  "<button class='right resolve' data-num='" . $row['RecordID'] . "' data-type='bug'><span class='glyphicon glyphicon-check'></span></button>";
      				$str .= " <button class='right vote' data-num='". $row['RecordID'] . "' data-type='bug'>";
      				$str .= "<span class='glyphicon glyphicon-thumbs-up'></span></button>";
      				$str .= "</span>";
					$str .= "<span class='right' id='bugs_" . $row['RecordID']. "' data-num='". $row['Votes']."'>" . $row['Votes'] . "</span>";
					echo $str;
					echo "<hr />";
      			}
			?>
      	</div>
      	<div class='col-sm-6'>
      		<h4>Feature Requests</h4>
      		<?php
      			while($row = mysqli_fetch_assoc($featureResponse)){
      				$str = "<span>" . $row['Description'] ;
					$str .=  "<button class='right resolve' data-num='" . $row['RecordID'] . "' data-type='feature'><span class='glyphicon glyphicon-check'></span></button>";
      				$str .= " <button class='right vote' data-num='". $row['RecordID'] . "' data-type='feature'><span class='glyphicon glyphicon-thumbs-up'></span></button></span>";
					$str .= "<span class='right' id='features_" . $row['RecordID']. "' data-num='". $row['Votes']."'>" . $row['Votes'] . "</span>";
					echo $str;
					echo "<hr />";
      			}
      		?>
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
                       <a href="wiki/index.php/Main_Page">Reference Document</a>
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
    	$(".vote").click(function(){
    		var ticNum = $(this).data('num')
    		var type = $(this).data('type')
    		$.ajax({
    			url: "scripts/voteTicket.php",
    			type:"POST",
    			data:{
    				ticNum:ticNum,
    				oper: "add",
    				type: type
    			},
    			success: function(response){
    				if (response == "Success"){
    					alert("Thanks for your feedback!");
    					location.reload()
    				}else{
    					alert("Unexpected error.  Check console.");
    					console.log(response)
    				}
    				
    			},
    			beforeSend: function(){
    				console.log("Sending ajax vote");
    			},
    			error: function(){
    				alert("Network error!")
    			}
    		})
    	})
    	$("#backButton").click(function(){
    		location.href = "tickets.php"
    	})
    	$(".resolve").click(function(){
    		a = confirm("Are you sure you want to mark this ticket as resolved?")
    		type = $(this).data('type');
    		ticNum = $(this).data('num');
    		if (a){
    			$.ajax({
    				url:"scripts/resolveTicket.php",
    				type:"POST",
    				data:{
    					ticNum:ticNum,
    					type:type
    				},
    				success: function(response){
    					if(response == "Success"){
    						alert("Thanks for resolving this issue!");
    						location.reload()
    					}else{
    						alert("There was unexpected error.  Please check the console.");
    						console.log(response);
    					}
    				},
    				error: function(e){
    					console.log(e)
    					alert("Network error!")
    				},
    				beforeSend:function(){
    					console.log("Sending ajax resolution for type " + type)
    				}
    			})
    		}
    	})
    </script>
  </body>
</html>
