jQuery(document).ready(function($) {
	addNewToggle();
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id = 0;
	$('#viraldownloaderInsertButton').click(function(e) {
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
					share_url : $('#fb_viral_downloader_container .url').val(),
					share_caption : $('#fb_viral_downloader_container .caption').val(),
					share_description : $('#fb_viral_downloader_container .description').val(),
					share_file : $('#fb_viral_downloader_container .file').val()
				}
			};
			$.post(ajaxurl, data, function(response) {
				if(response) {
					var shortcode = generateShortCode(response , $('#viraldownloaderLinkText').val());
					tinyMCE.activeEditor.execCommand("mceInsertContent", false, shortcode);
				} else {
					alert(response);
				}
				tb_remove();
			});
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
