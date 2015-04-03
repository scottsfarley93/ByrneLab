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
		.right{
			float:right;
		}
		.viewDiagram{
			margin-right: 2% !important;
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
    	<h1 class='page-header'>Saved Diagrams</h1>
    	<strong>Filter by Core: <select id='projectDropdown'><option value='all'>No Filter</option></select></strong>
    	<div class='row'>
			<ul id='savedProjectsList'>
				
			</ul>
    	</div>
    	
		<div class='modal fade' id='details-modal' tabindex="-1" role='dialog' aria-hide='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class='modal-title'>Project Details</h4>
					<div class='modal-body'>
						<div id='indicator'></div>
						<ul class='list-group' id='propsList'>
							<li class='list-group-item'> <strong class='text-muted'>Project Title: </strong><span id='projectTitle'></span></li>
							<li class='list-group-item'><strong class='text-muted'>Last Drawn: </strong> <span id='lastDrawn'></span></li>
							<li class='list-group-item'><strong class='text-muted'>Created: </strong> <span id='createdAt'></span></li>
							<li class='list-group-item'> <strong class='text-muted'>Number of Taxa: </strong> <span id='numTaxa'></span></li>
							<li class='list-group-item'> <strong class='text-muted'>Shows Stratigraphy Column: </strong><span id='stratCol'></span></li>
							<li class='list-group-item'> <strong class='text-muted'>Plot Dimensions: </strong><span id='plotDims'></span></li>
							<li class='list-group-item' id='taxa'>Taxa: 
								<ul class='list-group' id='taxa-list'>
									
								</ul>
							</li>
						</ul>
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
	function loadProjects(filter){
		    	$.ajax({
    		url: "scripts/fetchSavedProjects.php",
    		contentType: "json",
    		error: function(){
    			console.log("Ajax Error in fetching saved projects");
    		},
    		type:"GET",
    		success: function(response){
    			var response = JSON.parse(response);
    			//erase the existing modal

    			for (var i=0; i<response.length; i++){
    				proj = response[i];
    				s = "<li class='list-group-item'><button class='glyphicon glyphicon-eye-open viewDiagramDetails btn-primary' data-toggle='modal' data-target='#details-modal' data-project='" + proj['ProjectIndex'] + "'></button>"
    				s += "Diagram for Core: " + proj['Core'];
    				s += "<span class='right'>" + proj['LastUpdated'] + "</span></li>"
    				$("#savedProjectsList").append(s);
    			}
    			
    			  $(".viewDiagramDetails").click(function(){
		    		var index = $(this).data('project');
		    		
		    		var project;
		    		for (var i=0; i<response.length; i++){
		    			var item = response[i];
		    			if (item['ProjectIndex'] == index){
		    				project = item;
		    				break
		    			}
		    		}
		    		$("#viewDiagram").click(function(){
		    			var user = project['User'];
		    			var core = project['Core'];
		    			core = core.split(" ").join("+")
		    			var creation = project['creationTimestamp']
		    			var url = "drawDiagram.php?user=" + user + "&core=" + core + "&creationTime=" + creation;
		    			document.location.href = url;
		    		})
		    		var file = project['FileReference'];
		    		//load the cpn file and give the user some background on the diagram
		    		$.ajax({
		    			url: file,
		    			contentType: "json",
		    			success: function(response){
		    				$("#indicator").html("")
		    				var config = JSON.parse(response)
		    				console.log(config)
		    				var numTaxa = config['taxa'].length;
		    				var title = config['title'];
		    				var width = config['plotWidth'];
		    				var height = config['plotHeight'];
		    				var showStratigraphy = config['stratigraphy']['doStratigraphy'];
		    				var createdAt = config['createdAt'];
		    				var lastDrawn = config['lastDrawn'];
		    				$("#taxa-list").empty();
		    				for (var x =0; x<numTaxa; x++){
		    					var t = config['taxa'][x]['name'];
		    					if (t == undefined){
		    						t == config['taxa'][x]['topLabel'];
		    					}
		    					l = "<li class='list-group-item'>" + t + "</li>"
		    					$("#taxa-list").append(l);
		    				}
		    				$("#projectTitle").text(title);
		    				$("#lastDrawn").text(lastDrawn)
		    				$("#createdAt").text(createdAt)
		    				$("#numTaxa").text(numTaxa)
		    				if (showStratigraphy == true || showStratigraphy == "true"){
		    					showStratigraphy = "Yes"
		    				}else{
		    					showStratigraphy = "No"
		    				}
		    				$("#stratCol").text(showStratigraphy);
		    				$("#plotDims").text(width + " x " + height);
		    				
		    				
		    			},
		    			error: function(){
		    				$("#indicator").html("")
		    				console.log("Error");
		    			},
		    			beforeSend: function(){
		    				$("#indicator").html("Loading diagram file...")
		    			}
		    		})
		    		
		    	})
    		},
    		///this is part of the original ajax call
    		data:{
    			core: filter
    		},
    		beforeSend: function(){
    		}
    	})
	}
	
	$(document).ready(function(){
		loadProjects("all");
		   $.ajax({
    			url:"scripts/populateCores.php",
    			type:'post',
    			success:function(response){
    				response = JSON.parse(response);
    				for (i in response){
    					$("#projectDropdown").append("<option value='" + response[i] + "' >" + response[i] + "</option>");
    				}
    			}
    		})
	})
	$("#projectDropdown").change(function(){
		$("#savedProjectsList").empty();
		var val = $(this).val();
		console.log(val);
		loadProjects(val);
	})
	

    </script>
     
  </body>
</html>
