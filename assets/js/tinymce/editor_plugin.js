(function () {
	tinymce.create("tinymce.plugins.ViralDownloaderPlugin", {
		init: function (d, e) {
			d.addCommand("vdOpenDialog", function (a, c) {
				var f = jQuery(window).width();
				b = jQuery(window).height();
				f = 400 < f ? 400 : f;
				f -= 80;
				b -= 84;
				tb_show("Add viralDownloader Link",	"#TB_inline?width=" + f + "&height=" + b + "&inlineId=vd-dialog");
			});
			d.onNodeChange.add(function (a, c) {
				c.setDisabled("vd_button", a.selection.getContent().length > 0)
			})
		},
		createControl: function (d, e) {
			if (d == "vd_button") {
				d = e.createButton("vd_button", {
					text: 'Add viralDownloader Link',
					image: "../wp-content/plugins/fb-viral-downloader/assets/images/viral_downloader.png",
					icon: false,
					onclick: function() {
						tinyMCE.activeEditor.execCommand("vdOpenDialog", false, {title: 'Add viralDownloader Link' });
					}
				});
				return d
			}
			return null
		},
		getInfo: function () {
			return {
				longname: "viralDownloader Plugin",
				author: "dualcube.com",
				authorurl: "http://dualcube.com",
				infourl: "http://dualcube.com",
				version: "1.0"
			}
		}
	});
	tinymce.PluginManager.add("ViralDownloaderPlugin", tinymce.plugins.ViralDownloaderPlugin)
})();