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

  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Chronology</title>
	
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

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">

      	<h2 class="page-header featurette-heading">Add a new Chronology</h2>
      		<div class='row'>
	      			<div class="col-sm-4 nonhover" id='draganddrop'>
	      			<p id='status'>Drag and drop files here</p>
	      			</div>
	      		<div class='col-sm-2 padding' align="center"><p>Or...</p></div> 
	      		<div class="col-sm-6" id='browse'>
	      			<p>Browse for files.</p><br />
	      			<input type='file' name="fileUpload" id='fileUploadButton' accept=".csv" class='left-input'/>
	      		</div>
      		</div>
      		<div class='row'>
      		<p>Datafile Properties</p>
		      			<ul class='list-group'>
		      			<li class='list-group-item'><label for='coreDropdown'>Associated Core: </label><select id='coreDropdown'>
		      				<option value=null>
		      					--Select--
		      				</option>
		      			</select></li>
		      			<li class='list-group-item'><label for='numLevels'>Nubmer of Levels: </label><input type='number' name='numLevels' id='numLevels' size='35'/></li>
		      			<li class='list-group-item'><label for='minDepth'>Minimum Depth: </label><input type='number' name='minDepth' id='minDepth' size='35'/></li>
		      			<li class='list-group-item'><label for='maxDepth'>Maximum Depth: </label><input type='number' name='maxDepth' id='maxDepth' size='35'/></li>
		      			<li class='list-group-item'><label for='minAge'>Minimum Age: </label><input type='number' name='minAge' id='minAge' size='35'/></li>
		      			<li class='list-group-item'><label for='maxAge'>Maximum Age: </label><input type='number' name='maxAge' id='maxAge' size='35'/></li>
		      			<li class='list-group-item'><label for='form-submit'>Upload</label><input type='submit' value='Upload File' id='form-submit' name='form-submit'/></li>
		      			<li class='list-group-item' id='progress'><label for='progress'>Progress: </label><progress></progress></li>
		      		</ul>
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
     <script src="https://rawgit.com/mholt/PapaParse/master/papaparse.min.js"></script>
    <script>
    	//dynamically populates the core list
    	$(document).ready(function(){
    		$.ajax({
    			url:"scripts/populateCores.php",
    			type:'post',
    			success:function(response){
    				response = JSON.parse(response);
    				for (i in response){
    					$("#coreDropdown").append("<option value='" + response[i] + "'>" + response[i] + "</option>");
    				}
    				
    			}
    		})
    	})
    </script>
    <script>
    $("#progress").hide();
    var fileSet = false;
    //handle drag and drop
    var holder = document.getElementById('draganddrop'),
    state = document.getElementById('status');

		if (typeof window.FileReader === 'undefined') {
		  state.innerHTML = 'File reader is not supported by your browser, please use the upload button instead.';
		}
    holder.ondragenter = function(){$(this).toggleClass("hover"); $(this).toggleClass("nonhover")}
	holder.ondragleave = function(){$(this).toggleClass("hover"); $(this).toggleClass("nonhover")}
	holder.ondragover = function () { return false; };
	holder.ondragend = function () {return false; };
	
	var uploadFile;
	var fName;
	var modifiedData;
	holder.ondrop = function (e) {
		  e.preventDefault();
		  var file = e.dataTransfer.files[0]
		  var filetype = file.type;
		  if (filetype == 'text/csv'){
		  	handleFile(file);
		  }else{
		  	alert("That filetype is not currently supported.  Please try a comma-separated value file.");
		  }
		  $(this).toggleClass('hover')
		  $(this).toggleClass("nonhover")
		  return false;
	};
		
		var button = document.getElementById('fileUploadButton');
		button.addEventListener('change', function(evt){
			file = evt.target.files[0];
			handleFile(file)
	})
	function handleFile(file){
		fileSet = true;
		uploadFile = file;
		var modifiedDate = file['lastModifiedDate']
		var fName = file['name'];
		var reader = new FileReader();
		reader.readAsText(file);
		reader.onload = function(e){
			var theFile= e.target.result;
			var data = Papa.parse(theFile, {
				complete: function(results){
					parsedData = results.data;
					//update UI fields
					var numLevels = parsedData.length;
					$("#numLevels").val(numLevels);
					var maxDepth = -999999999
					var minDepth = 999999999
					var maxAge = -99999999;
					var minAge = 999999999;
					i = 1;
					while (i < parsedData.length){
						var level = parsedData[i];
						var depth = +level[0];
						var age = +level[1];
						if (depth > maxDepth){
							maxDepth = depth;
						}
						if(depth < minDepth){
							minDepth = depth;
						}
						if(age > maxAge){
							maxAge = age;
						}
						if(age < minAge){
							minAge = age;
						}
						i +=1;
					}
					$("#minDepth").val(minDepth);
					$("#maxDepth").val(maxDepth);
					$("#minAge").val(minAge);
					$("#maxAge").val(maxAge);
				}
			});
		}
	}
	$("#coreDropdown").change(function(){
		coreID = $(this).val();
		$.ajax({
			url:"scripts/checkChronologyStatus.php",
			type:"POST",
			success:function(response){
				console.log(response)
				if(response != 0){
					alert("This core already has a chronology associated with it.  If you continue, you will overwrite it.");
				}		
			},
			data:{
				coreID:coreID
			}
		})
	})
	
	//handle form submit
    $("#form-submit").on('click', function(e){
    	if (fileSet == false){
    		alert("Please specify a valid chronology file for upload.")
    		return
    	}else{
    		$("#progress").show();
    		var coreName = $("#coreDropdown").val();
    		var numLevels = $("#numLevels").val();
    		var minDepth = $("#minDepth").val();
    		var maxDepth = $("#maxDepth").val();
    		var minAge = $("#minAge").val();
    		var maxAge = $("#maxAge").val();
    		e.preventDefault();
    		var formData = new FormData();
    		var actualFileName = uploadFile.name;
    		var fileLastModified = uploadFile.lastModifiedDate;
    		formData.append('upload', uploadFile, coreName);
    		console.log(fileLastModified);
    		function progressFunction(e){
    			if(e.lengthComputable){
    				$("progress").attr({value:e.loaded, max:e.total});
    			}
    		}
    		$.ajax({
    			url:"scripts/processNewChronology_Metadata.php",
    			type:"POST",
    			data:{
    				coreName:coreName,
    				numLevels:numLevels,
    				minDepth:minDepth,
    				maxDepth:maxDepth,
    				minAge:minAge,
    				maxAge:maxAge,
    				lastModified:fileLastModified,
    			},
    			success:function(response){
    				console.log(response);
    				if(response == "Success"){
    					alert("Datafile Added.");
    					window.location.href = "manageData.php";
    				}
    			}
    		})
    		$.ajax({
    			url:"scripts/processNewChronology_File.php",
    			type:"POST",
    			data:formData,
    			xhr: function(){
    				var myXhr = $.ajaxSettings.xhr();
    				if(myXhr.upload){
    					myXhr.upload.addEventListener('progress', progressFunction, false)
    				}
    				return myXhr;
    			},
    			beforeSend:function(){
    				console.log("SENDING: " + formData);
    			},
    			error:function(e){
    				console.log("ERROR :" + e.error)
    			},
    			success: function(response){
    				console.log(response);
    				if(response == 1){
    					console.log("Successfully uploaded chronology.");
    				}else{
    					alert("error saving datafile to server.")
    				}
    			},
    			cache:false,
    			contentType:false,
    			processData:false	
    		})
    	}
    	
    	})
    </script>
    <script>
    	    	function getUrlParameter(sParam)
		{
		    var sPageURL = window.location.search.substring(1);
		    var sURLVariables = sPageURL.split('&');
		    for (var i = 0; i < sURLVariables.length; i++) 
		    {
		        var sParameterName = sURLVariables[i].split('=');
		        if (sParameterName[0] == sParam) 
		        {
		            return decodeURIComponent(sParameterName[1]);
		        }
		    }
		}  
		var update = getUrlParameter('update');
		var fileName = getUrlParameter('fileName');
		var core = getUrlParameter('core');
		$("#fileName").val(fileName);
		if(update == 'true'){
			$("#fileName").attr('disabled', 'disabled')
		}
    </script>
  </body>
</html>
