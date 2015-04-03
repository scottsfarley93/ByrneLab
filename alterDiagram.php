
<?php
require_once("database_access.php");
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
  <head>
  	<style>
  	#saveButton{
  		float:right;
  	}
  	.glyphicon{
  		float:right;
  	}
  	.right{
  		float:right
  	}
  	th{
  		margin-right: 2%;
  		padding-right: 5%!important;
  	}
	  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plot</title>
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
         	          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Diagram Options <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li id='download'><a href="#">Download as SVG</a></li>
                <li id='store'><a href="#">Store on Calpalyn</a></li>      
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="cgi-bin/logout.php">Logout</a></li>
          </ul>
      </div>
    </nav>

    <div class="container" id='mainPage'>
    	    <h1 class='page-header'>
    	Edit Diagram Properties
    </h1>
    	<ul id='settings' class='list-group'>
    		<li class='list-group-item'>Plot Title: <input type='text' id='plotTitle' size='60'/></li>
    		<li class='list-group-item'>Plot Height <input type='number' id='plotHeight'/> </li>
    		<li class='list-group-item'>Plot Width <input type='number' id='plotWidth'/> </li>
    		<li class='list-group-item'>Diagram Taxa: <ul class='list-group' id='taxa-list'>
    			
    		</ul>
    		</li>
    		<li class='list-group-item'>Stratigraphy Column 
    			<ul class='list-group'>
    				<li class='list-group-item'><input type='checkbox' id='doStratigraphy'/>Show Stratigraphy Column </li>
    				<li class='list-group-item'>Edit Stratigraphy Column <button class='glyphicon glyphicon-edit strat-edit btn-success' data-toggle='modal' data-target='#stratEdit-modal'></button></li>
    			</ul>
    		</li>
    		<li class='list-group-item'>Zonation
    			 <ul class='list-group'>
    				<li class='list-group-item'><input type='checkbox' id='doZonation'/>Show Zonation</li>
    				<li class='list-group-item'>Edit Zonation <button class='glyphicon glyphicon-edit zone-edit btn-success' data-toggle='modal' data-target='#zoneEdit-modal'></button></li>
    			</ul>
    		</li>
    		<li class='list-group-item'>Axes
    			<li class='list-group-item'>Primary Axis Label <input type='text' id='primaryAxisLabel'</li>
    			<li class='list-group-item'>Primary Axis Units <input type='text' id='primaryAxisUnits' </li>
    			<!--Secondary axis properties could go here -->
    		</li>
    	</ul>
    	<button class='btn btn-success' id='saveButton'>Save Changes</button>
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
	<div class='modal fade' id='taxa-edit-modal' tabindex="-1" role='dialog' aria-hide='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class='modal-title'>Taxon Properties Editor</h4>
				<div class='modal-body'>
					<ul class='list-group'>
						<li class='list-group-item'>Top Label <input type='text' id='topLabel'/> <span class='right'><input type='checkbox' id='italics' />Show label in italics</span></li>
						<li class='list-group-item'>Fill Color<input type='color' id='fill'></li>
						<li class='list-group-item'>Outline Color<input type='color' id='outline'/></li>
						<li class='list-group-item'>Normalization Type<select id='normType'><option value='none'>--None--</option></select></li>
						<li class='list-group-item'>500% Curve<input type='checkbox' id='500Curve'/></li>
						<li class='list-group-item'>Plot Type <select id='plotType'><option value='curve'>Curve</option><option value='bar'>Bar Chart</option></select></li>
						<li class='list-group-item'>Group <select id='group'>
								<option value='none'>--None--</option>
								<option value='herbs'>Herbs</option>
								<option value='shrubs'>Shrubs</option>
								<option value='trees'>Trees</option>
								<option value='aquatics'>Aquatics</option>
						</select></li>
						<li class='list-group-item'>Bottom Label <input type='text' id='bottomLabel'/></li>
						<li class='list-group-item'>Curve Smoothing <select id='smoothing'>
							<option value='None'>--None--</option>
							<option value='3'>Three Period Smoothing</option>
						</select></li>
					</ul>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id='saveTaxon' data-dismiss='modal'>Save changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class='modal fade' id='stratEdit-modal' tabindex="-1" role='dialog' aria-hide='true'>
