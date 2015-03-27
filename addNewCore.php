<?php
	session_start();
	if ($_SESSION['loggedIn'] == "TRUE"){
		$user = $_SESSION['user'];
	}else{
		header("Location: default.html");
	}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
  	<style>
  	#draganddrop{
  		height: 100px;

  	}
  	#browse{
  		height:100px;
  	}
  	.nonhover{
  		background-color: #007A29;
  		border-style: dashed;
    	border-width: 5px;
    	border-color: black;
  	}
  	.hover{
  		border-style: solid;
    	border-width: 5px;
    	border-color:red;
  	}
  	form input{
  		float:right;
  	}

  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Core</title>
	
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

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
      	<h2 class="page-header featurette-heading">Add a new core</h2>
      	<div class='row'>
      		<p>Core Properties</p>
      		<form method='post' action='scripts/processNewFile.php' enctype="multipart/form-data">
		      			<ul class='list-group'>
		      			<li class='list-group-item'><label for='fileName'>Core Name: </label><input type='text' name='coreName' id='coreName' size='35' placeholder="e.g. ODP Hole 735B"/></li>
		      			<li class='list-group-item'><label for='fileName'>Site Name: </label><input type='text' name='siteName' id='siteName' size='35' placeholder="e.g. Southwest Indian Ridge"/></li>
		      			<li class='list-group-item'><label for='fileName'>Minimum Age: </label><input type='number' name='minAge' id='minAge' size='35'/></li>
		      			<li class='list-group-item'><label for='fileName'>Maximum Age: </label><input type='number' name='maxAge' id='maxAge' size='35'/></li>
		      			<li class='list-group-item'><label for='fileName'>Minimum Depth: </label><input type='number' name='minDepth' id='minDepth' size='35'/></li>
		      			<li class='list-group-item'><label for='fileName'>Maximum Depth: </label><input type='number' name='maxDepth' id='maxDepth' size='35'/></li>
		      			<li class='list-group-item'><label for='lat'>Latitude: </label><input type='text' id='lat' name='lat' size='35'/></li>
		      			<li class='list-group-item'><label for='lon'>Longitude: </label><input type='text' id='lon' name='lon' size='35'/></li>
		      			<li class='list-group-item'><label for='fileName'>Water Depth: </label><input type='number' name='waterDepth' id='waterDepth' size='35'/></li>
		      			<li class='list-group-item'><label for='fileName'>Date Cored: </label><input type='date' name='dateCored' id='dateCored' size='35'/></li>
		      			<li class='list-group-item'><label for='protect'>Password Protect?: 
		      				<span data-toggle="tooltip" data-placement="right" title="Password protect this file with a separate password so that only you can use it.">
		      					<i class='glyphicon glyphicon-question-sign'></i></span></label><input type='checkbox' name='protect' id='protect'/></li>
		      			<li class='list-group-item'><label for='pass'>Password: </label><input type='password' name='pass' id='pass' size='35'/></li>
		      			<li class='list-group-item'><label for='form-submit'>Create</label><input type='submit' value='Create Core' id='form-submit' name='form-submit'/></li>
		      		</ul>
		      		</form>
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
    	$("#form-submit").on('click', function(e){
    		var coreName = $("#coreName").val();
    		var siteName = $("#siteName").val();
    		var minAge = $("#minAge").val();
    		var maxAge = $("#maxAge").val();
    		var minDepth = $("#minDepth").val();
    		var maxDepth = $("#maxDepth").val();
    		var lat = $("#lat").val();
    		var lon = $("#lon").val();
    		var waterDepth = $("#waterDepth").val();
    		var dateCored = $("#dateCored").val();
    		var protect = $("#protect").prop('checked');
    		var pass = $("#pass").val();
    		e.preventDefault();
    		    		$.ajax({
    			url: 'scripts/processNewCore.php', 
    			type:"POST",
    			data:{
    				coreName:coreName,
    				siteName:siteName,
    				minAge:minAge,
    				maxAge:maxAge,
    				minDepth:minDepth,
    				maxDepth:maxDepth,
    				lat:lat,
    				lon:lon,
    				waterDepth:waterDepth,
    				dateCored:dateCored,
    				protect:protect,
    				pass:pass
    			},
    			success:function(response){
    				if(response == "Success"){
    					alert("Core added.");
    					window.location.href = "manageData.php";
    				}
    			}
    		})

    	})
    </script>
  </body>
</html>
