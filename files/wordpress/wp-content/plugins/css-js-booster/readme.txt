=== Plugin Name ===
Contributors: Schepp
Tags: performance, frontend, speed, loading time, css, javascript, concatenate, compress
Requires at least: 2.9.1
Tested up to: 3.0.0
Stable tag: trunk

CSS-JS-Booster automates performance optimizing steps related to CSS, Media 
and Javascript linking/embedding.

== Description ==

CSS-JS-Booster is a PHP-script that tries to automate as many performance
optimizing steps related to CSS and JS embedding as possible.
Depending on the amount of CSS, CSS-images and JS, this can significantly
increase loading speed of your site.

Note: Check by hand the htaccess-part! (see "Installation")

= For CSS optimization steps are: =

* combine multiple CSS-files resulting in less HTTP-requests
* Optimize and minify CSS with CSSTidy
* Embed any CSS-images smaller 24KB as data-URI or MHTML (for IE <= 7)
* GZIP-compress the resulting CSS
* Have browsers cache the result as long as it remains unchanged
* If IE6: Issue a JS-command to fix background image caching behaviour 

= For JS optimization steps are: =

* Fetch JS from external servers and cache it locally
* Combine multiple JS-files resulting in HTTP-requests going down
* GZIP-compress the resulting JS
* Have browsers cache the result as long as it remains unchanged

== Installation ==

CSS-JS-Booster absolutely requires PHP 5. No PHP 4, sorry...
Version-wise it is tested up until PHP 5.3. 

* Copy the whole `booster`-folder into `wp-content/plugins/`
* If not already there: create a subfolder named `booster_cache` inside `wp-content/` and CHMOD it to 0777 (give it write-permissions)
* Go into the admin-panel to the plugins and activate `CSS-JS-Booster`
* Check if the contents of the file `wp-content/plugins/booster/htaccess/.htaccess` were put into/appended to the .htaccess-file in the root of your Wordpress-site. If not append them by hand.

= Compatibility with other plugins =

CSS-JS-Booster may in rare cases break some other plugins.
I noticed for example that plugins trying to calculate file-paths based on the src-attribute of the script-tag break.
So you need to check yourself.

If your site has many visitors, we suggest that you combine CSS-JS-Booster with WP-Super-Cache.

== Support ==

Please send bug reports this way: http://github.com/Schepp/CSS-JS-Booster/issues

When creating a new issue: 

* Make sure that you mention in the title that you are talking about the Wordpress plugin (as there is a standalone version, too), e.g.: "[WP] Booster killed my site"
* Make sure that you send me a link to your page
* Tell me what exactly is broken
* It helps if you put the HTML source code of your page resulting from activated Booster on http://pastebin.com/ and include a link to it
* It helps if you put the HTML source code of your page resulting from deactivated Booster on http://pastebin.com/ and include a link to it
* It helps if you tell me which plugins are active