<div class='modal-dialog'>
	<div class='modal-content'>
		<div class='modal-header'>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class='modal-title'>Stratigraphy Column Editor</h4>
			<div class='modal-body'>
				<table class='modal-table' id='stratTable' contenteditable>
					<thead><th>Zone Label</th><th>Upper Boundary</th><th>Lower Boundary</th><th>Layer Fill</th><th>Boundary Type</th></thead>
					<tbody id='stratTableBody'>
						
					</tbody>
				</table>
			<button class='btn btn-success' id='addStratLayerButton'>Add Stratigraphy Layer <span class='glyphicon glyphicon-plus'></span></button>
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id='saveStratigraphy' data-dismiss='modal'>Save changes</button>
			</div>
		</div>
	</div>
</div>
</div>
<div class='modal fade' id='zoneEdit-modal' tabindex="-1" role='dialog' aria-hide='true'>
<div class='modal-dialog'>
	<div class='modal-content'>
		<div class='modal-header'>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class='modal-title'>Zonation Editor</h4>
			<div class='modal-body'>
				<table class='modal-table' id='zoneTable' contenteditable>
					<thead><th>Zone Label</th><th>Upper Boundary</th><th>Lower Boundary</th><th>Sub Zone</th></thead>
					<tbody id='zoneTableBody'>
						
					</tbody>
				</table>
			<button class='btn btn-success' id='addZoneButton'>Add Zone <span class='glyphicon glyphicon-plus'></span></button>
			</div>
			<div class='modal-footer'>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id='saveZonation' data-dismiss='modal'>Save changes</button>
			</div>
		</div>
	</div>
