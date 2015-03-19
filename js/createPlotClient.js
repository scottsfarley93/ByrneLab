
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
	},
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
		}
		*/
	],
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
$("#customPlotDims").hide()


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

//subtotal management functions
function addSubtotal(){
	//adds a subtotal
	numSubtotals +=1;
	current_subtotal = +current_subtotal +1
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
	current_subtotal = numSubtotals
	color = subtotalColors[current_subtotal]
	
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
				str += "<li class='taxon-row'><input class='taxon-input' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "'><p class='taxon'>" + taxonName + "<p></li>"
				subString += "<li class='subtotal-row'><input class='taxon-subtotal' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "'><p class='subtotalTaxonName'>" + taxonName + "<p></li>"
				//$("#sumFieldDropdown").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
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
    		config.normalization['sumField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		//apfac --> volume
		$("#volumeVolumeSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
    		config.normalization['volumeVolumeField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		$("#volumeConctrolSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
    		config.normalization['volumeControlField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		$("#massMassSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization['massMassField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		$("#massControlSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization['massMassField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		$("#massYearsSelect").change(function(){
			var selected = $(':selected', this);
			var index = selected.index();
    		s = selected.closest('optgroup').attr('label')
			config.normalization['massYearField'] = {file:s, fieldName: $(this).val(), fileIndex: index};
		})
		//handle clicks on the taxa checkbox grid
		$(".taxon-input").click(function(){
					var num = $(this).data('number')
					var file = $(this).data('file');
					var name = $(this).data('name');
					var obj = {"name": name, "fileIndex": num, "file": file}
				if ($(this).prop('checked')){
					//push it into UI list
					taxaList.push(name);
					//push it into server side params list
					config['taxa'].push(obj);
				}else{
					var index = taxaList.indexOf(name);
					taxaList.splice(index, 1);
					for (var i=0; i< config['taxa'].length; i++){
						var item = config['taxa'][i];
						var n = item['name'];
						var f = item['file'];
						if (n == name && f == file){
							cofnig['taxa'].splice(i, 1);
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
				('#secondaryProps').hide()
			}else{
				$('#chronAxisInput').prop('disabled', false)
				$("#secondaryAxisShow").prop('disabled', false)
				$("#secondaryAxisHide").prop('disabled', false)
				$('#secondaryAxisText').text("")
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
	
	$(".sortable").sortable();
}

//make sure the user selects a core before they can advance
function checkCore(){
	var value = $("#coreDropdown").val()
	if (value == 'null'){
		alert("Please select a valid core name.")
		throw new Error("No Valid Core Selected")
	}
}

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
			$(".sortable").each(function(index){
				name = $(this).text();
				for (var i=0; i< config['taxa'].length; i++){
					var tName = config['taxa']['name'];
					if (name == tName){
						config['taxa']['plotIndex'] = index;
					}
				}
			})
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
			alert("Please note that these features are not yet available and neither the dendrogram nor the stratigraphy column will appear on your diagram. ")
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
			console.log(config)
			break

	}
})
