//THIS FILE IS createPlotClient
//This file handles setting object properties and saving them into a configuration file.  No actual data display is done in this file.
//TODO: 1.  fix subtotal coloring
console.log("Running version 2.0.4")
////////////////config keeps to graphing paramters and will be passed to the server with everything necessary to draw the graph////////
config = {
	title : "", //title of plot to be displayed
	core : "", //core identifier that holds the datasets
	//normalization properties
	
	normalization: {
		dataSum: { // controls whether or not to make a sum field available.  Must be precalculated by the user
			doDataSum:false,
			sumField : { //where to get the values for the data sum
				file :  "",
				fileIndex : -1,
				fieldName : "",
			},
		},
		subtotals: {
			//controls the generation of subtotal fields.  Will be calculated at runtime. 
			numSubtotals: 0,
			subtotals: [] //add to subtotal {file: "", fileIndex:"", fieldName: ""}
		},
		apfac: {
			//controls the normalization of data using apfac techniques.  
			//both grains per unit volume and accumulation rate are possible to compute.
			//Calculated at runtime
			doVolumeApfac: false,
			doMassApfac: false,
			volumeVolumeField: {file: "", fileIndex: "", fieldName: ""}, //volume field for calculating the volume apfac
			volumeControlField: {file: "", fileIndex: "", fieldName: ""}, //field with # of control grains for the volume apfac
			massMassField: {file: "", fileIndex: "", fieldName: ""}, //field with mass for calculation of mass/accumulation apfac
			massControField: {file: "", fileIndex: "", fieldName: ""}, // field with number of control grains for the mass apfac
			massYearField: {file: "", fileIndex: "", fieldName: ""},  //field with number of years in sample for accumulation apfac
			//arrays of {depth, value} pairs for each field
			volumeVolumeMatrix: [],
			volumeControlMatrix: [],
			massMassMatrix: [],
			massControlMatrix: [],
			massYearMatrix: []
		}
	},//end normalization
	plotHeight: 0, //in pixels
	plotWidth: 0, //in pixels
	axes: {
		//controls axis properties
		primaryAxisCategory: "Depth", //can either be "Depth" or "Time"
		showSecondaryAxis: false,
		primaryAxisTitle: "", //label to show on the main axis, eg "Depth"
		primaryAxisUnits: "", //label to show for units of main axis, eg "Meters"
		secondaryAxisTitle: "", //label to show on secondary axis, eg "Chronology"
		secondaryAxisUnits: "", //label for units of secondary axis, eg "Years"
		minDepth: -1, 
		maxDepth: -1,
		minYear: -1,
		maxYear: -1
	},
	hasChronologyFile: false,
	extraFeatures: false, // could be extended to control dendrogram and/or stratigraphy when those features are working
	taxa : [ //starts as empty array and is filled with n objects using the scheme below
		/*
		{
			dataset: "", 
			fileIndex: 0,
			name: "",
			plotIndex: 0, //ordering of taxa in the plot -- > 0 based left to right
			show5xCurve: false, //boolean to draw exaggeration curve,
			fillColor: "", //hex color for curve fill,
			outlineColor: "", //hex color for curve outline
			topLabel: "", //can be anything but defaults to taxon name in file,
			grouping: "", //Herbs, Shrubs, Trees, Trees & Shrubs, Aquatics, Other
			bottomLabel: "", //label to go underneath curve left aligned with axis
			topLabelItalics: true //controls whether the taxon name  (or other label)  will be printed in italix 
			valueMatrix: [] // array of {depth, value} pair objects
		}
		*/
	],
	zonation: {
		doZonation: false,
		numZones: 0,
		zonation: [],
		/*
		 * topDepth: 0,  // top boundary of zone
		 * bottomDepth: 0, //bottom boundary of zone
		 * label: "", //label or zone number
		 * zoneType: 0; //zone or subzone
		 * 
		 */
	},
	stratigraphy: {
		doStratigraphy: false,
		numLayers: 0,
		stratColumn: [
		/*
			{
				layerTop: 0 //top boundary of zone
				layerBottom: 0 //bottom boundary of zone
				layerFill: "" //texture --> need to make sure this is possible
				layerBoundary: 0 //type of boundary to draw 1-4
				layerLabel: "" //label of stratigraphy texture
			}
		*/
		],
	},
	numTaxa: 0, //total number of taxa to be plotted
	user: "", //user who create the plot,
	createdAt: "1900-01-01 12:00:00", //when the configuration file was created
	lastDrawn: "1900-01-01 12:00:00", //when the plot was last drawn 
}

///////////////////////////////////end config///////////////////

//keep track of variables for the plot
//taxaList is for client side
var taxaList = []
//keep track of what page we are on
var page = 0;
$("#backButton").on('click', function(){
	page -= 1;
})
$("#nextButton").on('click', function(){
	page += 1;
})

