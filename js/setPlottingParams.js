config = {
	title = "", //title of plot to be displayed
	core = "", //core identifier that holds the datasets
	//normalization properties
	
	normalization: {
		dataSum: { // controls whether or not to make a sum field available.  Must be precalculated by the user
			doDataSum:false,
			sumField = { //where to get the values for the data sum
				file = "",
				fileIndex = -1,
				fieldName = "",
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
			massYearField: {file: "", fileIndex: "", fieldName: ""},. //field with number of years in sample for accumulation apfac
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
	taxa = [ //starts as empty array and is filled with n objects using the scheme below
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