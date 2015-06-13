jQuery(document).ready(function($) {
	var share_data = 0;
	var google = 0;
	var facebook = 0;
	var twitter = 0;
	//var privacy = {"value":"ALL_FRIENDS"};
	$.ajaxSetup({
		cache : true
	});
	$('.download').hide();
	$('.viraldownloader_url').click(function(e) {
		$('.download').toggle();
	});
	$('#fb').click(function(f) {
		var fbid = $(this).data().fbid;
		var lid = $(this).data().id;
		var url = $(this).data().url;
		var caption = $(this).data().caption;
		var description = $(this).data().description;
		var title = $(this).data().title;
		$.getScript('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', function() {
			window.fbAsyncInit =	function() {
				FB.init({
					appId : fbid,
					status : true,
					version : 'v2.3',
					cookie : true,
					xfbml : true
				});
				FB.getLoginStatus(function(response) {
					statusChangeCallback(response);
				});
			};
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id))
					return;
				js = d.createElement(s); 
				js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=" + fbid + "&version=v2.3";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			function statusChangeCallback(response) {
				if(response.status=='connected'){
					fb_share($(this).data());
				} else {
					FB.login(function(response){
						if (response.status === 'connected'){
							fb_share($(this).data());	
						}
					},{scope: 'public_profile,email'});
				}
			}
			function fb_share(data) {
				var obj = {
					method : 'feed',
					link : url,
					name : title,
					caption : caption,
					description : description
				};
				FB.ui(obj, shareCallback);
				function shareCallback(response) {
					if (response) {
						var ajaxData = {
							fb_post_id : response,
							download_id : lid,
							action : 'viraldownloader_share_complete'
						}
						jQuery.post(viraldownloader_data.ajax_url, ajaxData, function(response) {
							facebook = facebook + 1;
						});
					}
				}
			}
		});
	});
	$('#gp').click(function(f) {
		$("#google_icon").attr("onclick","javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;");    
		var data = {
			action : 'google_share',
			id : $(this).data().id,
		};
		$.post(viraldownloader_data.ajax_url, data, function(response) {
			if(response){
				google = google + 1;
			}
		});
	});
	$('#tw').click(function(f) {
		twitter = twitter + 1;
	});
	$('#bttn').click(function(f) {
		var gp = $(this).data().gp;
		var fb = $(this).data().fb;
		var tw = $(this).data().tw;
		if( gp == google && fb == facebook && tw == twitter) {
			var feature = $(this).data().feature;
			var file = $(this).data().file;
			if(!$(this).data().ip && !$(this).data().agents){
				alert('Disabled');
				var data = {
					action : 'generate',
					value : share_data,
					id : $(this).data().id,
					member : $(this).data().members
				};
			}	else {
				var data = {
					action : 'generate',
					value : share_data,
					id : $(this).data().id,
					ip : $(this).data().ip,
					agents : $(this).data().agents,
					member : $(this).data().members
				};
			}
			$.post(viraldownloader_data.ajax_url, data, function(response) {
				if (response) {
					if(response == 'IP Blocked'){
						alert(response);
					} else if(response == 'You Are Unauthorised'){
						alert(response);
					} else if(response == 'This File is For Members Only'){
						alert(response);
					} else if(feature == 1){
						var download_link = document.createElement('a');
      			download_link.id = "attachment";
      			download_link.href = response;
      			download_link.download = file;
      			document.body.appendChild(download_link);
      			download_link.click();
					} else {
						window.location.href = response;
					}
				}
			});
		} else {
			if( gp ==1 && tw == 0 & fb == 0) {
				alert("Please Share Via Google Plus");
			} else if( gp ==0 && tw == 1 && fb == 0) {
				alert("Please Share Via Twitter");
			} else if( gp ==0 && tw == 0 && fb == 1) {
				alert("Please Share Via Facebook");
			} else if( gp ==0 && tw == 1 && fb == 1) {
				alert("Please Share Via Twitter & Facebook");
			} else if( gp ==1 && tw == 1 && fb == 0) {
				alert("Please Share Via Twitter & Google+");
			} else if( gp ==1 && tw == 0 && fb == 1) {
					alert("Please Share Via Google+ & Facebook");
			} else {
				alert("Please Share Via All Social Media");
			}
		}
	});
});