var now = new Date();
config.lastDrawn = now.toLocaleString();
config.createdAt = now.toLocaleString();
var currentIndex = 1;

//hide everything at the start
$("#selectCoreDiv").hide();
$("#selectTaxaDiv").hide();
$("#backButton").prop('disabled', true)
$("#orderCurvesDiv").hide()
$("#dimensionsDiv").hide()
$("#axesDiv").hide();
$("#stylingDiv").hide();
$("#normalizationDiv").hide();
$("#secondaryProps").hide()
$("#extraFeaturesDiv").hide();
$("#titleMenu").addClass("active")
$("#selectSum").hide();
$("#subtotalDiv").hide()
$("#apfacDiv").hide()
$("#sumFieldDropdown").prop("disabled", true);

//swaps array values to the specified indeces
var swap = function(theArray, indexA, indexB) {
    var temp = theArray[indexA];
    theArray[indexA] = theArray[indexB];
    theArray[indexB] = temp;
};

//get the list of cores from the server
$.ajax({
	url:"scripts/populateCores.php",
	type:'POST',
	error:function(error){
		alert("Error gathering data from the server.  Please try again later.");
		console.log("CORE AJAX ERROR: " . error);
	},
	success: function(response){
		response = JSON.parse(response);
    				for (i in response){
    					$("#coreDropdown").append("<option value='" + response[i] + "' >" + response[i] + "</option>");
    				}
		
	}
})
//generates random colors for subtotal color coding
function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
///subtotal setup
var numSubtotals = 0;
var current_subtotal = numSubtotals;
var subtotals = {}
$("#addSubtotalButton").on('click', addSubtotal)
subtotalColors = []
//ugly -- kept to make sure deletes work
subtotalElements = {}
var color;

//subtotal management functions
function addSubtotal(){
	//adds a subtotal
	$(".taxon-subtotal").prop('disabled', false);
	numSubtotals +=1;
	current_subtotal = +current_subtotal +1
	config.normalization.subtotals.numSubtotals = numSubtotals
	color = getRandomColor()
	subtotalColors.push(color)
	var textInput = "<input type='text' name='subtotal_" + numSubtotals + "' id='subtotal_" + numSubtotals + "' class='subtotalEntry' size='125'/>";
	var label = "<label for='subtotal_'" + numSubtotals + "' style='color:" + color + "'>Subtotal " + numSubtotals + ": </label>";
	var add = "<div>" + label + textInput + "</div>"
	subtotals[numSubtotals] = [];
	config['normalization']['subtotals'][numSubtotals] = []; 
	subtotalElements[numSubtotals] = [];
	$("#subtotalTextHolder").append(add);
	$(".subtotalEntry").on('click', updateSubtotal);
	$(".subtotal-checkbox").prop('disabled', false)
}

function updateSubtotal(){
	//updates the current subtotal when you click into a new text input
	id=this['id'];
	num1 = id.slice(-1).toString()
	num2 = id.slice(-2, -1).toString();
	if (num2 == "_"){
		num = num1
	}else{
		num = num2 + num1
	}
	current_subtotal = num;
}

function addToSubtotal(){
	//adds a name to the specified subtotal
	var name = $(this).data('name')
	input = this
	var dataNum = $(this).data('number')
	var dataFile = $(this).data('file')
	var parent = $(this).parent()
	checked = input.checked
	st = "#subtotal_" + current_subtotal
	if (checked){
		color = subtotalColors[current_subtotal - 1];
		parent.css('color', color)
		if(!$.isEmptyObject(subtotals)){
			subtotals[current_subtotal].push(name);
			config['normalization']['subtotals'][current_subtotal].push({'file':dataNum, 'number':dataNum, 'name':name})
			subtotalElements[current_subtotal].push($(this))
			current_val = $(st).val()
			new_val= current_val + name + ", "
			$(st).val(new_val)
			
		}
	}else if(!checked){
		parent.css('color', 'black')
		current_val = subtotals[current_subtotal]
		index = subtotals[current_subtotal].indexOf(name)
		subtotals[current_subtotal].splice(index, 1)
		config['normalization']['subtotals'][current_subtotal].splice(index, 1);
		subtotalElements[current_subtotal].splice(index, 1)
		i = 0
		val = ""
		while(i < subtotals[current_subtotal].length){
			val += subtotals[current_subtotal][i] + ", "
			i+=1
		}
		$(st).val(val)
	}
}
function removeSubtotal(){
	var elementToRemove = $("#subtotal_" + numSubtotals).parent();
	elementToRemove.remove();
	var inputElements = subtotalElements[numSubtotals]
	var numElements= inputElements.length
	for (var i=0; i< numElements; i++){
		$(inputElements[i]).prop('checked', false)
	}
	var removeColor = subtotalColors[current_subtotal-1];
	subtotals[numSubtotals] = [];
	config['normalization']['subtotals'][numSubtotals] = [];
	numSubtotals -= 1;
	config.normalization.subtotals.numSubtotals = numSubtotals;
	current_subtotal = numSubtotals
	color = subtotalColors[current_subtotal]
	if (numSubtotals == 0){
		$(".taxon-subtotal").prop('disabled', true);
		$(".subtotal-row").css('color', 'black')
	}
	
}
$("#removeSubtotalButton").on('click', removeSubtotal)


