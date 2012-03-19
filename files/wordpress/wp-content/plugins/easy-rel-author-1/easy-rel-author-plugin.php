<?php
/*
Plugin Name: Easy rel=author Plugin
Plugin URI: http://www.kevinmeyer.me
Version: 0.71b
Description: Changes the Author byline to link to a google profile and tag it with rel=author
Usage:  Add your google profile URL as your profile website in wordpress
Author: Kevin Meyer
Author URI: http://www.kevinmeyer.me/
License: GPL2
*/


/*  Copyright 2011  Kevin Meyer  (email : )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Easy rel=author Plugin Version
 * @var string
 */
$easy_rel_author_version = "0.71b";
$easy_rel_author_name = "Easy rel=author Plugin";




/**
 * Displays and saves the Settings for the Easy Rel Author Plugin
 * 
 */
function EasyRelAuthor_subpanel(){
     if (isset($_POST['save_EasyRelAuthor_settings'])) {
       $option_add_post_footer = $_POST['post_footer'];
       if($option_add_post_footer=='on')
       		$option_add_post_footer='checked';
		else
			$option_add_post_footer='';
		
		$option_name_on_footer=$_POST['name_on_footer'];
		if($option_name_on_footer=='on')
			$option_name_on_footer='checked';
		else
			$option_name_on_footer='';
			
       	update_option('EasyRelAuthor_post_footer', $option_add_post_footer);
        update_option('EasyRelAuthor_name_on_footer', $option_name_on_footer);
       ?> <div class="updated"><p><?php echo "Easy rel=author Plugin"; ?> settings saved</p></div> <?php

       
     }
	
	?>    
		<div class="wrap">
		<h2>Easy rel=author Plugin Settings</h2>
		The default setting for this plugin is a link from the author name in the byline.<br>You must also go into your Wordpress profile and add your Google+ profile URL (e.g. https://plus.google.com/106880569092865851618)
		 and link your <a href=http://www.google.com/support/webmasters/bin/answer.py?answer=1229920>Google+ profile back to
		  		this domain</a> as "link" in your "about page".
		<form method="post">
		<table class="form-table" border=10>
		 <tr valign="middle" >
		  <th scope="row">
		  <td><input name="post_footer" type="checkbox" <?php echo get_option('EasyRelAuthor_post_footer');?>></td><td><h3 margin="0">Add a footer on each post</h3>By default, the plugin will link the author's name byline to the Google+ profile.
		  		<br>Check this box if you would rather have an additional byline footer added or if
		  		your theme does not hyperlink the byline by default.</td></th>
        	</tr>
         <tr valign="middle">
         	<th scope="row">
         		<td><input name="name_on_footer" type="checkbox" <?php echo get_option('EasyRelAuthor_name_on_footer');?>></td><td><h3>Use author name in footer link</h3>By default, the plugin will
         		use the Google+ image <img src=http://www.google.com/images/icons/ui/gprofile_button-16.png /> in the footer of the post.  If you would rather have your name as the rel=auther hyperlink text, click here.<br>  Only works if you have selected the 
         		above "footer on each post" option</td>
         		</tr>
		</table>      
                  
         	 <div class="submit">
           <input type="submit" name="save_EasyRelAuthor_settings" value="<?php _e('Save Settings', 'save_EasyRelAuthor_settings') ?>" />
        </div>
        </form> 
        If you find this plugin useful and would like to donate $5, we'll put it toward making sure the plugin stays updated in the future. 

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="2N2X9SGN8F786">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
        
        </div>
        <?php 
}




/**
 * Adds the Easy Rel author Settings page to the Options WP Menu.
 * 
 */

function EasyRelAuthor_admin_menu() {
   if (function_exists('add_options_page')) {
        add_options_page("Easy rel=author Settings", "Easy rel=author", 8, basename(__FILE__), 'EasyRelAuthor_subpanel');
        }
}

/**
 * Called when the plugin goes to deactivated state - Does needed cleanup.
 * 
 */
function EasyRelAuthor_deactivate() {
	return;
}




/**
 * Called when plugin moves to an active state
 * 
 */
function EasyRelAuthor_activate() {
   
}



add_action('admin_menu', 'EasyRelAuthor_admin_menu'); 

register_activation_hook( __FILE__, 'EasyRelAuthor_activate' );
register_deactivation_hook( __FILE__, 'EasyRelAuthor_deactivate' );


// Replace the_author() output
function rel_author_custom_author_byline( $author ) {
	global $post;
	$custom_author = get_post_meta($post->ID, 'author', TRUE);
	if($custom_author)
		return $custom_author;
	return $author ;
}
add_filter('the_author','rel_author_custom_author_byline');

// Replace the_author_link() output
function rel_author_custom_author_uri( $author_uri ) {
	global $authordata;

	//doing it via post footer?
	if( get_option('EasyRelAuthor_post_footer')=='checked')
		return $author_uri;
		
	//if the g+ profile setting is blank - return the default
	$gplus_profile = get_the_author_meta('google_plus');
	if($gplus_profile=='')
		return $author_uri;
		
		
	$pos= stripos($gplus_profile, "?rel=author");
	if($pos===false)
	{
		$gplus_profile .= "?rel=author";
	}
	
	return 	$gplus_profile . "\" rel=\"author";
	
}
add_filter( 'author_link', 'rel_author_custom_author_uri' );


function add_google_plus_profile( $contactmethods)
{
	// add google profiles
	$contactmethods['google_plus'] = "Google+ URL";
	return $contactmethods;
}
add_filter('user_contactmethods', 'add_google_plus_profile');



function EasyRelAuthor_add_post_footer($text) 
{
	if( get_option('EasyRelAuthor_post_footer') =='checked')
	{
		$relauthor= get_the_author_meta('google_plus');
		if($relauthor=='')
		{
			//if the google profile setting is blank
			//just return the post without a link
			return $text;
		}

		
		$pos=stripos($relauthor,"?rel=author");
		if($pos===false)
		{
			//make sure to link the profile with ?rel=author at the end
			$relauthor.="?rel=author";
		}
		
		if(get_option('EasyRelAuthor_name_on_footer')=='checked')
		{
			$author_name=get_the_author_meta('first_name');
			$text .= "<p><a href=\"$relauthor\" rel=\"author\"> -" . $author_name ."</a></p>";
	
		}
		else
		{
			$text .= "<p><a href=\"$relauthor\" rel=\"author\"><img src=\"http://www.google.com/images/icons/ui/gprofile_button-16.png\" /></a></p>";
		}
	}
	return $text;
}

add_action('the_content', 'EasyRelAuthor_add_post_footer');



?>