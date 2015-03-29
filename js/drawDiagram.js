///THIS IS drawDiagram.js 
//This file handles all GRAPHING.  All properties for graphing and displaying data must be previously set by the user createPlotClient.js
//global object so that we can keep track of mins/maxes, file names, etc
console.log("Creating Pollen Diagram using Calpalyn II drawDiagram.js v1.0.0");
var plotting = {}; // namespace for temp vars as needed
//can either use absolute dimensions or multiplier/percentages --> better for scaling the diagram with different sizes
var rules; //configuration object for holding potentially changing diagram config rules that are independent of the particular diagram being created 
$.ajax({
	url: "../config/diagramFormat.json",
	async: false,
	success: function(response){
		rules = response;
		console.log("Formatted rules established from JSON file.")
	},
	error: function(e){
		console.log("Error establishing formatting rules");
	},
	contentType: "json"
})


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
			//saves the project into the database of successfully created configuration files
			url: "../scripts/saveProject.php",
			data: {
				core: coreName,
				creationTime: timestamp
			},
			error: function(){
				console.log("Error in saving to saved projects database");
			},
			success: function(response){
				if(response == "Error"){
					console.log("Server error in saving to saved projects database");
				}else if(response =="Success"){
					console.log("Project file successfully saved to the database");
				}
			}
		})
		$.ajax({
			//reads the configuration file from server 
			url: fName,
			method: "GET",
			dataType: "json",
			success: function(response){
				console.log("CPN file read successfully.  Drawing diagram.")
				console.log(response)
				drawDiagram(response)
			},
			error: function(e){
				alert("Error getting CPN file.  Please try again.");
			}
		})
		//success callback function once we have received the cpn file
		function drawDiagram(config){
			//first parse the configuration file
			//start with plot dimensions and title
			diagramWidth = config['plotWidth'];
			diagramHeight = config['plotHeight'];
			//dimensions default to -1, so if there is no height specified --> error?  
			if (diagramWidth == -1 ||  diagramHeight == -1){
				alert("It seems that the diagram configuration file has invalid plot dimensions.  Please return to the diagram configuration page.");
				throw "Plot Dimension Error"; 
			}
			//Diagram Scaling/////////////
			A_diagram = diagramHeight * diagramWidth  // total diagram area on a printed page in pixels;
			console.log("Creating a diagram of width: " + diagramWidth + " and Height: " + diagramHeight + " Yielding an area of : " + A_diagram);
			//figure out which components this diagram has
			Xchart = diagramWidth
			//take off space for primary axis because all diagrams have a primary axis
			Xprimary = diagramWidth * spacing['Xprimary-multiplier']; //pixels
			Xchart -= Xprimary
			if ((config['axes']['showSecondaryAxis'] == true) || (config['axes']['showSecondaryAxis'] == "true")){
				//take off space for secondary axis if the user requested that it be shown
				Xsecondary = diagramWidth * space['Xsecondary-multiplier'];
				Xchart -= Xsecondary;
			}
			if ((config['stratigraphy']['doStratigraphy'] == true) || config['stratigraphy']['doStratigraphy'] == "true"){
				Xcol = diagramWidth * space['Xcol-multiplier'];
				Xchart -= Xcol;
			}
			//always use px measurements for the padding
			totalPadding = spacing['Xpadding'] * config['taxa'].length
			Xchart -= totalPadding;
			config['Xcanvas'] = Xchart;
			//iterate through values and do specified manipulations
			for (var i=0; i< config['taxa'].length; i++){
				//determine the values to graph, which differ from actual values with normalization
				config['taxa'][i]['graphingValues'] = config['taxa'][i]['valuesMatrix'] //maybe better to just mutate this??
				var taxon = config['taxa'][i];
				//do normalization steps and mutate the values matrix with a norm parameter 
				/*
				if (taxon['show5xCurve']){ //-----> this needs to come last
					var newVals = []
					for (var p= 0; p< taxon.valuesMatrix.length; p++){
						var val = taxon['valuesMatrix'][p]['value'];
						var depth = taxon['valuesMatrix'][p]['depth'];
						newVal = val * 5
						newVals.push({depth:depth, value:newVal});
					}
				}
				*/
			}
				
			
		}
		
	
	
	
	
	/**
 * @author Scott Farley
 */