//get a list of taxa from the server for that core
function loadTaxa(){$.ajax({
	url:"scripts/coreTaxa.php",
	type:"POST",
	error:function(error){
		alert("Error gathering data from the server.  Please try again later.");
		console.log("TAXA AJAX ERROR: " + error);
	},
	data:{
		core:$("#coreDropdown").val()
	},
	success: function(response){
		var r = JSON.parse(response);
		if (r.length == 0){
			str = "Please select a core with one or more valid datafiles associated."
			$("#taxaHolder").append(str);
		}
		$("#subtotalTaxaHolder").html("")
		for (var i=0; i<r.length;i++){
			var file = r[i];
			var fileName = file['File'];
			var taxa = file['Taxa']
			var str = "<div class='page-header '>";
			str += "<h4>" + fileName + "</h4>";
			subString = str
			str += "<ul class='checkbox-grid'>";
			subString += "<ul class='checkbox-grid subtotal' >"
			var sumFieldString = "<optgroup label='" + fileName + "'>"
			volumeVolumeString = "<optgroup label='" + fileName + "'>"
			volumeControlString = "<optgroup label='" + fileName + "'>"
			massMassString = "<optgroup label='" + fileName + "'>"
			massControlString = "<optgroup label='" + fileName + "'>"
			massYearString = "<optgroup label='" + fileName + "'>"
			for (var t=1; t<taxa.length; t++){
				taxonName = taxa[t]
				str += "<li class='taxon-row'><input class='taxon-input subtotal-checkbox' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "'><p class='taxon'>" + taxonName + "<p></li>"
				subString += "<li class='subtotal-row'><input class='taxon-subtotal' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "' disabled><p class='subtotalTaxonName'>" + taxonName + "<p></li>"
				sumFieldString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
				volumeVolumeString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
				volumeControlString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
				massMassString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
				massControlString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
				massYearString += "<option value='" + taxonName + "'>" + taxonName + "</option>"
			}
			$("#subtotalTaxaHolder").append(subString + "</ul>" + "</div><br />");
			sumFieldString += "</optgroup>"
			$("#sumFieldDropdown").append(sumFieldString);
			volumeVolumeString += "</optgroup>"
			$("#volumeVolumeSelect").append(volumeVolumeString)
			volumeControlString += "</optgroup>"
			$("#volumeControlSelect").append(volumeControlString)
			massMassString += "</optgroup>"
			$("#massMassSelect").append(massMassString);
			massControlString += "</optgroup>"
			$("#massControlSelect").append(massControlString)
			massYearString = "</optgroup>"
			$("#massYearsSelect").append(massYearString);
			str += "</ul>"
			str+="</div><br />"
			$("#taxaHolder").append(str);	
		}
		//handle apfac and sumtotal loading 
		$('#sumFieldDropdown').change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
    		config.normalization.dataSum['sumField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		$("#noSumInput").change(function(){
			if ($(this).prop('checked')){
				console.log("Checked");
				config.normalization.dataSum.doDataSum = false;
			}else{
				console.log("unchecked");
				config.normalization.dataSum.doDataSum = true;
				
			}
		})
		//apfac --> volume
		$("#volumeVolumeSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
    		config.normalization.apfac['volumeVolumeField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
    		config.normalization.apfac['doVolumeApfac'] = true
		})
		$("#volumeConctrolSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
    		config.normalization.apfac['volumeControlField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
    		config.normalization.apfac['doVolumeApfac'] = true
		})
		$("#massMassSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization.apfac['massMassField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
			config.normalization.apfac['doMassApfac'] = true
		})
		$("#massControlSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization.apfac['massMassField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
			config.normalization.apfac['doMassApfac'] = true
		})
		$("#massYearsSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization.apfac['massYearField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
			config.normalization.apfac['doMassApfac'] = true
		})
		//handle clicks on the taxa checkbox grid
		$(".taxon-input").click(function(){
					var num = $(this).data('number')
					var file = $(this).data('file');
					var name = $(this).data('name');
					var obj = {"name": name, "fileIndex": num, "file": file, "plotIndex": currentIndex, "valuesMatrix": []};
					console.log(obj)
				if ($(this).prop('checked')){
					//push it into UI list
					taxaList.push(name);
					//push it into server side params list
					config['taxa'].push(obj);
					currentIndex +=1;
				}else{
					var index = taxaList.indexOf(name);
					taxaList.splice(index, 1);
					for (var i=0; i< config['taxa'].length; i++){
						var item = config['taxa'][i];
						var n = item['name'];
						var f = item['file'];
						if (n == name && f == file){
							cofnig['taxa'].splice(i, 1);
							currentIndex -= 1;
						}
					}
				}
			})
			$(".taxon-subtotal").on('click', addToSubtotal)
		}
	}
)}

