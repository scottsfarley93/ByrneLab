<?php
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<style>
  	.nav-left{
  		float:left;
  	}
  	.btn-right{
  		float: right;
  		cursor: pointer;
  		margin-right: 1%;
  		margin-top: 0px;
  		margin-bottom:10px!important;
  	}
	.list-group{
		padding-top: 2%;
		
	}
	.table-responsive{
		width:100%
	}
	.list-group-item{
		min-height: 50px;
		padding-bottom: 10px;
	}
	.list-main{
		margin-bottom:5% !important;
	}
	#addLink{
		color:white;
		font-size:large
	}
	.editFormat{
		border-color:black thin;
		border-radius: 5;
		border-style: inset;
	}
	#DandD{
		height:100;
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
  	</style>
  	<style>
      @import url(Cesium/Widgets/widgets.css);
      
      #cesiumContainer {
        height: 100%;
        width: 100%;
        font-family: sans-serif;
        box-shadow: 10px 10px 5px #727372;
      }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Datafiles</title>
   
    <script src="Cesium/Cesium.js"></script>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	
	<!-- Optional theme -->
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
                <li><a href="manageData.php" >Manage existing files</a></li>
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
		<p>Your Cores</p>
      	<div class='row' id='mapContainer'>
	      	<div id='cesiumContainer'>
	      	</div>
	      	<div id='toolbar'></div>

      	</div>
      	<div class='row'><p>List</p></div>
      	<div class='row' id='fileslist'>
      		<button type='button' id='addButton' class='btn btn-primary btn-large'><a id='addLink' href='addNewCore.php'>Add Data</a></button>
      		
      	</div>
      </div>

    </div> <!-- /container -->
	<footer>
        <div class="container">
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"></h4>
	      </div>
	      <div class="modal-body" id='modal-body'>
	      	
	      	
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-default nav-left" data-dismiss="modal">Close</button>
	      	<button type='button' class='btn btn-warning' id='updateButton'>Update File</button>
	      	<button type='button' class='btn btn-danger' id='removeButton'>Remove</button> 
	        <button type="button" class="btn btn-primary" id='submitUpdates'>Save </button>
	      </div>
	    </div>
	  </div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script>
    $('#myModal').modal('show')
    	$(".clickable").on('click', function(){
    		$('body')
    	})
    </script>
          	<script>
      	//this script controls the cesium map
		    var viewer = new Cesium.Viewer('cesiumContainer', {timeline : false,
		    	sceneMode : Cesium.SceneMode.COLUMBUS_VIEW,
		    	terrainProvider : new Cesium.CesiumTerrainProvider({
			        url : '//cesiumjs.org/smallterrain',
			        credit : 'Terrain data courtesy Analytical Graphics, Inc.'
			    }),
			    baseLayerPicker : false,
			    mapProjection : new Cesium.WebMercatorProjection()
			 });

		    viewer.extend(Cesium.viewerEntityMixin);
			viewer.dataSources.add(Cesium.GeoJsonDataSource.fromUrl('scripts/sitesToJSON.php'));

	</script>
    <script>
    var coreDataResponse;
    	$(document).ready(function(){
    		///populates core list below the map 
    		$.ajax({
    			url:"scripts/dataToJSON.php",
    			type:"POST",
    			success:function(response){
    				response =JSON.parse(response);
    				coreDataResponse = response;
    				var theList = $("#fileslist");
    				theList.append("<ul class='list-group'>")
    				for (i in response){
    					var core = response[i]['Core'];
    					var datafiles = response[i]['Datafiles'][0];
    					var chronology = response[i]['Chronology']
    					var coreName = core["CoreName"];
    					var siteName = core['siteName'];
    					var minAge = core['MinAge'];
    					var maxAge = core['MaxAge'];
    					var minDepth = core['minDepth'];
    					var maxDepth = core['MaxDepth'];
    					var latitude = core['latitude'];
    					var longitude = core['Longitude'];
    					var waterDepth = core['WaterDepth'];
    					var dateCored = core['Date Cored'];
    					var listString = "";
    					//build a string of the elements needed in the list
    					if (coreName != ""){
    						listString+= "<li class='list-group-item list-main'><div class='page-header'><h4>" + coreName + "</h4><h5 class='text-muted'>" + siteName + "</h5></div><button type='button' class='btn btn-right btn-info' data-toggle='modal' data-target='#editModal' data-type='Core' data-number="+ i + "><span class='glyphicon glyphicon-edit'></span></button>"
    						 + "<button type='button' class='btn btn-right btn-success'><span class='glyphicon glyphicon-play'></span></button>" ;
							listString += "<ul class='list-group'><i>Datafiles</i>";
    					if (datafiles.length != 0){	
    						for (x in datafiles){
	    						var file = datafiles[x];
								console.log(file);
								dataName = file['Name'];
								if (dataName != ""){
									listString += "<li class='list-group-item'>" + dataName  + "<button type='button' class='btn btn-right btn-info' data-toggle='modal' data-target='#editModal' data-type='file' data-file=" + x + " data-number=" + i + "><span class='glyphicon glyphicon-edit'></span> </button> </li>";
								}
    						}
    					}else{
    						listString += "<li class='list-group-item'>No Datafiles <button type='button' class='btn-primary btn btn-right addDataFile'><span class='glyphicon glyphicon-paperclip'></span></button></li>" 
    					}
    					
    					listString += "</ul>";
    				
    				listString += "<ul class='list-group'><i>Chronology</i> ";
    				if(chronology['minChronAge']!=undefined){
    					var chron = chronology;
    					var minAge = chron['minChronAge'];
    					var maxAge = chron['maxChronAge'];

    					var chronVersion  = chron['chronVersion']
    					listString += "<li class='list-group-item'>Version: " + chronVersion + "<button type='button' class='btn btn-right btn-info' data-toggle='modal' data-target='#editModal' data-type='chrono' data-number=" + i + "><span class='glyphicon glyphicon-edit'></span></button></li>";
    					listString += "<li class='list-group-item'>Start Age: " + minAge + "</li>";
    					listString += "<li class='list-group-item'>End Age: " + maxAge + "</li></ul>";
    				}else{
    					listString += "<li class='list-group-item'>No Chronology <button type='button' class='btn-primary btn btn-right addChronologyFile'><span class='glyphicon glyphicon-paperclip'></span></button></li>"
    				}
    				//add the above components to the DIV
    				theList.append(listString);
    				//link the buttons
    				$(".addChronologyFile").on('click', function(){window.location.href="addNewChronology.php?core=" + coreName});
    				$(".addDataFile").on('click', function(){window.location.href="addNewDatafile.php?core=" + coreName});
    				}
    				}
    				//do modal popup
    				$(".btn-info").on('click', function(){
    					//if info button clicked -->show a modal popup with details 
    					var data_num = $(this).data('number');
    					var data_type = $(this).data('type');
    					var data = coreDataResponse[data_num];
    					var coreData = data['Core'];
 
    					var coreName = coreData['CoreName'];
    					siteName = coreData['siteName'];
    					if(siteName == ""){
    						siteName = "None";
    					}
    					if (data_type == 'Core'){
    						$("#submitUpdates").show();
    					$("#myModalLabel").html("Core Details")
    					//populate the window
    					$("#modal-body").html(
    						"<table class='table-responsive' >" +
    						"<tr><td>Core Name: </td><td id='coreName'>" + coreName + "</td><tr>" +
    						"<tr><td>Site Name: </td><td contentEditable='true' id='siteName' class='editFormat'>" + siteName + "</td><tr>" +
    						"<tr><td>Min Age: </td><td contentEditable='true' id='minAge' class='editFormat'>"+ coreData['MinAge'] + "</td></tr>" + 
    						"<tr><td>Max Age: </td><td contentEditable='true' id='maxAge' class='editFormat'>" + coreData['MaxAge'] + "</td></tr>" + 
    						"<tr><td>Min Depth: </td><td contentEditable='true' id='minDepth' class='editFormat'>" + coreData['MinDepth'] + "</td><tr>" +
    						"<tr><td>Max Depth: </td><td contentEditable='true' id='maxDepth' class='editFormat'>" + coreData['MaxDepth'] + "</td><tr>" +
    						"<tr><td>Latitude: </td><td contentEditable='true' id='latitude' class='editFormat'>" + coreData['Latitude'] + "</td></tr>"+
    						"<tr><td>Longitude: </td><td contentEditable='true' id='longitude' class='editFormat'>" + coreData['Longitude'] + "</td><tr>" + 
    						"<tr><td>Water Depth: </td><td contentEditable='true' id='waterDepth' class='editFormat'>"+coreData['WaterDepth'] +"</td></tr>" +
    						"<tr><td>Core Date: </td><td contentEditable='true' id='dateCored' class='editFormat'>"+coreData['Date Cored']+ "</td></tr>" +
    						"</table>" 
    					)
    					$("#updateButton").hide();
    					//update function for cores
    						$("#submitUpdates").click(function(){
    							
    							$.ajax({
    								url:'scripts/updateCoreMetadata.php',
    								type:"POST",
    								data:{
    									coreName:$("#coreName").text(),
    									siteName:$("#siteName").text(),
    									minAge:$('#minAge').text(),
    									maxAge:$("#maxAge").text(),
    									minDepth:$("#minDepth").text(),
    									maxDepth:$("#maxDepth").text(),
    									lat:$("#latitude").text(),
    									lon:$("#longitude").text(),
    									waterDepth:$("#waterDepth").text(),
    									dateCored:$("#dateCored").text()
    								},
    								success:function(response){
    									if (response == "Success"){
    										alert("Core metadata has been successfully updated.");
    										window.location.href = "manageData.php";
    									}
    								}
    							})
    						})
    						//remove button for cores
    						$("#removeButton").click(function(){
    							test = confirm("Do you really want to remove this core and all associated files?  \nThis action cannot be undone.")
    							if (test){
    								$.ajax({
    									url:"scripts/deleteCore.php",
    									type:"POST",
    									data:{
    										coreName:$("#coreName").text()
    									},
    									success:function(response){
    										if(response=="Success"){
    											alert("Core " + $("#coreName").text() + " has been successfully deleted.");
    											window.location.href = "manageData.php";
    										}else{
    											console.log(response);
    										}
    									},
    									error:function(error){
    										alert("There was an error, please try again later.")
    									}
    								})
    							}else{
    								return
    							}
    							
    						})
    						
    						//there is not a file associate with cores so there is no updating
    						
    					}else if (data_type == 'file'){
    						$("#updateButton").show();
    						//populate if the button was a datafile
    						$("#submitUpdates").show();
    						var fileNumber = $(this).data('file');
    						var fileData = data['Datafiles'][0][fileNumber];
    						$("#myModalLabel").html("Datafile Details")
    						$("#modal-body").html(
    						"<table class='table-responsive' >" +
    						"<tr><td>File Name: </td><td id='fileName'>" + fileData['Name'] + "</td><tr>" +
    						"<tr><td>Core Name: </td><td id='coreName'>" + coreName + "</td><tr>" +
    						"<tr><td>Site Name: </td><td>" + siteName + "</td><tr>" +
    						"<tr><td>Min Depth: </td><td  contentEditable='true' id='minDepth' class='editFormat'>" + fileData['MinDepth'] + "</td><tr>" +
    						"<tr><td>Max Depth: </td><td contentEditable='true' id='maxDepth' class='editFormat'>" + fileData['MaxDepth'] + "</td><tr>" +
    						"<tr><td>Number of Taxa: </td><td contentEditable='true' id='numTaxa' class='editFormat'>" + fileData['NumTaxa'] + "</td><tr>" +
    						"<tr><td>Number of Levels: </td><td contentEditable='true' id='numLevels' class='editFormat'>" + fileData['NumLevels'] + "</td><tr>" +
    						"<tr><td>Last Modified: </td><td>" + fileData['FileLastModified'] + "</td></tr>"+
    						"<tr><td>Uploaded: </td><td>" + fileData['FileUploaded'] + "</td><tr>" + 
    						"<tr><td>File Version: </td><td>"+fileData['FileVersion'] +"</td></tr>" +
    						"</table>" 
    					)
    					//update function for datafiles
    					$("#submitUpdates").click(function(){
    						alert($("#fileName").text())
    							$.ajax({
    								url:'scripts/updateFileMetadata.php',
    								type:"POST",
    								data:{
    									coreName:$("#coreName").text(),
    									fileName:$("#fileName").text(),
    									minDepth:$("#minDepth").text(),
    									maxDepth:$("#maxDepth").text(),
    									numTaxa:$("#numTaxa").text(),
    									numLevels:$("#numLevels").text(),
    								},
    								success:function(response){
    									console.log(response)
    									if (response == "Success"){
    										alert("File metadata has been successfully updated.");
    										window.location.href = "manageData.php";
    									}
    								}
    							})
    						})
    						//remove function for datafiles
    						$("#removeButton").click(function(){
    							test = confirm("Do you really want to remove this datafile? \nThis action cannot be undone.")
    							if (test){
    								$.ajax({
    									url:"scripts/deleteFile.php",
    									type:"POST",
    									data:{
    										coreName:$("#coreName").text(),
    										fileName:$("#fileName").text()
    									},
    									success:function(response){
    										if(response=="Success"){
    											alert("Datafile " + $("#fileName").text() + " has been successfully deleted.");
    											window.location.href = "manageData.php";
    										}else{
    											console.log(response);
    										}
    									},
    							error:function(error){
    										alert("There was an error, please try again later.")
    									}
    								})
    							}else{
    								return
    							}
    						})
    						//update for datafiles
    						$("#updateButton").on('click', function(){
    							window.location.href = "addNewDatafile.php?core=" + $("#coreName").text() + "&fileName=" + $("#fileName").text() + "&update=true"
    						})
    						
    					}else if(data_type == 'chrono'){
    						//populate if the button was chronology
    						var chronData = data['Chronology']
    						$("#updateButton").show();
    						$("#myModalLabel").html("Chronology Details")
    						$("#modal-body").html(
    						"<table class='table-responsive' >" +
    						"<tr><td>Core Name: </td><td id='coreName'>" + coreName + "</td><tr>" +
    						"<tr><td>Min Age: </td><td>"+ chronData['minChronAge'] + "</td></tr>" + 
    						"<tr><td>Max Age: </td><td>" + chronData['maxChronAge'] + "</td></tr>" + 
    						"<tr><td>Min Depth: </td><td>" + chronData['minChronDepth'] + "</td><tr>" +
    						"<tr><td>Max Depth: </td><td>" + chronData['maxChronDepth'] + "</td><tr>" +
    						"<tr><td>Number of Levels: </td><td>" + chronData['ChronLevels'] + "</td><tr>" +
    						"<tr><td>Last Modified: </td><td>" + chronData['chronModified'] + "</td></tr>"+
    						"<tr><td>Uploaded: </td><td>" + chronData['chronUploaded'] + "</td><tr>" + 
    						"<tr><td>File Version: </td><td>"+chronData['chronVersion'] +"</td></tr>" +
    						"</table>" 
    						)
    						$("#submitUpdates").hide();
    						
    						//there is no update function for chronology
    						$("#removeButton").click(function(){
    							//remove function for chronology
    							test = confirm("Do you really want to remove this datafile? \nThis action cannot be undone.")
    							if (test){
    								$.ajax({
    									url:"scripts/deleteChronology.php",
    									type:"POST",
    									data:{
    										coreName:$("#coreName").text(),
    									},
    									success:function(response){
    										if(response=="Success"){
    											alert("Chronology for " + $("#coreName").text() + " has been successfully deleted.");
    											window.location.href = "manageData.php";
    										}else{
    											alert("There was an error deleting your file.  Please try again later.")
    											console.log(response);
    										}
    									}, 
    									error:function(error){
    										alert("There was an error, please try again later.")
    									}
    								})
    							}else{
    								return
    							}
    						})
    						//update for chronology
    						$("#updateButton").on('click', function(){
    								window.location.href = "addNewChronology.php?core=" + $("#coreName").text() + "&update=true"
    						})
    					}
    					
    					
    					
    				})
    			}
    		})

    	})
    </script>
    <script>
    </script>
  </body>
</html>