</div>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
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
	var config
	$.ajax({
		//reads the configuration file from server 
		url: fName,
		method: "GET",
		dataType: "json",
		success: function(response){
			console.log("CPN file read successfully.  Drawing diagram.")
			console.log(response)
			config = response;
			addTaxa(response)
			$("#plotTitle").val(config['title'])
			$("#plotWidth").val(config['plotWidth'])
			$("#plotHeight").val(config['plotHeight'])
			if (config['zonation']['doZonation'] == true || config['zonation']['doZonation'] == 'true'){
				$("#doZonation").prop('checked', true);
			}
			if (config['stratigraphy']['doStratigraphy'] == true || config['stratigraphy']['doStratigraphy'] == 'true'){
				$("#doStratigraphy").prop('checked', true)
			}
			$("#primaryAxisLabel").val(config['axes']['primaryAxisTitle'])
			$("#primaryAxisUnits").val(config['axes']['primaryAxisUnits'])
			config=response;
		},
		error: function(e){
			alert("Error getting CPN file.  Please try again.");
		}
	})
	function addTaxa(config){
		for (var i=0; i< config['taxa'].length; i++){
			var taxon = config['taxa'][i];
			$("#taxa-list").append("<li class='list-group-item'>" + taxon['topLabel'] + "<button data-taxa= '" + i + "' class='glyphicon glyphicon-edit taxon-edit btn-success' data-toggle='modal' data-target='#taxa-edit-modal'></button></li>")
		}
		if (config['normalization']['dataSum']['doDataSum'] == true){
			$("#normType").append("<option value='sumField'>Percentage of Sum Field</option>")
		}
		if (config['normalization']['subtotals']['numSubtotals'] > 0){
			for (var q = 0; q< config['normalization']['subtotals']['numSubtotals']; q++){
				$("#normType").append("<option value='sub'" + q + "'>Percentage of Subtotal #" + (q+1) + "</option>")
			}
		}
		if (config['normalization']['apfac']['doVolumeApfac']== true){
			$("#normType").append("<option value='volumeApfac'>As grains per unit volume </option>")
		}
		if (config['normalization']['apfac']['doMassApfac'] == true){
			$("#normType").append("<option value='massApfac'>As accumulation rate</option>")
		}
		$(".taxon-edit").click(function(){
			var num = $(this).data('taxa');
			var taxon = config['taxa'][num];
			var topLabel = taxon['topLabel'];
			$("#topLabel").val(topLabel)
			if (taxon['topLabelItalics'] == true || taxon['topLabelItalics'] == "true"){
				$("#italics").prop('checked', true)
			}
			$("#fill").val(taxon['fill'])
			$("#outline").val(taxon['outline'])
			var normType = taxon['norm'];
			console.log("Norm: " + normType)
			$("#normType").val(normType);
			if (taxon['show5xCurve']== true || taxon['show5xCuve'] == "true"){
				$("#500Curve").prop('checked', true);
			}
			var group = taxon['grouping'];
			$("#group").val(group);
			$("#bottomLabel").val(taxon['bottomLabel'])
			if (taxon['bottomLabel'] == ""){
				$("#bottomLabel").attr('placeholder', "None")
			}
			var smoothing = taxon['smoothing'];
			console.log("Smooting: " + smoothing);
			$("#smoothing").val(smoothing);
			//keep track of the number so we can do updating
			$("#saveTaxon").data('num', num);
		})
	}
	function addStratigraphy(config){
		console.log("Stratigraphy:")
		strat = config['stratigraphy'];
		var col = strat['stratColumn'];
		$("#stratTableBody").empty();
		for (var i=0; i< col.length; i++){
			var layer = col[i]
			s = "<tr id='strat" + i + "'><td class='stratLabel'>" + layer['label'] + "</td><td class='stratTop'>" + +layer['layerTop'] + "</td><td class='stratBottom'>" + +layer['layerBottom'] + "</td><td><select class='fillDropdown'><option value='none'>--None--</option></select></td>"
			s += "<td><select class='layerBoundary'><option value='none'>--None--</option></select></td><td><button class='glyphicon glyphicon-remove btn-danger removeStrat'></button></tr>"
			$("#stratTableBody").append(s)
		}
		$(".removeStrat").click(function(){
			$(this).closest("tr").remove();
		})
	}
	$(".strat-edit").click(function(){
		addStratigraphy(config)
	})
	$("#addStratLayerButton").click(function(){
		s = "<tr><td class='stratLabel'>None</td><td class='stratTop'>0</td><td class='stratBottom'>0</td><td><select class='fillDropdown'><option value='none'>--None--</option></select></td>"
			s += "<td><select class='boundaryDropdown'><option value='none'>--None--</option></select></td><td><button class='glyphicon glyphicon-remove btn-danger removeStrat'></button></tr>"
		$("#stratTableBody").append(s);
		$(".removeStrat").click(function(){
			$(this).closest("tr").remove();
		})
	})
	function addZonation(config){
		console.log("Zonation")
		zones = config['zonation']['zonation'];
		$("# zoneTableBody").empty();
		for (var i =0; i< zones.length; i++){
			var zone = zones[i]
			console.log(zone)
			s = "<tr><td class='zoneLabel'>" + zone['label'] + "</td><td class='zoneTop'>" + +zone['zoneTop'] + "</td><td class='zoneBottom'>" + +zone['zoneBottom'] + "</td><td><input type='checkbox' class='subzone'"
			if (zone['subzone'] == "true" || zone['subzone'] == true){
				s += "checked='true'"
			}
			s += "</td><td><button class='glyphicon glyphicon-remove btn-danger removeZone'></button></tr>"
			$("#zoneTableBody").append(s)
		}
		$(".removeZone").click(function(){
			$(this).closest("tr").remove();
		})
	}
	$(".zone-edit").click(function(){
		addZonation(config);
	})
	$("#addZoneButton").click(function(){
		s = "<tr><td class='zoneLabel'>None</td><td class='zoneTop'>0</td><td class='zoneBottom'>0</td><td><input type='checkbox' class='subzone'/></td>"
	    s += "<td><button class='glyphicon glyphicon-remove btn-danger removeZone'></button></tr>"
		$("#zoneTableBody").append(s)
		$(".removeZone").click(function(){
			$(this).closest("tr").remove();
		})
		
	});
	///modal dismiss & save functions
	$("#saveTaxon").click(function(){
		var num = $(this).data('num')
		var topLabel = $("#topLabel").val();
		var italics = $("#italics").prop('checked')
		var fill = $("#fill").val()
		var outline = $("#outline").val()
		var normType = $("#normType").val()
		var exagCurve = $("#500Curve").prop('checked')
		var plotType = $("#plotType").val()
		var group = $("#group").val()
		var bottomLabel  = $("#bottomLabel").val()
		var smoothing = $("#smoothing").val();
		config['taxa'][num]['topLabel'] = topLabel;
		config['taxa'][num]['topLabelItalics'] = italics;
		config['taxa'][num]['plotType'] = plotType
		config['taxa'][num]['fill'] = fill;
		config['taxa'][num]['outline'] = outline
		config['taxa'][num]['show5xCurve'] = exagCurve;
		config['taxa'][num]['smoothing'] = smoothing;
		config['taxa'][num]['norm'] = normType;
		config['taxa'][num]['grouping'] = group;
		config['taxa'][num]['bottomLabel'] = bottomLabel;
		console.log(config['taxa'][num])
	})
	$("#saveStratigraphy").click(function(){
		var labels = $(".stratLabel")
		var tops = $(".stratTop")
		var bottoms = $(".stratBottom")
		var fills = $(".fillDropdown")
		var boundaries = $(".boundaryDropdown")
		config['zonation']['zonation'] = [];
		for (var i=0;i< labels.length; i++){
			var label = $(labels[i]).text();
			var top = $(tops[i]).text()
			var bottom = $(bottoms[i]).text();
			var fill = $(fills[i]).val()
			var boundary = $(boundaries[i]).val();
			zone = {label: label, layerTop:top, layerBottom: bottom, layerFill: fill, layerBoundary:boundary}
			config['stratigraphy']['stratColumn'].push(zone)
		}
	})
	$("#saveZonation").click(function(){
		var labels = $(".zoneLabel")
		var tops = $(".zoneTop")
		var bottoms = $(".zoneBottom")
		var subzones = $(".subzone")
		config['zonation']['zonation'] = [];
		for (var i=0; i<labels.length; i++){
			var label = $(labels[i]).text();
			var top = $(tops[i]).text()
			var bottom = $(bottoms[i]).text();
			var subzone = $(subzones[i]).prop('checked')
			var zone = {label: label, zoneTop: top, zoneBottom:bottom, subzone:subzone}
			config['zonation']['zonation'].push(zone);
		}
		console.log(config['zonation'])
	})
	