$('#sumSelectGlyph').click(function(){
	$("#selectSum").slideToggle();
	$(this).toggleClass("glyphicon-minus glyphicon-plus")
})

$('#subtotalGlyph').click(function(){
	$("#subtotalDiv").slideToggle();
	$(this).toggleClass("glyphicon-minus glyphicon-plus")
})
$('#apfacGlyph').click(function(){
	$("#apfacDiv").slideToggle();
	$(this).toggleClass("glyphicon-minus glyphicon-plus")
})
$("#noSumInput").change(function(){
	//control the generation of a total of all of the data points
	if ($(this).prop('checked')){
		$("#sumFieldDropdown").prop("disabled", true);
		//graphingParams['useSumField'] = true

	}else{
		$("#sumFieldDropdown").prop('disabled', false);
		//graphingParams['useSumField'] = false;
	}
});




//check if there is a chronology file associated with the core
function checkChronology(){
		$.ajax({
		url:"scripts/checkChronologyStatus.php",
		type:"POST",
		data:{
			coreID:$("#coreDropdown").val()
		},
		success:function(response){
			//disable items if there is no chronology file
			if (response == 0){
				$("#chronAxisInput").prop('disabled', true);
				$("#secondaryAxisShow").prop('disabled', true)
				$("#secondaryAxisHide").prop('disabled', true)
				$('#secondaryAxisText').text("There is no chronology file associated with this dataset.  Some options have been disabled.  If you wish to include a secondary axis, please upload a chronology file for this core.")
				$('#secondaryProps').hide()
				config.hasChronologyFile = false;
			}else{
				//enable if there is a chronolgoy file
				$('#chronAxisInput').prop('disabled', false)
				$("#secondaryAxisShow").prop('disabled', false)
				$("#secondaryAxisHide").prop('disabled', false)
				$('#secondaryAxisText').text("")
				config.hasChronologyFile = true;
			}
			$("input[name=secondaryAxisSelect]").change(function(){
			var checked = $('input[name=secondaryAxisSelect]:checked').val()
			if(checked == 1){
				$("#secondaryProps").show()
			}else{
				$("#secondaryProps").hide()
			}
		})	
		}
	})
}



function showSortableList(taxaList){
	if (taxaList.length > 0){
		for (i in taxaList){
			var name = taxaList[i]
			$("#orderList").append("<li class='list-group-item sortTaxa' >" + name + "</li>")
		}
	}else{
		$("#orderList").html("Please select one or more taxa to graph.")
	}
	$(".sortable").sortable().bind('sortupdate', function(){
		list = $(this);
		var children = list.children();
		for (var i=0; i< children.length; i++){
			child = $(children[i]);
			var name = child.text();
			for (var x=0; x< config['taxa'].length; x++){
				var t = config['taxa'][x];
				var tName = t['name'];
				if (name == tName){
					config['taxa'][x]['plotIndex'] = i;
				}
			}
		}
		//debug
		for (var q=0; q<config['taxa'].length; q++){
			console.log(config['taxa'][q]['name'] + " : " + config['taxa'][q]['plotIndex']);
		}
	})
	
}

//make sure the user selects a core before they can advance
function checkCore(){
	var value = $("#coreDropdown").val()
	if (value == 'null'){
		alert("Please select a valid core name.")
		throw new Error("No Valid Core Selected")
	}
}

//show stratigraphy editor in one the button click


