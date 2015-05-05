<?php
if (isset($GET['core'])){
	$core = $GET['core'];
}
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
  	#sidebar{
  		margin-top: 3%;
  	}
  	#topBar{
  		margin-top:1%;
  	}
  	.btn-right{
  		float:right;
  	}
  	.btn-left{
  		float:left;
  	}
  	#coreDropdown{
  		width: 300px;
  	}
  	.checkbox-grid li {
  		margin-top:1%;
    	display: block;
    	float: left;
    	width: 25%;
	}
	#subtotalTaxaHolder{
		height: 500px !important;
		overflow-y:scroll;
		overflow-x:hidden;
		border-top:#ffffff thin;
	}
	.taxon-row{
		 box-shadow: 5px 5px 2px #C7C7C7;
	}
	.alignRight{
		float: right;
	}
	table{
		border:#ffffff;
	}
	.modal-content{
		width: 120% !important;
	}
	.fileHeader{
		padding-top: 5%;
		margin-top: 5%;
	}

  	</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Plot</title>
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
            <li><a href="savedProjects.php">Saved Projects</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          	<li><a href='tickets.php'>Ticket Center</a></li>
            <li><a href="scripts/logout.php">Logout</a></li>
          </ul>
      </div>
    </nav>

    <div id='sidebar'>
    	<ul class='list-group col-sm-2'>
    		<li class='list-group-item' id='titleMenu'>Plot Title</li>
    		<li class='list-group-item' id='coreSelectMenu'>Select Core</li>
    		<li class='list-group-item' id='taxaSelectMenu'>Select Taxa</li>
    		<li class='list-group-item' id='orderCurvesMenu'>Curve Order</li>
    		<li class='list-group-item' id='normalizationMenu'>Data Normalization</li>
    		<li class='list-group-item' id='dimensionsMenu'>Plot Dimensions</li>
    		<li class='list-group-item' id='axesMenu'>Axes</li>
    		<li class='list-group-item' id='extraMenu'>Extra Features</li>
    		<li class='list-group-item' id='stylingMenu'>Curve Styling</li>
    	</ul>
    </div>

    <div class="container">
    	<div class="col-xs-10">
    	    <div class='row' id='topBar' align="center">
        		<button type='button' id='backButton' class='btn btn-primary btn-large btn-left'><span class='glyphicon glyphicon-menu-left'></span>Back</button><button id='nextButton' type='button' class='btn btn-primary btn-large btn-right'>Next<span class='glyphicon glyphicon-menu-right'></span></button>
        		<h2 id='pageTitle'>Title</h2>
        	
        	<div class='row' id='plotTitleDiv'>
        		<i>This is the title that will be printed at the top of your plot.</i><br />
        		<label for='plotTitle'>Plot Title:   </label><input type='text' size='100' name='plotTitle' id='plotTitleInput'/><br />
        		
        	</div>
        	<div class='row' id='selectCoreDiv'>
        		<i>Select the core entity that you wish to graph with.</i><br />
        		<label for='selectCore'>Select Core:</label>
        		<select id='coreDropdown'>
        			<option value="null">--Select--</option>
        		</select><br />
        	</div>
        	<div class='row' id='selectTaxaDiv'>
        		<i>Select the taxa you wish to include on your plot.</i>
        		<div id='taxaHolder'>
        			
        		</div>
        	</div>
        	<div class='row' id='orderCurvesDiv'>
        		<i>Select the order in which the curves should be plotted.  </i>
        		<ul class='sortable list-inline' id='orderList'>
        		</ul>
        	</div>
        	
        	<div class='row' id='normalizationDiv'>
        		<i>Select the normalizations you would like to calculate.</i>
        		<ul class='list-group'>
        			<li class='list-group-item' id='selectSumLi'>
        				Total Field    <span class='glyphicon glyphicon-plus' id='sumSelectGlyph'></span>
        				<ul id='selectSum' class='list-group'>
        					<li class='list-group-item'>Do not make a sum field available.  <input type='checkbox' value='0' name='sumFieldInput' id='noSumInput' checked/></li>
        					<li class='list-group-item'>Use this field: <select id='sumFieldDropdown'><option val='none'>--None--</option></select></li>
        				</ul>
        			</li>
        			<li class='list-group-item' id='subtotalLi'>
        				Subtotals    <span class='glyphicon glyphicon-plus' id='subtotalGlyph'></span>
        				<div id='subtotalDiv'>
        					<button type='button' class='btn btn-primary' id='addSubtotalButton'>Add Subtotal</button>
        					<button type='button' class='btn btn-alert' id='removeSubtotalButton'>Remove Subtotal</button>
        					<p>Select the taxa to include in the subtotal</p>
        					<ul class='checkbox-grid' id='subtotalTaxaHolder'>
        					</ul>
        					<div id='subtotalTextHolder'>
        						
        					</div>
        				</div>
        			</li>
        			<li class='list-group-item' id='apfacLi'>
        				Pollen Accumulation Rate<span class='glyphicon glyphicon-plus' id='apfacGlyph'></span>
        				<div id='apfacDiv'>
        					<ul class='list-group row'>
        						<li class='list-group-item'>
        							<ul class='list-group'>
        								<li class='list-group-item'>Controls Counted:  <select id='apfacControlCountSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Controls Added:  <select id='apfacTotalControlSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Years in Sample:  <select id='apfacYearsSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Sample Vertical Thickness:  <select id='apfacThickSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Sample Volume:  <select id='apfacVolumeSelect'><option value='null'>--None--</option></select></li>
        							</ul>
        						</li>
        					</ul>
        				</div>	
        				</div>
        			</li>
        			
        		</ul>
        	</div>
        	
        	<div class='row' id='dimensionsDiv'>
        		<i>Select the dimensions of the output plot.</i>
        		<ul class='list-group'>
        			<strong>Standard Sizes:</strong>
        			<li class='list-group-item'><input type='radio' value='0' id='standardDim1' name='page-size' width='2' height='4' units= 'in' class='dim' />  2 x 4</li>
        			<li class='list-group-item'><input type='radio' value='1' id='standardDim2' name='page-size' width= '4' height='4' units= 'cm' class='dim'/>  4 x 6</li>
        			<li class='list-group-item'><input type='radio' value='2' id='standardDim3' name='page-size' width= '8' height='4' units= 'cm' class='dim'/>  8 x 12</li>
        			<li class='list-group-item'><input type='radio' value='3' id='standardDim4' name='page-size' width= '5' height='4' units= 'in' class='dim' />  5 x 10</li>
        			<li class='list-group-item'><input type='radio' value='4' id='standardDim5' name='page-size' width= '8.5' height='4' units= 'in' class='dim'/>  8.5 x 11</li>
        			<li class='list-group-item'><input type='radio' value='5' id='standardDim6' name='page-size' width='page' height='page' units='in' class='dim' checked/> Fit to screen</li>
        		</ul>
        		<br />
        		<ul class='list-group' id='customPlotDims'>
        			<strong>Custom Size:</strong>
        			<li class='list-group-item'>Width (inches): <input type='text' name='customWidth' id='customWidthInput'/></li>
        			<li class='list-group-item'>Height (inches): <input type='text' name='customHeight' id='customHeightInput'/></li>
        		</ul>
        	</div>
        	
        	<div class='row' id='axesDiv'>
        		<i>Specify how you would like the axes to appear on your plot.</i>
        		<ul class='list-group'>
        			<strong>Primary Axis</strong>
        			<li class='list-group-item'><input type='radio' value='Depth' id='depthAxisInput' name='primaryAxisSelect' checked/>   Depth</li>
        			<li class='list-group-item'><input type='radio' value='Time' id='chronAxisInput' name='primaryAxisSelect'/>   Time</li>
        		</ul>
        		<br />
        		<ul class='list-group'>
        			<strong>Secondary Axis</strong>
        			<li class='list-group-item'><input type='radio' value='true' id='secondaryAxisShow' name='secondaryAxisSelect' />  Show Secondary Axis</li>
        			<li class='list-group-item'><input type='radio' value='false' id='secondaryAxisHide' name='secondaryAxisSelect'checked/>  Don't Show Secondary Axis</li>
        		</ul>
        		<br />
        		<ul class='list-group '>
        			<strong>Primary Axis Properties</strong>
        			<li class='list-group-item'>
        				Title for Primary Axis: <input type='text' name='primaryAxisTitleInput' id='primaryAxisTitleInput'/></li>
        			
        			<li class='list-group-item'>Units for Primary Axis: <input type='text' name='primaryAxisUnitsInput' id='primaryAxisUnitsInput'/></li>
        		</ul>
        		<br />
        		<ul class='list-group' id='secondaryProps'>
        			<strong>Secondary Axis Properties</strong>
        			<li class='list-group-item'>Title for Secondary Axis: <input type='text' name='SecondaryAxisTitleInput' id='SecondaryAxisTitleInput'/></li>
        			<li class='list-group-item'>Units for Secondary Axis: <input type='text' name='SecondaryAxisUnitsInput' id='SecondaryAxisUnitsInput'/></li>
        		</ul>
        		<div id='secondaryAxisText'>
        			
        		</div>
        	</div>
        	
        	<div class='row' id='extraFeaturesDiv'>
        		<ul class="list-group ">
        			<strong>Stratigraphy Column</strong>
        			<li class='list-group-item'><input type='radio' value= 'true' name='showStratigraphy' id='showStratigraphyInput'/>  Show stratigraphy column </li>
        			<li class='list-group-item'><input type='radio' value= 'false' name='showStratigraphy' id='hideStratigraphyInput' checked/>  Do not show stratigraphy column </li>
        			<li class='list-group-item'><button id='createStratDiagramButton' class='btn btn-primary' data-toggle='modal' data-target='#stratigraphy-modal'>Build Stratigraphy Diagram</button></li>
        			 <div class='modal fade' id='stratigraphy-modal' tabindex="-1" role='dialog' aria-hide='true'>
        				<div class='modal-dialog'>
        					<div class='modal-content'>
        						<div class='modal-header'>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class='modal-title'>Stratigraphy Column Editor</h4>
        							<div class='modal-body'>
        								<p>Specify the upper and lower bounds and fill types of the layers you would like to include in the stratigraphy column.</p>
										<table class='modal-table' id='stratTable'>
        									<thead><th>Zone Label</th><th>Upper Boundary</th><th>Lower Boundary</th><th>Layer Fill</th><th>Boundary Type</th></thead>
        									
        								</table>
        								
        								<button class='btn btn-success' id='addStratLayerButton'>Add Zone<span class='gyphicon glyphicon-plus'></span></button>
        								<button class='btn btn-danger' id='removeStratLayerButton'>Remove Zone <span class='glyphicon glyphicon-minus'></span></button>
        							</div>
        							<div class='modal-footer'>
        								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        								<button type="button" class="btn btn-primary" id='saveStratigraphy' data-dismiss='modal'>Save changes</button>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</ul>
        		<br />
        		
        		<ul class="list-group ">
        			<strong>Diagram Zonation</strong>
        			<li class='list-group-item'><input type='radio' value= 'true' name='showZonation' id='showZonationInput'/>  Enable Zonation </li>
        			<li class='list-group-item'><input type='radio' value= 'false' name='showZonation' id='hideZonationInput' checked/>  Do Not Enable Zonation</li>
        			<li class='list-group-item'><button id='createZonationButton' class='btn btn-primary' data-toggle='modal' data-target='#zonation-modal'>Open Zonation Editor</button></li>
        			<div class='modal fade' id='zonation-modal' tabindex="-1" role='dialog' aria-hide='true'>
        				<div class='modal-dialog'>
        					<div class='modal-content'>
        						<div class='modal-header'>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class='modal-title'>Manual Zonation Editor</h4>
        							<div class='modal-body'>
        								<p>Specify the zonation parameters to be drawn on the diagram.  Zone labels will be plotted in the center of the upper and lower bounds on the diagram. Please ensure that zones do not overlap with each other, or unexpected grahpical results may occur.</p>
        								<table class='modal-table' id='zoneTable'>
        									<thead><th>Zone Label</th><th>Upper Boundary</th><th>Lower Boundary</th><th>Subzone?</th></thead>
        								</table>
        								
        								<button class='btn btn-success' id='addZoneButton'>Add Zone<span class='gyphicon glyphicon-plus'></span></button>
        								<button class='btn btn-danger' id='removeZoneButton'>Remove Zone <span class='glyphicon glyphicon-minus'></span></button>
        							</div>
        							<div class='modal-footer'>
        								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        								<button type="button" class="btn btn-primary" id='saveZonation' data-dismiss='modal'>Save changes</button>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</ul>
        	</div>
        	
        	
        	<div class='row' id='stylingDiv'>
        		<i>Specify how you would like each curve to appear.</i>
        		<ul class='list-group' id='taxaStylingList'>
        			
        		</ul>
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
    <script src="js/papaparse.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src='js/jquery.sortable.min.js'></script>
    <script src="js/createPlotClient.js"></script>
    

	
  </body>
</html>
