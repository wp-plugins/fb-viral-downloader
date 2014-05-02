(function() {
	tinymce.PluginManager.add("ViralDownloaderPlugin", function(editor, url) {

		editor.addCommand("vdOpenDialog", function(a, c) {
			tb_show("Add FB Viral Downloader Link", "#TB_inline?width=600&height=550&inlineId=vd-dialog");
		});
		editor.onNodeChange.add(function(a, c) {
			c.setDisabled("vd_button", a.selection.getContent().length > 0);
		});

		editor.addButton('vd_button', {
			title : 'Add Viral Downloader Link',
			image : "../wp-content/plugins/fb-viral-downloader/assets/images/viral_downloader.svg",
			icon : false,
			onclick : function() {
				tinyMCE.activeEditor.execCommand("vdOpenDialog", false, {
					title : 'Add viralDownloader Link'
				});
			}
		});
	});
})();
