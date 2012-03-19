<?php
/*
Plugin Name: Recent Disqus Comments Widget
Plugin URI: 
Description: Get Most Recent Comments from Disqus
Author: Niall Molloy
Version: 0.7
Author URI:
License: GPL v3
*/

$RC_PLUGIN_NAME  = "Recent Disqus Comments Widget";
$RC_WIDGET_TITLE = "Recent Disqus Comments";

add_action("plugins_loaded", "recent_disqus_comments_widget_init");

function recent_disqus_comments_widget_init() {
	global $RC_WIDGET_TITLE;

	recent_disqus_comments_widget_setup ();
	register_sidebar_widget ($RC_WIDGET_TITLE, 'recent_disqus_comments_widget_render');
	register_widget_control ($RC_WIDGET_TITLE, 'recent_disqus_comments_widget_preferences');
}

function recent_disqus_comments_widget_render ($args) {
	global $RC_PLUGIN_NAME;

	recent_disqus_comments_widget_setup ();
	$options = get_option ($RC_PLUGIN_NAME);
	extract($args);
	$echo_widget = $before_widget;
	if ($options['rc-titlebar-visible']) {
		$echo_widget .= $before_title;
		$echo_widget .= $options['title'];
		$echo_widget .= $after_title;
	}
	$echo_widget .= recent_disqus_comments_widget_get_content ();
	$echo_widget .= $after_widget;
	echo $echo_widget;
}

function recent_disqus_comments_widget_get_content () {
	global $RC_PLUGIN_NAME;
	$options = get_option ($RC_PLUGIN_NAME);
    
	$script  = '<script type="text/javascript" src="http://disqus.com/forums/omgubuntu/recent_comments_widget.js?';
	$script .= "num_rc-items={$options["rc-items"]}&amp;hide_avatars=";
	if ( $options['rc-show-avatars'] ) $script .= '0';
	else $script .= '1';
	$script .= "&amp;avatar_size={$options["rc-avatar-size"]}";
	$script .= '&amp;excerpt_length=' . $options['rc-excerpt-length'] . '"></script>';
	
	return $script;
}

function recent_disqus_comments_widget_setup () {
	global $RC_PLUGIN_NAME;

	$options = get_option ($RC_PLUGIN_NAME);
	if (!is_array ($options) || empty ($options["title"])) {
		$options = array ("title" => "Recent Comments",
				   "rc-items" => 5,
				   "rc-show-avatars" => true,
				   "rc-avatar-size" => 32,
				   "rc-excerpt-length" => 200,
				   "rc-titlebar-visible" => true,
				 );
        	update_option ($RC_PLUGIN_NAME, $options);
    }
}

function recent_disqus_comments_widget_preferences () {
	global $RC_PLUGIN_NAME;
	$options = get_option ($RC_PLUGIN_NAME);
	
	if ($_POST["submit-settings"]) {
		$options["rc-items"] = (int)$_POST['rc-items'];
		if ($_POST["rc-show-avatars"]) $options["rc-show-avatars"] = true;
		else $options["rc-show-avatars"] = false;
		$options["rc-avatar-size"] = (int)$_POST['rc-avatar-size'];
		$options["rc-excerpt-length"] = $_POST['rc-excerpt-length'];
		if ($_POST["rc-titlebar-visible"]) $options["rc-titlebar-visible"] = true;
		else $options["rc-titlebar-visible"] = false;
		$options['title'] = htmlspecialchars ($_POST['title']);

	}
	
	update_option($RC_PLUGIN_NAME, $options);
?>

<p>
	<label for="rc-items">Number of items to display (Max 20):</label>
	<select name="rc-items" id="rc-rc-items">
		<option <?php if($options['rc-items'] == "1") { echo "selected"; }?> value="1">1</option>
		<option <?php if($options['rc-items'] == "2") { echo "selected"; }?> value="2">2</option>
		<option <?php if($options['rc-items'] == "3") { echo "selected"; }?> value="3">3</option>
		<option <?php if($options['rc-items'] == "4") { echo "selected"; }?> value="4">4</option>
		<option <?php if($options['rc-items'] == "5") { echo "selected"; }?> value="5">5</option>
		<option <?php if($options['rc-items'] == "6") { echo "selected"; }?> value="6">6</option>
		<option <?php if($options['rc-items'] == "7") { echo "selected"; }?> value="7">7</option>
		<option <?php if($options['rc-items'] == "8") { echo "selected"; }?> value="8">8</option>
		<option <?php if($options['rc-items'] == "9") { echo "selected"; }?> value="9">9</option>
		<option <?php if($options['rc-items'] == "10") { echo "selected"; }?> value="10">10</option>
		<option <?php if($options['rc-items'] == "11") { echo "selected"; }?> value="11">11</option>
		<option <?php if($options['rc-items'] == "12") { echo "selected"; }?> value="12">12</option>
		<option <?php if($options['rc-items'] == "13") { echo "selected"; }?> value="13">13</option>
		<option <?php if($options['rc-items'] == "14") { echo "selected"; }?> value="14">14</option>
		<option <?php if($options['rc-items'] == "15") { echo "selected"; }?> value="15">15</option>
		<option <?php if($options['rc-items'] == "16") { echo "selected"; }?> value="16">16</option>
		<option <?php if($options['rc-items'] == "17") { echo "selected"; }?> value="17">17</option>
		<option <?php if($options['rc-items'] == "18") { echo "selected"; }?> value="18">18</option>
		<option <?php if($options['rc-items'] == "19") { echo "selected"; }?> value="19">19</option>
		<option <?php if($options['rc-items'] == "20") { echo "selected"; }?> value="20">20</option>
	</select>
</p>
<p>
	<label for="rc-show-avatars">Show avatars:</label>
	<input type="checkbox" id="rc-show-avatars" name="rc-show-avatars" value="rc-show-avatars" <?php if($options['rc-show-avatars']) { echo "checked"; }?>/>
</p>
<p>
	<label for="rc-avatar-size">Avatar Size:</label>
	<select  id="rc-avatar-size" name="rc-avatar-size">
		<option <?php if($options['rc-avatar-size'] == "24") { echo "selected"; }?> value="24">Small (24px)</option>
		<option <?php if($options['rc-avatar-size'] == "32") { echo "selected"; }?> value="32">Medium (32px)</option>
		<option <?php if($options['rc-avatar-size'] == "48") { echo "selected"; }?> value="48">Large (48px)</option>
		<option <?php if($options['rc-avatar-size'] == "92") { echo "selected"; }?> value="92">X-Large (92px)</option>
		<option <?php if($options['rc-avatar-size'] == "128") { echo "selected"; }?> value="128">Ginormous (128px)</option>
	</select>									
</p>
<p>
	<label for="rc-excerpt-length">Comment Excerpt Length:</label>
	<input type="text" id="rc-excerpt-length" name="rc-excerpt-length" value="<?php echo $options['rc-excerpt-length'];?>" />
</p>
<p>
	<label for="rc-titlebar-visible">Display Widget Title Bar:</label>
	<input type="checkbox" id="rc-titlebar-visible" name="rc-titlebar-visible" value="rc-titlebar-visible" <?php if($options['rc-titlebar-visible']) { echo "checked"; }?>/>
</p>
<p>
	<label for="title">Title:</label>
	<input type="text" id="" name="title" value="<?php echo $options['title'];?>" />
</p>
<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

<?php
}
?>
