=== Social Viral Downloader ===
Contributors: Dualcube, arimghosh, SayanS
Tags: share to download, comment to download, Facebook, Google +, Twitter, action gate, actiongating, action-gating, action gating, like gating, likegating, like-gating, share to unlock, like to unlock, share to download, comment to download, viral content, sharing, social share, viral, facebook share, internet marketing, marketing, downloadable, popularity, file, pdf, downloads, monitor, hits, download monitor, tracking, admin, count, counter, files, versions, download count, logging, digital documents, download category, download manager, download template, downloadmanager, file manager, file tree, grid, hits, ip-address, manager, media, monitor, password, protect downloads, tracker

Donate link: http://dualcube.com/
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 2.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a "Share to Download" plugin, and works for Facebook, Google+ and Twitter. 

== Description ==
This plugin is a very powerful viral marketing tool. Once installed, this plugin prompts the user to share a msg in her Facebok/Twitter/Google before downloading any file from your site. The admin can edit the msg. from the setting pane. Additionally, the admin can now keep a track of the number of downloads, and hence shares, his files are getting. It is a simple yet powerful mechanism to gain widespread popularity throughsocial sites

= Features =
The Social Viral Downloader plugin create posts for your Wordpress websites where you can add a downloadable file link. You may also add a description along with a title for the Facebook post which will be shared when a viewer wants to download the file from your website. 

* Gain widespread popularity with minimal efforts!
* Added new sharing options Google+ & Twitter.
* Admin have opportunity to select which sharing method will apper on front end.
* Added two download options 'Redirect' & 'Featured'.
* Added different Settings panel for settings.
* Very minimal coding with maximum portability.
* All share count will be shown including individual sharing count.
* Total download count can be found in post page.
* Ability to add new shortcode from page itself.
* Each and every download and share counts.
* Add downloadable file to posts through shortcodes.
* Share files of any format you want.
* Simple and easy to use.
* Add shortcodes on the go from WP-Editor itself.
* Customize each download links by filter and styling.
* Block IP Address and Agents.
* All download logs with all details are stored.
* All Download logs will be there in table format.
* Ability to export download logs In CSV.
* Ability to search intended data In download logs.
* Ability to add categories and tags to posts.
* New graphical look.

= Compatibility =
* The plugin is fully compatible with the recent versions of Wordpress.
* Compatible with older Wordpress versions, down to 3.0.
* Multilingual Support is included with the plugin and is fully compatible with WPML.
* Support added for common importers like Wordpress Importer and WP All Import

= Configurable =
Social Viral Downloader is completely customizable. You may change settings as you want. And You also can block IP, Agents As your requirement.

= Feedback =
All we want is some love. If you did not like this plugin or if it is buggy, please give us a shout and we will be happy to fix the issue/add the feature. If you indeed liked it, please leave a 5/5 rating.  
In case you feel compelled to rate this plugin less than 5 stars - please do mention the reason and we will add or change options and fix bugs. It's very unpleasant to see silent low rates. For more information and instructions on this plugin please visit www.dualcube.com.

== Installation ==
1. Upload the entire `fb-viral-downloader` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Look at your admin bar and enjoy using the new links there.
4. Go and manage your forum.

== Frequently Asked Questions ==

= Does this plugin work with newest WP version and also older versions? =
Yes, this plugin works really fine with WordPress 4.2.2!
 It is also compatible for older Wordpress versions upto 3.0.1.

= Is it possible for the users by any means to download the file without sharing on Social Media? =
No, the download is only possible when the viewer shares the download link on given social media sites.

= Can I share any pdf or zip file? =
Yes, the plugin allows you to share any format of file you want

= Is the plugin compatible with my theme? =
Yes, the plugin is completely compatible with all the Wordpress themes!

= Can I add title and description to the link that will be posted on Social Media? =
Yes, you can add title and a brief description to the link to be posted on Social Media of the user.

= Can I use images instead of text in the download link? =
You can either enter the `img` tag directly inside the link text or keep the text blank and insert the link then you will find a shortcode added to the visual editor like `[viraldownloader id=123 text='']`. Place your custor between the quotes of `text=''` and then click on the Add media button and select an image. Make sure Link to is selected none before you insert into post.