var numtaxa;
function showGraphingOptions(){
	//adds the necessary html elements to the last page of the createplot page
	$("#taxaStylingList").empty();
	//reorder the taxa in the config object so they show up in order
	for (var i=0; i< config['taxa'].length; i++){
		var index = config['taxa'][i]['plotIndex'];
		if (index != i){
			swap(config['taxa'], index, i);
		}
	}
	taxa = config['taxa'];
	numtaxa = taxa.length;
	console.log(numtaxa);
	for (var i=0; i < numtaxa; i++){
		t = taxa[i]
		tName = t['name'];
		tFile = t['file'];
		tPlotIndex = t['plotIndex'];
		console.log(tPlotIndex);
		tString = "<li class='list-group-item'>" 
		tString += "<h4>" + tName + "</h4><strong class='text-muted'>" + tFile + "</strong>"
		tString += "<li class='list-group-item'><ul class='list-group taxa-options-holder' data-name='" + tName + "' data-index='" + tPlotIndex + "'>"
		tString += "<li class='list-group-item taxa-options '>  Curve Label <input type='text' id='tNameField" + i + "'/> <span class='alignRight' id='italics" +i+ "'><input type='checkbox'>Show label in italics</span></li>"
		tString += "<li class='list-group-item taxa-options'>Fill Color  <input id='fill" + i + "' type='color'/></li>"
		tString += "<li class='list-group-item taxa-options'>Outline Color <input id='outline" + i + "' type='color'/></li>"
		tString += "<li class='list-group-item taxa-options'>Normalization Type <select id='normType" + i + "'>"
		tString += "<option val='none'>--None--</option>"
		if (config['normalization']['dataSum']['doDataSum'] == true){
			tString += "<option val='sumField'>Percentage of Sum Field</option>"
		}
		if (config['normalization']['subtotals']['numSubtotals'] > 0){
			for (var q = 0; i< config['normalization']['subtotals']['numSubtotals']; q++){
				tString += "<option val='sub'" + i + "'>Percentage of Subtotal #" + (i+1) + "</option>"
			}
		}
		if (config['normalization']['apfac']['doVolumeApfac']== true){
			tString += "<option val='volumeApfac'>As grains per unit volume </option>"
		}
		if (config['normalization']['apfac']['doMassApfac'] == true){
			tString += "<option val='massApfac'>As accumulation rate</option>"
		}
		tString += "</select></li>"
		tString += "<li class='list-group-item taxa-options'>500% Curve <input id='exagCurve" + i + "' type='checkbox'></li>"
		tString += "<li class='list-group-item taxa-options'>Plot Type <select id='plotType" + i + "'>"
		tString += "<option val='bar'>Bar graph</option>"
		tString += "<option val='curve'>Curve</option></select></li>"
		tString += "<li class='list-group-item taxa-options'>Group<select id='grouping" + i + "'>"
		tString += "<option val='none'>--None--</option>"
		tString += "<option val='herbs'>Herbs</option>"
		tString += "<option val='shrubs'>Shrubs</option>"
		tString += "<option val='trees'>Trees</option>"
		tString += "<option val='aquatics'>Aquatics</option>"
		tString += "<option val='other'>Other</option></select></li>"
		tString += "<li class='list-group-item taxa-options'>  Bottom Label  <input  id='bottom" + i + "' type='text'/></li>"
		tString += "<li class='list-group-item taxa-options'> Plot Index (Zero-based from left): <input type='number' id ='plotIndex" + i + "' value='" + tPlotIndex + "'/></li>"
		tString += "</ul></li>"
		tString += "</ul>"
		$("#taxaStylingList").append(tString);
		//set default value of taxon label
		$("#tNameField" + i).val(tName)
	}
	//event handlers to update config object with properties from form
		$(".btn").click(function(){
			if (page == 9){
				console.log("Gathering curve properties from input forms.")
				//actually submit the config object to the server for graphing
				//gather the properties from the curve styling
				for (var i=0; i< numtaxa; i++){
					curveLabel = $("#tNameField" + i).val();
	;				fill = $("#fill" + i).val();
					outline = $("#outline" + i).val();
					normType = $("#normType" + i).val();
					exagCurve = $("#exagCurve" + i).prop('checked');
					plotType = $("#plotType" + i).val();
					grouping = $("#grouping" + i).val()
					bottomLabel = $("#bottom" + i).val();
					italics = $("#italics" + i).prop('checked');
					plotIndex = $("#plotIndex" + i).val();
					for (var q=0; q< config['taxa'].length; q++){
						//iterate through the config file to find the right place to dump the properties
						var taxon = config['taxa'][q]
						if (taxon.plotIndex == plotIndex){//each taxon has a unique plot index so this should be an okay way to id the properties
							//update properties in the config
							config['taxa'][q]['fill'] = fill;
							config['taxa'][q]['outline'] = outline;
							config['taxa'][q]['topLabelItalics'] = italics;
							config['taxa'][q]['bottomLabel'] = bottomLabel;
							config['taxa'][q]['topLabel'] = curveLabel;
							config['taxa'][q]['show5xCurve'] = exagCurve;
							config['taxa'][q]['grouping'] = grouping;
						}
					}
				}
			}
		})
}
//modal window handlers
//hide remove buttons at first
$("#removeStratLayerButton").hide();
$("#removeZoneButton").hide();
///Stratigraphy handlers
$("#addStratLayerButton").click(function(){
	$("#removeStratLayerButton").show();
	currentStratLayer = config['stratigraphy']['numLayers'];
	s = "<tr id='stratRow" + currentStratLayer + "'><td><input type='text'/></td><td><input type='number'/></td><td><input type='number'/></td>"
	s += "<td><select><option val='0'>--None--</option</select></td>";
	s += "<td><select><option val='0'>--None--</option></select></td></tr>";
	$("#stratTable").append(s);
	config['stratigraphy']['numLayers'] +=1
});

