jQuery(document).ready(function($) {
	addNewToggle();
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id = 0;
	var google=0,facebook=0,twitter=0;
	var feat=0,mem=0,red=0; 
	$('#viraldownloaderInsertButton').click(function(e) {
		//alert('ok');
		e.preventDefault();
		if($('#viraldownloaderDownlaodableSelect').val() != 'add-new') {
			var shortcode = generateShortCode($('#viraldownloaderDownlaodableSelect').val() , $('#viraldownloaderLinkText').val());
			tinyMCE.activeEditor.execCommand("mceInsertContent", false, shortcode);
			tb_remove();
		} else {
			var data = {
				action : 'add_new_downloadable',
				title : $('#fb_viral_downloader_container .title').val(),
				featured_image : $('#fb_viral_downloader_container .image').val(),
				data : {
					share_google : google,
					share_facebook : facebook,
					share_twitter : twitter,
					featured : feat,
					members : mem,
					redirect : red,
					share_url : $('#fb_viral_downloader_container .url').val(),
					share_caption : $('#fb_viral_downloader_container .caption').val(),
					share_description : $('#fb_viral_downloader_container .description').val(),
					share_file : $('#fb_viral_downloader_container .file').val()
				}
			};
			$.post(ajaxurl, data, function(response) {
				if(response) {
					//console.log(response);
					var shortcode = generateShortCode(response , $('#viraldownloaderLinkText').val());
					tinyMCE.activeEditor.execCommand("mceInsertContent", false, shortcode);
				} else {
					alert(response);
				}
				tb_remove();
			});
		}
	});
	
	$('#fb_viral_downloader_container .share_google').change(function (){
		if($(this).is(":checked"))	{
			google = 1;
		} else {
		 	google = 0;
		}
	});
	$('#fb_viral_downloader_container .share_facebook').change(function (){
		if($(this).is(":checked"))	{
			facebook = 1;
		} else {
			facebook = 0;
		}
	});
	$('#fb_viral_downloader_container .share_twitter').change(function (){
		if($(this).is(":checked"))	{
			twitter = 1;
		} else {
			twitter = 0;
		}
	});
	$('#fb_viral_downloader_container .featured').change(function (){
		if($(this).is(":checked"))	{
			feat = 1;
		} else {
		 	feat = 0;
		}
	});
	$('#fb_viral_downloader_container .members').change(function (){
		if($(this).is(":checked"))	{
			mem = 1;
		} else {
			mem = 0;
		}
	});
	$('#fb_viral_downloader_container .redirect').change(function (){
		if($(this).is(":checked"))	{
			red = 1;
		} else {
			red = 0;
		}
	});
	
	function generateShortCode(id, text) {
		var shortcode = '[viraldownloader id=';
		shortcode += id;
		shortcode += ' ';
		if($('#viraldownloaderLinkText').val() != '')
			shortcode += 'text=\'' + $('#viraldownloaderLinkText').val() + '\'';
		shortcode += ']';
		return shortcode;
	}
	
	$('#viraldownloaderDownlaodableSelect').on('change', addNewToggle);
	function addNewToggle() {
		if($('#viraldownloaderDownlaodableSelect').val() == 'add-new') {
			$('#fb_viral_downloader_container .hide').show();
		} else {
			$('#fb_viral_downloader_container .hide').hide();
		}
	}
	
	$('#fb_viral_downloader_container .upload').click(function(e) {
		e.preventDefault();
		var type = 'image';
		if($(this).hasClass('file-upload')) {
			type = 'file';
		}
 
    if ( file_frame ) {
      file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
      file_frame.uploader.uploader.param( 'upload_for', type );
      file_frame.open();
      return;
    } else {
      wp.media.model.settings.post.id = set_to_post_id;
    }
 
    file_frame = wp.media.frames.file_frame = wp.media({
      title: 'Choose a file',
      button: {
        text: 'Insert',
      },
      multiple: false
    });
 
    file_frame.on( 'select', function() {
      attachment = file_frame.state().get('selection').first().toJSON();
 
      if( (file_frame.uploader.uploader.param('upload_for') === undefined && type == 'file') ||  file_frame.uploader.uploader.param('upload_for') == 'file') {
				$('#fb_viral_downloader_container .file').val(attachment.url);
			} else {
				$('#fb_viral_downloader_container .image-preview').attr('src', attachment.url);
				$('#fb_viral_downloader_container .image').val(attachment.id);
			}
      
      wp.media.model.settings.post.id = wp_media_post_id;
    });
 
    file_frame.open();
	});
});