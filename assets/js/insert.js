jQuery(document).ready(function($) {
	$('#viraldownloaderInsertButton').click(function(e) {
		e.preventDefault();
		var shortcode = '[viraldownloader id=';
		shortcode += $('#viraldownloaderDownlaodableSelect').val();
		shortcode += ' ';
		if($('#viraldownloaderLinkText').val() != '')
			shortcode += 'text="' + $('#viraldownloaderLinkText').val() + '"';
		shortcode += ']';
		tinyMCE.activeEditor.execCommand("mceInsertContent", false, shortcode);
		tb_remove();
	});
});