$("#removeStratLayerButton").click(function(){
	config['stratigraphy']['numLayers'] -= 1;
	currentStratLayer = config['stratigraphy']['numLayers'];
	if (currentStratLayer == 0){
		$(this).hide();
	}
	$("#stratRow" + currentStratLayer).remove();
})

//update the stratigraphy config object when the save changes button is clicked
$("#saveStratigraphy").click(function(){
	//iterate through the children of the table and then the children of the rows to get the values
	console.log("Saving stratigraphy to configuration.")
	var list = $("#stratigraphyTable");
	var rows = list.children();
	var numRows = rows.length;
	for (var i=0; i<numRows; i++ ){
		var row = rows[i];
		var rowData = $(row).children();
		var layerLabel = $(rowData[0]).val();
		var layerTop = $(rowData[1]).val();
		var layerBottom = $(rowData[2]).val();
		var layerFill = $(rowData[3]).val();
		var boundaryType = $(rowData[4]).val();
		var layer = {label: layerLabel, top:layerTop, bottom:layerBottom, fill:layerFill, boundary: boundaryType}
		config['stratigraphy']['stratColumn'].push(layer);
	}
})

///zonation handlers
var currentZone = config['zonation']['numZones'];
$("#addZoneButton").click(function(){
	$("#removeZoneButton").show();
	s = "<tr id='zoneRow" + currentZone + "'><td><input type='text'/></td><td><input type='number'/></td><td><input type='number'/></td><td><input type='checkbox'/></td></tr>";
	$("#zoneTable").append(s);
})

$("removeZoneButton").click(function(){
	config['zonation']['numZones'] -= 1;
	currentZone = config['zonation']['numZones'];
	if (currentZone == 0){
		$(this).hide();
	}
	$("#zoneRow" + currentZone).remove();
})
//update the zonation config object when the save changes button is clicked
$("saveZonation").click(function(){
	console.log('Saving zonation to configuration');
	var list = $("#zoneTable");
	var rows = list.children();
	var numRows= rows.length;
	for(var i=0; i< numRows; i++){
		var row = rows[i];
		var rowData = $(row).children();
		var label = $(rowData[0]).val();
		var layerTop = $(rowData[1]).val()
		var layerBottom = $(rowData[2]).val();
		var layerIsSubzone = $(rowData[3]).prop('checked');
		if (layerIsSubzone){
			zoneType = 1; //subzone
		}else{
			zoneType =0 ; //primary zone
		}
		var zone = {label: label, topDepth: layerTop, bottomDepth: layerBottom, zoneType:zoneType}
		config['zonation']['zonation'].push(zone);
	}
})


