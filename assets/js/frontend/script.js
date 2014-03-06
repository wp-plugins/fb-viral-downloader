jQuery(document).ready(function($) {
	$.ajaxSetup({
		cache : true
	});

	$.getScript('//connect.facebook.net/en_UK/all.js', function() {
		FB.init({
			appId : parseInt(viraldownloader_data.fb_client_id),
			status : true,
			cookie : true
		});

		function fb_share(data) {
			// calling the API ...
			var obj = {
				method : 'feed',
				link : data.url,
				picture : data.image,
				name : data.title,
				caption : data.caption,
				description : data.description
			};

			FB.ui(obj, shareCallback);

			function shareCallback(response) {
				if (response) {

					var ajaxData = {
						fb_post_id : response,
						download_id : data.id,
						action : 'viraldownloader_share_complete'
					}

					jQuery.post(viraldownloader_data.ajax_url, ajaxData, function(response) {
						if (response) {
							window.location.href = response;
						}
					});
				}
			}

		}


		$('.viraldownloader_url').click(function(e) {
			e.preventDefault();
			fb_share($(this).data());
		});
	});
});
