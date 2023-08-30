if(hepta === undefined) {
  var hepta = {};
}

hepta.dzfield = {};

function dzSuccessfulUpload(file, responseText)
{
	console.log(responseText);
	responseText = JSON.decode(responseText);
	if(responseText.error == 0)
	{
		while(responseText.uploadedFiles.length)
		{
			var uploadedFile = responseText.uploadedFiles.pop();
			var fileHash =  uploadedFile.hash;
			file.hash = fileHash;
			uploadedFile.id = 0;
			hepta.dzfield.dzFiles[fileHash] = {'id': uploadedFile.id.toString(), 'name': uploadedFile.name.toString(), 'path': uploadedFile.path.toString(), 'hash': fileHash};
		}
		jQuery('#' + responseText.fieldId).val(JSON.encode(hepta.dzfield.dzFiles));
	}
}

function dzRemoveFile(file,containerId)
{
	var toRemove = '';
	if(file.hasOwnProperty('id'))
	{
		toRemove = file.id;
	}
	else
	{
		toRemove = file.hash;
	}

	delete hepta.dzfield.dzFiles[toRemove];

	jQuery('#' + containerId).val(JSON.encode(hepta.dzfield.dzFiles));
}

function addServerFile(dzFile)
{
	//var mockFile = { name: dzFile.name, size: dzFile.size };
	hepta.Dropzone.emit("addedfile", dzFile);
	var imageUrl = hepta.baseURL + dzFile.path;
	hepta.Dropzone.createThumbnailFromUrl(dzFile, imageUrl);

	// Make sure that there is no progress bar, etc...
	hepta.Dropzone.emit("complete", dzFile);
}

function addServerFiles(container){
	var value = jQuery(container).val();
	var files = JSON.decode(value);
	if(files.length > 0)
	{
		
		jQuery.each(files,function (index, dzFile) {
			addServerFile(dzFile);
		});
		var existingFileCount = files.length; // The number of files already uploaded
		hepta.Dropzone.options.maxFiles = hepta.Dropzone.options.maxFiles - existingFileCount;
	}

};
