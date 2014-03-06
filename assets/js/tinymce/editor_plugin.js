(function () {
	tinymce.create("tinymce.plugins.ViralDownloaderPlugin", {
		init: function (d, e) {
			d.addCommand("vdOpenDialog", function (a, c) {
				tb_show("Add FB Viral Downloader Link",	"#TB_inline?width=600&height=550&inlineId=vd-dialog");
			});
			d.onNodeChange.add(function (a, c) {
				c.setDisabled("vd_button", a.selection.getContent().length > 0)
			})
		},
		createControl: function (d, e) {
			if (d == "vd_button") {
				d = e.createButton("vd_button", {
					title: 'Add Viral Downloader Link',
					image: "../wp-content/plugins/fb-viral-downloader/assets/images/viral_downloader.svg",
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