You can do it programmatically too just add the following line to your theme's `functions.php` file

    add_filter( 'fb_viral_downloader_html', 'custom_fb_viral_downloader_html', 10, 3 );
    function custom_fb_viral_downloader_html( $text, $id, $share_count ) {
    	if( $id == 123 ) { //suppose your downloadable id is 123
    		$text = '<img src="http://yoursite/yourimagepath/yourimg.jpg"/>';
    	}
    	return $text;
    }


= Can I customize the Download Link html? =
Yes, you can do it just by adding a few lines to the theme's `functions.php`. `fb_viral_downloader_html` filter allows you to customize the html. It returns two extra parameters apart from the text, `$id` the id of the downloadable post and `$share_count` the count of the number of times the post is being shared.

Eg code:

    add_filter( 'fb_viral_downloader_html', 'custom_fb_viral_downloader_html', 10, 3 );
    function custom_fb_viral_downloader_html( $text, $id, $share_count ) {
    	return 'Share This to download' . '&nbsp;<span>(' . $share_count . '</span>)';
    }


== Screenshots ==
1. Social Viral Downloader in Admin Panel Configuration General Settings.
2. Social Viral Downloader in Admin Panel Configuration Download Logs Settings.
3. Social Viral Downloader Post Type.
4. Social Viral Downloader Add New Post.
5. Social Viral Downloader Categories.
6. Social Viral Downloader Tags.
7. Social Viral Downloader Download & Share Logs.
8. Social Viral Downloader End User View.

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Fixed W3 Total Cache compatibility issue. 

= 1.0.2 =
* Fixed broken link text.

= 1.1.0 =
* Shortcodes can now be added from the WP-Editor itself. Changed icons to match with Wordpress 3.8 Admin UI.

= 1.1.1 =
* Refactored and fixed code structure.

= 1.1.2 =
* Minor bug fix.

= 1.1.3 =
* Minor bug fix.

= 1.2 =
* Made compatible with Wordpress version 3.9.

= 1.3 =
* Fixed share count issue. Facebook profile link of recently shared users are shown in admin.

= 1.4 =
* Fixed conflict with other facebook widget.
* Added filter `fb_viral_downloader_html` to customize the download link.
* Unique class added to each download link for styling.

= 1.4.1 =
* Fixed notices in plugin

= 1.4.2 =
* Minor bug fixes

= 2.0.0 =
* Added Google+ And Twitter Support
* Added Download Count
* Share Count With Different Individual Count
* Download Logs
* Export And Delete Ability For All Logs
* New Admin Panel For IDs
* IP Block And Agent Block Capability

= 2.0.1 =
* 'Bringing User Back To Top' issue is solved.

= 2.0.2 =
* "Generate" button has been modified to make it compatible with different themes.

== Upgrade Notice ==

= 1.0.1 =
* Fixed W3 Total Cache compatibility issue.  

= 1.0.2 =
* Fixed broken link text.

= 1.1.0 =
* Shortcodes can now be added from the WP-Editor itself. Changed icons to match with Wordpress 3.8 Admin UI.

= 1.1.1 =
* Refactored and fixed code structure.

= 1.1.2 =
* Minor bug fix.

= 1.1.3 =
* Minor bug fix.

= 1.2 =
* Made compatible with Wordpress version 3.9.

= 1.3 =
* Fixed share count issue. Facebook profile link of recently shared users are shown in admin.

= 1.4 =
* Fixed conflict with other facebook widget. Added filter `fb_viral_downloader_html` to customize the download link. Unique class added to each download link for styling.

= 1.4.1 =
* Fixed notices in plugin

= 1.4.2 =
* Minor bug fixes

= 2.0.0 =
* Added Google+ And Twitter Support
* Added Download Count
* Share Count With Different Individual Count
* Download Logs
* Export And Delete Ability For All Logs
* New Admin Panel For IDs
* IP Block And Agent Block Capability

= 2.0.1 =
* 'Bringing User Back To Top' issue is solved.

= 2.0.2 =
* "Generate" button has been modified to make it compatible with different themes.