$("#saveButton").click(function(){
	//get things that were not set in the modal
	console.log("Saving properties...")
	var plotTitle = $("#plotTitle").val();
	var plotWidth = $("#plotWidth").val();
	var plotHeight = $("#plotHeight").val();
	var doZonation = $("#doZonation").prop('checked');
	var doStratigraphy = $("#doStratigraphy").prop('checked');
	var primaryAxisLabel = $("#primaryAxisLabel").val();
	var primaryAxisUnits = $("#primaryAxisUnits").val();
	var now = new Date();
	config.lastDrawn = now.toLocaleString();
	config['title'] = plotTitle
	config['plotWidth'] = plotWidth
	config['plotHeight'] = plotHeight;
	config['zonation']['doZonation']=doZonation;
	config['stratigraphy']['doStratigraphy'] = doStratigraphy;
	config['axes']['primaryAxisTitle'] = primaryAxisLabel;
	config['axes']['primaryAxisUnits'] = primaryAxisUnits
	console.log("Updating config...");
	c = JSON.stringify(config);
	$.ajax({
		url:"scripts/saveDiagramConfig.php",
		type:"POST",
		error:function(error){
			alert("Error gathering data from the server.  Please try again later.");
			console.log("TAXA AJAX ERROR:");
			console.log(error)
		},
		success: function(r){
			console.log(r)
			if (r['success'] == 'true'){
				console.log(r);
				user = r['user'];
				core = r['core'];
				t = r['timestamp'];
				//pass the correct params to the url so we can fetch the right cpn file.
				url = "drawDiagram.php?user=" + user + "&core=" + core + "&creationTime=" + t
				console.log(url)
				document.location.href = url;
			}else if (r['error'] == 'true'){
				alert(r['message'])
			}
		},
		data:{
			config: c,
			core: config['core']
		},
		cache: false,
	})
})
    </script>
  </body>
</html>
