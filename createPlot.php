<?php
if (isset($GET['core'])){
	$core = $GET['core'];
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
            <li><a href="createPlot.html">Create Plot</a></li>
            <li><a href="savedProjects.html">Saved Projects</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="cgi-bin/logout.php">Logout</a></li>
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

    <div class="container ">
    	<div class='row'>
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
        		<ul class='list-group col-sm-10'>
        			<li class='list-group-item' id='selectSumLi'>
        				Total Field    <span class='glyphicon glyphicon-plus' id='sumSelectGlyph'></span>
        				<ul id='selectSum' class='list-group'>
        					<li class='list-group-item'>Do not make a sum field available.  <input type='checkbox' value='0' name='sumFieldInput' id='noSumInput'/></li>
        					<li class='list-group-item'>Use this field: <select id='sumFieldDropdown'></select></li>
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
        				APFAC     <span class='glyphicon glyphicon-plus' id='0
        					
        					<ul class='list-group  row'>
        						<li class='list-group-item'>
        							<strong><p>Grains per cm<sup>3</sup></p></strong>
        							<ul class='list-group'>
        								<li class='list-group-item'>Sample Volume Field:  <select id='volumeVolumeSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Control Count Field:  <select id='volumeControlSelect'><option value='null'>--None--</option></select></li>
        							</ul>
        						</li>
        					</ul> 
        					<ul class='list-group row'>
        						<li class='list-group-item'>
        							<strong><p>Accumulation Rate</p></strong>
        							<ul class='list-group'>
        								<li class='list-group-item'>Sample Mass Field:  <select id='massMassSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Control Count Field:  <select id='massControlSelect'><option value='null'>--None--</option></select></li>
        								<li class='list-group-item'>Years in Sample Field:  <select id='massYearsSelect'><option value='null'>--None--</option></select></li>
        							</ul>
        						</li>
        					</ul> 
        					
        				</div>
        			</li>
        			
        		</ul>
        	</div>
        	
        	<div class='row' id='dimensionsDiv'>
        		<i>Select the dimensions of the output plot.</i>
        		<ul class='list-group col-sm-8'>
        			<strong>Standard Sizes:</strong>
        			<li class='list-group-item'><input type='radio' value='0' id='standardDim1' name='page-size' width='2' height='4' units= 'in' class='dim'/>  2 x 4</li>
        			<li class='list-group-item'><input type='radio' value='1' id='standardDim2' name='page-size' width= '4' height='4' units= 'cm' class='dim'/>  4 x 6</li>
        			<li class='list-group-item'><input type='radio' value='2' id='standardDim3' name='page-size' width= '8' height='4' units= 'cm' class='dim'/>  8 x 12</li>
        			<li class='list-group-item'><input type='radio' value='3' id='standardDim4' name='page-size' width= '5' height='4' units= 'in' class='dim' />  5 x 10</li>
        			<li class='list-group-item'><input type='radio' value='4' id='standardDim5' name='page-size' width= '8.5' height='4' units= 'cm' class='dim'/>  8.5 x 11</li>
        		</ul>
        		<br />
        		<ul class='list-group col-sm-8' id='customPlotDims'>
        			<strong>Custom Size:</strong>
        			<li class='list-group-item'>Width (inches): <input type='text' name='customWidth' id='customWidthInput'/></li>
        			<li class='list-group-item'>Height (inches): <input type='text' name='customHeight' id='customHeightInput'/></li>
        		</ul>
        	</div>
        	
        	<div class='row' id='axesDiv'>
        		<i>Specify how you would like the axes to appear on your plot.</i>
        		<ul class='list-group col-sm-8'>
        			<strong>Primary Axis</strong>
        			<li class='list-group-item'><input type='radio' value='Depth' id='depthAxisInput' name='primaryAxisSelect'/>   Depth</li>
        			<li class='list-group-item'><input type='radio' value='Time' id='chronAxisInput' name='primaryAxisSelect'/>   Time</li>
        		</ul>
        		<br />
        		<ul class='list-group col-sm-8'>
        			<strong>Secondary Axis</strong>
        			<li class='list-group-item'><input type='radio' value='true' id='secondaryAxisShow' name='secondaryAxisSelect' />  Show Secondary Axis</li>
        			<li class='list-group-item'><input type='radio' value='false' id='secondaryAxisHide' name='secondaryAxisSelect'checked/>  Don't Show Secondary Axis</li>
        		</ul>
        		<br />
        		<ul class='list-group col-sm-8'>
        			<strong>Primary Axis Properties</strong>
        			<li class='list-group-item'>
        				Title for Primary Axis: <input type='text' name='primaryAxisTitleInput' id='primaryAxisTitleInput'/></li>
        			
        			<li class='list-group-item'>Units for Primary Axis: <input type='text' name='primaryAxisUnitsInput' id='primaryAxisUnitsInput'/></li>
        		</ul>
        		<br />
        		<ul class='list-group col-sm-8' id='secondaryProps'>
        			<strong>Secondary Axis Properties</strong>
        			<li class='list-group-item'>Title for Secondary Axis: <input type='text' name='SecondaryAxisTitleInput' id='SecondaryAxisTitleInput'/></li>
        			<li class='list-group-item'>Units for Secondary Axis: <input type='text' name='SecondaryAxisUnitsInput' id='SecondaryAxisUnitsInput'/></li>
        		</ul>
        		<div id='secondaryAxisText'>
        			
        		</div>
        	</div>
        	
        	<div class='row' id='extraFeaturesDiv'>
        		<ul class="list-group col-sm-8">
        			<strong>Stratigraphy Column</strong>
        			<li class='list-group-item'><input type='radio' value= '1' name='showStratigraphy' id='showStratigraphyInput'/>  Show stratigraphy column </li>
        			<li class='list-group-item'><input type='radio' value= '0' name='showStratigraphy' id='hideStratigraphyInput'/>  Don't show stratigraphy column </li>
        			<li class='list-group-item'><button id='createStratDiagramButton' class='btn btn-primary' data-toggle='modal' data-target='#stratigraphy-modal'>Build Stratigraphy Diagram</button></li>
        			<div class='modal fade' id='stratigraphy-modal' tabindex="-1" role='dialog' aria-hide='true'>
        				<div class='modal-dialog'>
        					<div class='modal-content'>
        						<div class='modal-header'>
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class='modal-title'>Stratigraphy Column Editor</h4>
        							<div class='modal-body'>
        								<i>This is a placeholder to do things later.</i>
        							</div>
        							<div class='modal-footer'>
        								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        								<button type="button" class="btn btn-primary">Save changes</button>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</ul>
        		<br />
        		<ul class="list-group col-sm-8">
        			<strong>Dendrogram</strong>
        			<li class='list-group-item'><input type='radio' value= '1' name='showDendrogram' id='showDendrogramInput'/>  Show dendrogram element</li>
        			<li class='list-group-item'><input type='radio' value= '0' name='showDendrogram' id='hideDendrogramInput'/>  Don't show dendrogram element </li>
        		</ul>
        	</div>
        	
        	
        	<div class='row' id='stylingDiv'>
        		<i>Specify how you would like each curve to appear.</i>
        		
        	</div>
        	
        	</div>
        </div>	

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

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/createPlotClient.js"></script>
    <script src='js/jquery.sortable.min.js'></script>

	
  </body>
</html>