var fileLookup;
//control what to show at each step
$(".btn").click(function(){
	switch (page){
		case 0://first page --> title plot
			$("#plotTitleDiv").show()
			$("#selectCoreDiv").hide();
			$("#pageTitle").text("Title")
			$("#backButton").prop('disabled', true)
			$("#selectTaxaDiv").hide();
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide()
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").hide();
			$("#extraFeaturesDiv").hide()
			$("#titleMenu").addClass("active")
			$("#coreSelectMenu").removeClass('active list-group-item-success')
			break;
		case 1:
		//select core dropdown menu
			$("#taxaHolder").html("");
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").show();
			$("#pageTitle").text("Select Core")
			$("#titleMenu").addClass('list-group-item-success').removeClass('active')
			$("#backButton").prop('disabled', false)
			$("#selectTaxaDiv").hide();
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide()
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide()
			$("#stylingDiv").hide();
			$("#extraFeaturesDiv").hide()
			$("#coreSelectMenu").addClass("active")
			$("#taxaSelectMenu").removeClass('active list-group-item-success')
			config['title'] = $('#plotTitleInput').val()
			break;
		case 2:
		//select taxa checkbox grid
			checkCore();
			taxaList = []
			$("#taxaHolder").html("");
			loadTaxa();
			$("#selectTaxaDiv").show();
			$('#plotTitleDiv').hide();
			$("#selectCoreDiv").hide();
			$("#orderCurvesDiv").hide()
			$("#pageTitle").text("Select Taxa");
			$("#coreSelectMenu").addClass('list-group-item-success').removeClass('active')
			$("#normalizationDiv").hide()
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").hide();
			$("#extraFeaturesDiv").hide()
			$("#taxaSelectMenu").addClass("active")
			$("#orderCurvesMenu").removeClass('active list-group-item-success')
			config['core'] = $("#coreDropdown").val()
			break
		case 3:
		//curve order sortable list
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#pageTitle").text("Order Taxa");
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").show()
			$("#orderList").html("")
			showSortableList(taxaList)
			$("#taxaSelectMenu").addClass('list-group-item-success').removeClass('active')
			$("#normalizationDiv").hide()
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").hide();
			$("#extraFeaturesDiv").hide()
			$("#orderCurvesMenu").addClass("active")
			$("#normalizationMenu").removeClass('active list-group-item-success')
			//taxa are added and removed in the loadTaxa function
			//csv files are parsed and values are added to the matrix in the config 
			//get a list of the datafiles in use
			var filesList = [];
			var numTaxa = config['taxa'].length;
				//for all taxa, find what file the values are stored in
				//different taxa may be in the same file, but all taxa must be contained in one file
				//in addition to the file, we want to know what index to lookup from, so we store an array of file
				fileLookup = {}; //object of arrays
				for (var i=0; i< numTaxa; i++){
					//first iterate and find the files we need
					var taxon = config['taxa'][i]
					var configFile = taxon['file']
					fileLookup[configFile] = [];
				}
				for (var i=0; i<numTaxa; i++){
					//now populate the arrays with the file indeces that we need
					var taxon = config['taxa'][i]
					var configFile = taxon['file']
					var fileIndex = taxon['fileIndex'] 
					fileLookup[configFile].push(fileIndex);
				}
			filesList = Object.keys(fileLookup)
			///get the file names via the database
			console.log(filesList);
			$.ajax({
				url: "../scripts/fileLookup.php",
				method:"GET",
				data: {
					files: filesList,
					core: config['core'],
				},
				contentType: "json",
				success: function(response){
					//response should contain min/max depths for the selected files and file references
					response = JSON.parse(response);
					if (response['Success'] == "True"){
						//update min/maxes
						config['axes']['minDepth'] = response['MinDepth'];
						config['axes']['maxDepth'] = response['MaxDepth'];
						responseList = response['fileLookup'];
						parseFiles(responseList);
					}else{
						alert("Server Error.  Check log for details.");
						console.log(response['ErrorMessage'])
					}
				},
				error:function(){
					alert("Error!")
				},
				beforeSend: function(){
					console.log("Preparing to obtain datafiles from server.")
				}
			})
			function parseFiles(fList){
				console.log("Now Parsing Files, this is fList: ")
				for (i in fList){
					//iterate through files and parse them 
					//names shouldbe consistent between fList and fileLookup objects so we can cross reference
					var fObject = fList[i];
					var fName = fObject['Name']; //file name
					var fFile = fObject['File']; //actual file
					var indeces = fileLookup[fName]; //indeces for the taxa in the file
					console.log(indeces);
					f = Papa.parse(fFile, {
						download: true,
						worker: true,
						header: true,
						complete: function(parsedFile){
							var data = parsedFile['data'];
							var numLevels = data.length; 
							for (var x=0; x< indeces.length; x++){
								//iterate through the taxa in this file
								index = indeces[x];
								for(var q=0; q< config['taxa'].length; q++){
									//put the values into the right place in the config file
									configTaxon = config['taxa'][q];
									if ((configTaxon['file'] == fName) && (configTaxon['fileIndex'] == index)){
										values = [];
										for (var w=0; w<numLevels; w++){
											//iterate through the levels and collect the values
											var level = data[w];
											var val = level[x];
											if (level['depth'] != undefined){
												var depth = level['depth'];
											}else if (level['Depth'] != undefined){
												var depth = level['Depth'];
											}else{
												throw "No depth column found.  Please refer to software manual."
											}
											console.log(val)
											values.push({depth: depth, value: val});
											config['taxa'][q]['valuesMatrix'] = values;
										}
									}
								}
							}							
						}
					})
					
				}
			};
			
			break;
		case 4:
		//data normalization options --> total sum generation, subtotals, apfac
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").hide()
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").hide();
			$("#pageTitle").text("Normalization Calculations");
			$("#orderCurvesMenu").addClass('list-group-item-success').removeClass('active')
			$("#normalizationDiv").show();
			$("#extraFeaturesDiv").hide()
			$("#normalizationMenu").addClass("active")
			$("#dimensionsMenu").removeClass('active list-group-item-success')
			//get the order correct
			var numTaxaInList = $('#orderList').length
			var lst = $("#orderList")
			break;
		case 5:
		//plot dimensions
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide();
			$("#axesDiv").hide();
			$("#stylingDiv").hide();
			$("#pageTitle").text("Plot Dimensions");
			$("#dimensionsDiv").show()
			$("#normalizationMenu").addClass('list-group-item-success').removeClass('active')
			$("#extraFeaturesDiv").hide()
			$("#dimensionsMenu").addClass("active")
			break
			
		case 6:
		//axes --> primary axis select, secondary axis options, axis titles
			checkChronology()
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide();
			$("#dimensionsDiv").hide()
			$("#dimensionsMenu").addClass('list-group-item-success').removeClass('active')
			$("#pageTitle").text("Axes")
			$("#axesDiv").show();
			$("#stylingDiv").hide();
			$("#extraFeaturesDiv").hide()
			$("#axesMenu").addClass("active")
			$("#extraMenu").removeClass('active list-group-item-success')
			//get plot dimensions from previous page
			var height = $("input[name=page-size]:checked").attr('height')
			var width = $("input[name=page-size]:checked").attr('width')
			var units = $("input[name=page-size]:checked").attr('units')
			//convert to px using 72 px per inch and 28 px per cm
			if (units == 'cm'){
				heightPX = height * 28;
				widthPX = width * 28;
			}else if (units == 'in'){
				heightPX = height * 72;
				widthPX = width * 72;
			}else{
				widthPX = -1;
				heightPX = -1;
			}
			config['plotWidth'] = widthPX;
			config['plotHeight'] = heightPX;
			break
		case 7:
		//extra features to be implemented as time allows
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide();
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").hide()
			$("#extraFeaturesDiv").show();
			$("#axesMenu").addClass('list-group-item-success').removeClass('active')
			$("#pageTitle").text("Extra Features")
			$("#extraMenu").addClass("active")
			$("#nextButton").html("Next <span class='glyphicon glyphicon-menu-right'></span>").removeClass('btn-success').addClass('btn-primary')
			$("#stylingMenu").removeClass('active list-group-item-success')
			//get axis options from previous page
			var primaryAxisCat = $("input[name=primaryAxisSelect]:checked").val();
			var showSecondaryAxis = $("input[name=secondaryAxisSelect]:checked").val();
			var primaryAxisTitle = $("#primaryAxisTitleInput").val();
			var primaryAxisUnits = $("#primaryAxisUnitsInput").val();
			config['axes']['primaryAxisCategory'] = primaryAxisCat;
			config['axes']['primaryAxisTitle'] = primaryAxisTitle;
			config['axes']['primaryAxisUnits'] = primaryAxisUnits;
			config['axes']['showSecondaryAxis'] = showSecondaryAxis;
			if (showSecondaryAxis == 'true'){
				var secondaryAxisTitle = $("#SecondaryAxisTitleInput").val()
				var secondaryAxisUnits = $("#SecondaryAxisUnitsInput").val()
				config['axes']['secondaryAxisTitle'] = secondaryAxisTitle;
				config['axes']['secondaryAxisUnits'] = secondaryAxisUnits;
			}
			break
			
		case 8:
		//curve styling options
			$("#plotTitleDiv").hide()
			$("#selectCoreDiv").hide()
			$("#selectTaxaDiv").hide()
			$("#orderCurvesDiv").hide()
			$("#normalizationDiv").hide();
			$("#dimensionsDiv").hide()
			$("#axesDiv").hide();
			$("#stylingDiv").show();
			$("#pageTitle").text("Curve Styling")
			$("#extraMenu").addClass('list-group-item-success').removeClass('active')
			$("#extraFeaturesDiv").hide()
			$("#stylingMenu").addClass("active")
			$("#nextButton").html("Plot <span class='glyphicon glyphicon-send'></span>").removeClass('btn-primary').addClass('btn-success')
			showGraphingOptions();
			break
		case 9:
			var now = new Date();
			var nowString = now.getFullYear() + "-" + now.getMonth() + "-" + now.getDay() + ' ' + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
			config.createdAt = nowString;
			config.lastDrawn = nowString;
			var c = JSON.stringify(config)
			$.ajax({
				url:"scripts/saveDiagramConfig.php",
				type:"POST",
				error:function(error){
					alert("Error gathering data from the server.  Please try again later.");
					console.log("TAXA AJAX ERROR: " + error);
				},
				data:{
					config: c,
					core: config['core']
				},
				success: function(response){
					r = JSON.parse(response);
					if (r['success'] == 'true'){
						console.log(response);
						user = r['user'];
						core = r['core'];
						t = r['timestamp'];
						//pass the correct params to the url so we can fetch the right cpn file.
						url = "drawDiagram.php?user=" + user + "&core=" + core + "&creationTime=" + t
						window.location.replace(url);
					}
					if (r['error'] == 'true'){
						alert(r['message'])
					}
				}
			})

	}
})
