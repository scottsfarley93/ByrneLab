///THIS IS drawDiagram.js 
//This file handles all GRAPHING.  All properties for graphing and displaying data must be previously set by the user createPlotClient.js
//global object so that we can keep track of mins/maxes, file names, etc
console.log("Creating Pollen Diagram using Calpalyn II drawDiagram.js v1.2.0");
var startTime = new Date();
console.log("Drawing started at: " + startTime.toLocaleString())
var config; //loaded diagram configuration file
var plotElements = {
	svg: "", //svg holder
	yAxis: "", //primary axis (time or depth)
	yAxis2: "", //secondary axis (time or depth if needed)
	xScale: "", //px per value of canvas
	axisTop: "", //top of screen
	axisBottom: "", //bottom of screen
}; 
//can either use absolute dimensions or multiplier/percentages --> better for scaling the diagram with different sizes
var rules; //configuration object for holding potentially changing diagram config rules that are independent of the particular diagram being created 
$.ajax({
	url: "../config/diagramFormat.json",
	async: false,
	success: function(response){
		rules = JSON.parse(response);
		console.log("Formatted rules established from JSON file.")
	},
	error: function(e){
		console.log("Error establishing formatting rules");
	},
	contentType: "json"
})
console.log(rules);


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
	//reads the configuration file from server 
	url: fName,
	method: "GET",
	dataType: "json",
	success: function(response){
		console.log("CPN file read successfully.  Drawing diagram.")
		console.log(response)
		config = response;
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
	A_diagram = diagramHeight * diagramWidth  // total diagram area on a printed page in pixels^2;
	console.log("Creating a diagram of width: " + diagramWidth + " and Height: " + diagramHeight + " Yielding an area of : " + A_diagram + " px^2");
	//figure out which components this diagram has
	Xchart = diagramWidth - (+rules['margins']['left']* diagramWidth) - (+rules['margins']['right'] * diagramWidth);
	//take off space for primary axis because all diagrams have a primary axis
	Xprimary = diagramWidth * +rules['primaryAxis']; 
	curvePadding = +rules['canvasPadding'];
	Xchart -= Xprimary
	if ((config['axes']['showSecondaryAxis'] == true) || (config['axes']['showSecondaryAxis'] == "true")){
		//take off space for secondary axis if the user requested that it be shown
		Xsecondary = diagramWidth * +rules['secondaryAxis'];
		xPadding = (+rules['axisPadding'] * diagramWidth)
		Xchart -= (Xsecondary + +rules['axisPadding']);
	}
	if ((config['stratigraphy']['doStratigraphy'] == true) || config['stratigraphy']['doStratigraphy'] == "true"){
		Xcol = diagramWidth * +rules['startigraphyCol'];
		xPadding = (+rules['axisPadding'] * diagramWidth)
		Xchart -= (Xcol + xPadding);
	}
	//always use px measurements for the padding in the diagram
	totalPadding = config['taxa'].length * 2 //in the canvas
	Xchart -= totalPadding;
	config['Xcanvas'] = Xchart;
	console.log("Number of pixels allotted to canvas width: " + Xchart);
	//iterate through values and do specified manipulations
	var xmax = 0;
	for (var i=0; i< config['taxa'].length; i++){
		var curveMax = -Infinity;
		var taxon = config['taxa'][i];
		var normType = taxon['norm'];
		var norm3 = normType.substring(0, 3); //first three characters to check if it is a subtotal
		var valueMatrix = taxon['valuesMatrix'];
		var numLevels = valueMatrix.length;
		//there are four types of normalization: sum, subtotal, volume apfac, mass apfac
		//check each one and then add a 'norm' property to the values matrix for the taxon
		if (normType == 'sumField'){ //sumField
			console.log("Normalizing by sum field.")
			var sumMatrix = config['normalization']['dataSum']['sumMatrix'];
			console.log(">>>>Sum Matrix<<<<<")
			console.log(sumMatrix)
			if (sumMatrix.length < numLevels){
				console.log("Lengths do not align -- Unexpected results may occur.  Proceeding anyway...");
			}
			for (var w =0; w <numLevels; w++){
				var taxonLevel = valueMatrix[w];
				var normLevel = sumMatrix[w];
				var taxonValue = +taxonLevel['value'];
				var normValue = +normLevel['value'];
				try{
					var normalizedValue = (+taxonValue / +normValue) * 100;
				}catch(err){///catch div/!0 errors
					var normalizedValue = 0;
					console.log(err.message + " caught.  Proceeding.");
				}
				config['taxa'][i]['valuesMatrix'][w]['norm'] = normalizedValue;
			}
		}else if (normType == 'volumeApfac'){
			console.log("Volume APFAC not yet working.  Aborting.")
			throw "Normalization technique not yet implemented"
		}else if (normType == 'massApfac'){
			console.log("Volume APFAC not yet working.  Aborting.")
			throw "Normalization technique not yet implemented"
		}else if (norm3 == 'sub'){ //subtotals
			var normEnd = normType.substring(3); //subtotal number
			var subtotal = config['normalization']['subtotals']['subtotals'][normEnd];
			//iterate through levels first
			for (var w=0; w<numLevels; w++){
				var taxonValue = +valueMatrix[w]['value'];
				var subtotalValue = 0;
				for (var q=0; q<subtotal.length; q++){//iterate through the taxa in the subtotal
					var item = subtotal[q]['matrix'];
					var stValue = item[w];
					subtotalValue += +stValue;
				}
				try {
					var normalizedValue = (+taxonValue / +subtotalValue) * 100;
				}catch (err){
					var normalizedValue = 0;
					console.log(err.message + " caught.  Proceeding.");
				}
				
				config['taxa'][q]['valuesMatrix'][w]['norm'] = normalizedValue;
			}
		}else if (normType == "none"){//no normalization just leave the value unmodified
			console.log("Start of loop curve max: " + curveMax)
			for (var w=0; w< numLevels; w++){
				var val = +valueMatrix[w]['value'];
				config['taxa'][i]['valuesMatrix'][w]['norm'] = val;
			}
		}else{
			throw "Unexpected normalization type.  Aborting."
		}
		for (var p=0; p<numLevels; p++){
			var normval = config['taxa'][i]['valuesMatrix'][p]['norm'];
			console.log("P: " + p + " value: " + normval)
			var depth = config['taxa'][i]['valuesMatrix'][p]['depth'];
			if (depth == NaN || depth == ""){
				console.log("Depth value is not numeric.  Removing from matrix.")
				config['taxa'][i]['valuesMatrix'].splice(p, 1)
			}
			if (normval == NaN || normval == Infinity || normval == -Infinity){
				console.log("Normalized value is not numeric.  Passing.");
				config['taxa'][i]['valuesMatrix'][p]['norm'] = 0;
			}
		}
		console.log(config['taxa'][i]['valuesMatrix'])
		curveMax = d3.max(config['taxa'][i]['valuesMatrix'], function(d){
			if (d.norm == Infinity || d.norm == -Infinity || d.norm == NaN || d.norm == ""){
				return 0
			}else{
				return d.norm
			}
		})
		xmax += curveMax;	
	}
	console.log("Total number of taxa points (xmax): " + xmax)
	//start putting the diagram components together 
	var plotSelection = d3.select("#plot");
	var svg = plotSelection.append("svg")
		.attr('width', diagramWidth)
		.attr('height', diagramHeight)
	svg.append('rect')
		.attr('width', diagramWidth)
		.attr('height', diagramHeight)
		.attr('fill', 'lightsteelblue')
	plotElements.svg = svg;
	//determine primary axis and Y-scaling
	var pAxis = config['axes']['primaryAxisCategory'];
	var yScale;
	//axis mapping 
	//svgs index from zero at the top, height at the bottom
	var axisTop = (+rules['margins']['top']  + +rules['Ygrouping'] + +rules['Ynames']) * diagramWidth;
	var axisBottom = diagramHeight - (+rules['margins']['bottom'] + +rules['Ybottom']) * diagramWidth;
	plotElements['axisTop'] = axisTop;
	plotElements['axisBottom'] = axisBottom;
	console.log("Axes extend from: " + axisTop + " To " + axisBottom);
	if (pAxis == 'Depth'){
		//scale on depth
		var maxDepth = +config['axes']['maxDepth'];
		var minDepth = +config['axes']['minDepth'];
		console.log("Depths extend from: " + maxDepth + " To " + minDepth);
		yScale = d3.scale.linear() //We can use the Yscaling provided by the d3 library, but will draw our own axes, so it is properly scaled.
			.range([axisTop, axisBottom])
			.domain([minDepth, maxDepth]);
		plotElements['yScale'] = yScale;
	}else if (pAxis == "Time"){
		//scale on chronology
		var minAge = +config['axes']['minAge'];
		var maxAge = +config['axes']['maxAge'];
		console.log("Ages extend from: " + minAge + " To " + maxAge);
		yScale = d3.scale.linear()
			.range([axisTop, axisBottom])
			.domain([minAge, maxAge]);
		plotElements['yScale'] = yScale;
	}else{
		throw "Unexpected primary axis type.  Aborting..."
	}
	drawPrimaryAxis();
	if (config['axes']['showSecondaryAxis'] == 'true'){
		drawSecondaryAxis();
	}else{
		console.log("No secondary axis requested");
	}
	//figure out where the first curve starts
	var xstart = (diagramWidth * +rules['margins']['left']) 
	if (config['axes']['showSecondaryAxis'] == "true" || config['axes']['showSecondaryAxis'] == true){
		xstart += (diagramWidth * +rules['secondaryAxis']) + (diagramWidth * +rules['axisPadding']);
	}
	xstart += (diagramWidth * +rules['primaryAxis']) + (diagramWidth * +rules['canvasPadding'])
	if (config['stratigraphy']['doStratigraphy'] == "true" || config['stratigraphy']['doStratigraphy'] == true){
		xstart += (diagramWidth * +rules['stratigraphyCol']) + (diagramWidth* +rules['axisPadding']);
	}
	
	var endOfCanvas = diagramWidth - (+rules['margins']['right'] * diagramWidth);
	//the canvas must also include padding between axes
	xmax += totalPadding;
	console.log("Main x-scale mapping: \nStart: " + xstart + " value: " + 0 + "\nEnd: " + endOfCanvas + " value: " + xmax)
	var xScaleMain = d3.scale.linear()
		.domain([0, xmax])
		.range([xstart, endOfCanvas])
	cursor = 0;
	//////central taxon graphing loop/////////
	for (var i=0; i< config['taxa'].length; i++){
		console.log("-------Now plotting Taxa: " + taxName);
		var taxon = config['taxa'][i];
		var taxName = taxon['name'];
		var taxValues = taxon['valuesMatrix'];
		var taxmax = -Infinity;
		for (var q=0; q<taxValues.length; q++){
			var val = taxValues[q]['norm']
			if ($.isNumeric(val)){
				if (val > taxmax){
					taxmax = val
				}
			}
		}
		console.log("Taxamax: " + taxmax)
		axisStart = xScaleMain(cursor)
		axisEnd = xScaleMain(cursor + taxmax)
		plotElements['svg'].append('line')
			.attr('x1', axisStart)
			.attr('x2', axisStart)
			.attr('y1', +plotElements['axisTop'])
			.attr('y2', +plotElements['axisBottom'])
			.style('stroke', 'black')
		taxScale = d3.scale.linear()
			.domain([0, taxmax])
			.range([axisStart, axisEnd])
		taxAxis = d3.svg.axis()
			.scale(taxScale)
			.orient("bottom")
		var taxAxisElement = plotElements['svg'].append('g')
			.attr('class', 'x axis')
			.call(taxAxis)
			.attr('transform', 'translate(0,' + axisBottom + ')')
		if (taxmax < 5){
			taxAxisElement.selectAll('text')
			.style('text-anchor', 'end')
			.attr('dx', '-.8em')
			.attr('dy', '.15em')
			.attr('transform', function(d){
				return 'rotate(-45)'})	
		}
		var curveStart = {depth: +config['axes']['minDepth'], norm: 0}
		var curveEnd = {depth: +config['axes']['maxDepth'], norm: 0}
		taxValues.unshift(curveStart)
		taxValues.push(curveEnd)
		var yscale = plotElements['yScale']
		var pathFunction = d3.svg.line()
			.x(function(d){return taxScale(d.norm)})
			.y(function(d){return yscale(d.depth)})
		var pathvals = pathFunction(taxValues);
		var strokeColor = taxon['outlineColor'];
		console.log("Drawing outline in color: " + strokeColor)
		var fillColor = taxon['fillColor'];
		console.log("Drawing fill in color: " + fillColor)
		var curve = plotElements['svg'].append('path')
			.attr('d', pathvals)
			.attr('stroke', strokeColor)
			.attr('fill', fillColor)
		if (taxon['show5xCurve'] == 'true' || taxon['show5xCurve'] == true){
			//add the exag curve
			console.log("Adding 5x curve.")
			var exagFunction = d3.svg.line()
					.x(function(d){return d.norm * 5})
					.y(function(d){return d.depth})
			var pathvals = exagFunction(taxValues);
			var strokeColor = '#ffffff';
			var fillColor = '#ffffff';
			var curve = plotElements['svg'].append('path')
				.attr('d', pathvals)
				.attr('stroke', strokeColor)
				.attr('fill', fillColor)
		}
			
		cursor += taxmax + 1.5
	}
}
		
	
function drawPrimaryAxis(tickInt){
	///This draws the primary axis, either chronological or depth based
	//if there is no secondary axis, this will be put in axis #1 slot, otherwise it will be automatically shifted to #2
	console.log("Drawing primary axis")
	var axis = plotElements['svg'].append("g").attr('class', 'y axis')
	var xVal = (+rules['margins']['left'] * +config['plotWidth']); //where does the axis start in x pixels
	console.log("XVAL: " + xVal)
	//TODO: Text scaling? 
	//need to make room for numbers?
	if(config['axes']['showSecondaryAxes'] == "true"){
		xVal += (+config['plotWidth'] * +rules['secondaryAxis']) + (+config['plotWidth'] * +rules['axisPadding']);
	}
	var xEnd = xVal + (+config['plotWidth'] * +rules['primaryAxis']);
	axis.append("line")
		.attr('y1', +plotElements['axisTop'])
		.attr('y2', +plotElements['axisBottom'])
		.attr('x1', xEnd)
		.attr('x2', xEnd)
	var topCap = axis.append('line')
		.attr('y1', +plotElements['axisTop'])
		.attr('y2', +plotElements['axisTop'])
		.attr('x1', xEnd)
		.attr('x2', (xEnd -10))
	var topCap = axis.append('line')
		.attr('y1', +plotElements['axisBottom'])
		.attr('y2', +plotElements['axisBottom'])
		.attr('x1', xEnd)
		.attr('x2', (xEnd -10))
	var values;
	if (config['axes']['primaryAxisCategory'] == "Depth"){
		console.log("Primary axis is depth.")
		
	}else if (config['axes']['priamaryAxisCategory'] == "Time"){
		console.log("Primary axis is chronology")
		
	}else{
		throw "Unexpected axis category.  Aborting!"
	}
	plotElements['primaryAxis'] = axis
}	

