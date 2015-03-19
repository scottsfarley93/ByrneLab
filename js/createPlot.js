//keep track of variables for the plot
//taxaList is for client side
var taxaList = []

//graphingParams is for server side
var graphingParams = {}
graphingParams['subtotals'] = []

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


//get the list of cores from the server
$.ajax({
	url:"cgi-bin/populateCores.php",
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
	graphingParams['subtotals'][numSubtotals] = []; 
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
			graphingParams['subtotals'][current_subtotal].push({'file':dataNum, 'number':dataNum, 'name':name})
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
		graphingParams['subtotals'][current_subtotal].splice(index, 1);
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
	graphingParams['subtotals'][numSubtotals] = [];
	numSubtotals -= 1;
	current_subtotal = numSubtotals
	color = subtotalColors[current_subtotal]
	
}
$("#removeSubtotalButton").on('click', removeSubtotal)


//get a list of taxa from the server for that core
function loadTaxa(){$.ajax({
	url:"cgi-bin/coreTaxa.php",
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
			for (var t=1; t<taxa.length; t++){
				taxonName = taxa[t]
				str += "<li class='taxon-row'><input class='taxon-input' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "'><p class='taxon'>" + taxonName + "<p></li>"
				subString += "<li class='subtotal-row'><input class='taxon-subtotal' data-name='" + taxonName + "' type='checkbox' data-number='" + t + "' data-file='" + fileName +  "'><p class='subtotalTaxonName'>" + taxonName + "<p></li>"
				$("#sumFieldDropdown").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
				$("#volumeVolumeSelect").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
				$("#volumeControlSelect").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
				$("#massMassSelect").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
				$("#massControlSelect").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
				$("#massYearsSelect").append("<option value='" + taxonName + "'>" + taxonName + "</option>");
			}
			str += "</ul>"
			str+="</div><br />"
			$("#taxaHolder").append(str);
			$("#subtotalTaxaHolder").append(subString + "</ul>" + "</div><br />");
			
		}
		$(".taxon-input").click(function(){
				
					var num = $(this).data('number')
					var file = $(this).data('file');
					var name = $(this).data('name');
					var obj = {"Name": name, "Index": num, "File": file}
				if ($(this).prop('checked')){
					//push it into UI list
					taxaList.push(name);
					//push it into server side params list
					graphingParams.push(obj);
				}else{
					var index = taxaList.indexOf(name);
					taxaList.splice(index, 1);
					for (i in graphingParams){
						if (graphingParams['name'] == name){
							graphingParams.splice(i, 1);
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



//check if there is a chronology file associated with the core
function checkChronology(){
		$.ajax({
		url:"cgi-bin/checkChronologyStatus.php",
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
			$("#orderList").append("<li class='list-group-item' >" + name + "</li>")
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
			break;
		case 2:
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
			break
		case 3:
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
			break;
		case 4:
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
			break;
		case 5:
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
			$("#axesMenu").removeClass('active list-group-item-success')
			break
			
		case 6:
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
			break
		case 7:
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
			break
			
		case 8:
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
			break

	}
})
