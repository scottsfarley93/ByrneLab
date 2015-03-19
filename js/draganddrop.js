$("#fileProperties").hide();
//drang and drop events
var holder = document.getElementById('draganddrop'),
    state = document.getElementById('status');

if (typeof window.FileReader === 'undefined') {
  state.innerHTML = 'File reader is not supported by your browser, please use the upload button instead.';
}
holder.ondragenter = function(){$(this).toggleClass("hover"); $(this).toggleClass("nonhover")}
holder.ondragleave = function(){$(this).toggleClass("hover"); $(this).toggleClass("nonhover")}
holder.ondragover = function () { return false; };
holder.ondragend = function () {return false; };
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
var uploadFile;

//handle files
function handleFile(file){
	uploadFile = file;
	$("#fileTaxa").empty();
	var modifiedDate = file['lastModifiedDate']
	var fName = file['name']
	console.log(fName)
	var reader = new FileReader();
	console.log(file);
	var data = null;
	reader.readAsText(file);
	reader.onload = function(e){
		var theFile= e.target.result;
		var data = Papa.parse(theFile, {
			complete: function(results){
				var data = results.data;
				console.log(data)
				var header = data[0];
				console.log(header)
				var taxaTable = $("#fileTaxa");
				$('.r').remove();
				taxaTable.append("<p class='r'>File Elements</p>")
				taxaTable.append("<ul class='list-group r>")
				var numTaxa = 0
				for (i in header){
					taxaTable.append("<li class='list-group-item r' contentEditable='true'>" + header[i] + "</li>");
					numTaxa +=1;
				}
				taxaTable.append("</ul>")
				var numLevels = data.length;
				var metaTable = $("#fileMetadata");
				metaTable.append("<p>File Metadata</p>");
				metaTable.append("<ul class=list-group>");
				metaTable.append("<li class='list-group-item'> <label>File Name: </label>" + fName + "</li>");
				metaTable.append("<li class='list-group-item'> <label>Last Modified: </label>" + modifiedDate + "</li>");
				metaTable.append("<li class='list-group-item'> <label>Number of Taxa: </label>" + numTaxa + "</li>");
				metaTable.append("<li class='list-group-item'> <label>Number of Levels: </label>" + numLevels + "</li>");
				metaTable.append("</ul>");
				$("#fileProperties").show();
				$('#fileName').val(fName);
				$('#fileNameInput').val(fName);
					$("#form-submit").click(function(){
						var theFile = uploadFile;
						var formData = new FormData();
						formData.append('upload', theFile, fName);
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'cgi-bin/fileUploader.php', true);
						xhr.onload = function () {
							console.log(this.responseText);
						  if (xhr.status === 200) {
						    // File(s) uploaded.
						    if (this.responseText == 1){
						    	alert("Your data has been uploaded successfully.")
						    }else{
						    	alert("Error.  Please try again later.\nError Code: " + this.responseText)
						    }
						  } else {
						    alert('An error occurred!');
						  }
						};
						xhr.send(formData);
					})
		}});
		
	reader.onerror = function(){
		console.log("Unable to read the file " + file.fileName);
	}
	}

	
}


/**
 * @author Scott Farley
 */