function drawSecondaryAxis(tickInt){
	//this can be either depth or chronology, but is scaled according to the primary axis
	//this will always fit into axis slot #1 (left)
	//TODO: Text scaling? 
	console.log("Drawing secondary axis")
	var axis = plotElements['svg'].append("g").attr('class', 'y axis')
	var xVal = (+rules['margins']['left'] * +config['plotWidth']); //where does the axis start in x pixels
	console.log("XVAL: " + xVal)
	var xEnd = xVal + (+config['plotWidth'] * +rules['secondaryAxis']);
	axis.append("line")
		.attr('y1', +plotElements['axisTop'])
		.attr('y2', +plotElements['axisBottom'])
		.attr('x1', xEnd)
		.attr('x2', xEnd)
	var topCap = axis.append('line')
		.attr('y1', +plotElements['axisTop'])
		.attr('y2', +plotElements['axisTop'])
		.attr('x1', xEnd)
		.attr('x2', (xEnd -10))
	var topCap = axis.append('line')
		.attr('y1', +plotElements['axisBottom'])
		.attr('y2', +plotElements['axisBottom'])
		.attr('x1', xEnd)
		.attr('x2', (xEnd -10))
	//secondary axis will be what primary axis is not
	if (config['axes']['primaryAxisCategory'] == 'Depth'){
		//secondary axis is chronology
		console.log("Drawing secondary axis as chronology");
	}else{
		//secondary axis is depth
		console.log("Drawing secondary axis as depth");
	}
	plotElements['secondaryAxis'] = axis
	
}
function drawCurve(taxon, xmin, xmax){
	//x-scaling:
	//xmin and xmax will be determined in the main function as canvas properties
	//a d3 scale will be calculated for this matrix
	//very possible to do a log scale if desireable
	var matrix = taxon['valuesMatrix'];
	if (taxon['show5xCurve'] == 'true' || taxon['show5xCurve'] == true){
		var taxmax = d3.max(matrix, function(d){return d.norm * 5})
	}else{
		var taxmax = d3.max(matrix, function(d){return d.norm})
	}
	
	var xScale = d3.scale.linear()
		.domain([0, taxmax])
		.range([xmin, xmax])
	var scaledVals = [];
	var axis = d3.svg.axis()
		.scale(xScale)
		.orient('bottom')
	var taxAxis = plot.append('g')
		.attr('class', 'x axis')
		.attr('transform', 'translate(0,' + +plotElements['axisBottom'] + ")")
		.call(axis)
		.selectAll('text')
			.style('text-anchor', 'end')
			.attr('dx', '-.8em')
			.attr('dy', '.15em')
			.attr('transform', function(d){
				return 'rotate(-45)'})	
	var pathFunction = d3.svg.line()
			.x(function(d){return Math.round(d.norm * 100) / 100})
			.y(function(d){return Math.round(d.depth * 100)/ 100})

	var pathvals = pathFunction(matrix);
	var strokeColor = taxon['outlineColor'];
	var fillColor = taxon['fillColor'];
	var curve = plotElements['svg'].append('path')
		.attr('d', pathvals)
		.attr('stroke', strokeColor)
		.attr('fill', fillColor)
	if (taxon['show5xCurve'] == 'true' || taxon['show5xCurve'] == true){
		//add the exag curve
		var exagFunction = d3.svg.line()
				.x(function(d){return d.norm * 5})
				.y(function(d){return d.depth})
		var pathvals = exagFunction(matrix);
		var strokeColor = '#ffffff';
		var fillColor = '#ffffff';
		var curve = plotElements['svg'].append('path')
			.attr('d', pathvals)
			.attr('stroke', strokeColor)
			.attr('fill', fillColor)
	}
}
function drawBarGraph(matrix){
	
}
	
	
	/**
 * @author Scott Farley
 */
