jQuery.fn.KioskScan = function(options) {
	var scan_object = $('<object width="400" height="450" classid="CLSID:74F4F118-91E6-4AFC-B8D2-04066781F239" id="Eztwain" name="Eztwain" codebase="/files/scanning/eztwainx.cab" standby="Loading EZTwainX"><param name="Licensee" value="Complete Technology Solutions"><param name="LicenseKey" value="-840147303"><param name="AppTitle" value="' + options.company + ' Self Scan"><param name="BackColor" value="0xcc8844"></object>');

	this.html(scan_object);

	var twain				= document.getElementById('Eztwain');
	var location_id 		= options.location_id 		|| '';
	var self_scan_cat_id 	= options.self_scan_cat_id 	|| '';
	var queue_cat_id 		= options.queue_cat_id 		|| '';
	var user_id				= options.user_id 			|| '';
	var php_session_id 		= options.php_session_id 	|| '';

	var upload_destination	= window.location.protocol + "//" + window.location.hostname + "/kiosk/kiosks/self_scan_document";
	var filename			= "file.pdf";
	var file_field			= "data[QueuedDocument][submittedfile]";
	var user_field			= "data[QueuedDocument][user_id]";

	var location_field		= "data[QueuedDocument][scanned_location_id]";
	var self_scan_field		= "data[QueuedDocument][self_scan_cat_id]";
	var queue_cat_field		= "data[QueuedDocument][queue_category_id]";

	options.$scan.click(scan);
	options.$rescan.click(scan);

	function scan(e) {
		twain.ScanWithUI 	= false;
	    twain.ScanType 		= 0;
	    twain.ScanDPI 		= 300;
	    twain.AcquireSingleImage();
	}

	options.$save.click(function(e){
		twain.UploadExtraField(location_field, location_id);
		twain.UploadExtraField(self_scan_field, self_scan_cat_id);
		twain.UploadExtraField(queue_cat_field, queue_cat_id);
		twain.UploadExtraField(user_field, user_id);
		twain.UploadAddCookie ("PHPSESSID=" + php_session_id);
	    
	    var is_uploaded = twain.UploadToURL(upload_destination, filename, file_field);

	    if(is_uploaded)
	    {
	    	window.location.href = "/";
	    }
	    else
	    {
	    	alert('Failed to Upload the document, try again');
	    }

	});

	return this;
};