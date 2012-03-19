<?php
/*
Plugin Name: Ask Ubuntu Hot Posts Widget
Plugin URI: http://www.omgubuntu.co.uk
Description: Get hot questions from Ask Ubuntu
Author: Richard Lyon
Version: 0.1
Author URI: http://richardlyon.co.uk/
License: GPL v3
*/

include "ask-ubuntu-random-widget.php";

$AUH_PLUGIN_NAME  = "Ask Ubuntu Hot Questions";
$AUH_WIDGET_TITLE = "Ask Ubuntu Hot Questions";
$API_KEY = "MhThP0gXyUSxQU0lLoc7qA";

add_action("plugins_loaded", "ask_ubuntu_hot_widget_init");

function ask_ubuntu_hot_widget_init() {
	global $AUH_WIDGET_TITLE;

	ask_ubuntu_hot_widget_setup ();
	register_sidebar_widget ($AUH_WIDGET_TITLE, 'ask_ubuntu_hot_widget_render');
	register_widget_control ($AUH_WIDGET_TITLE, 'ask_ubuntu_hot_widget_preferences');
}

function ask_ubuntu_hot_widget_render ($args) {
	global $AUH_PLUGIN_NAME;

	ask_ubuntu_hot_widget_setup ();
	$options = get_option ($AUH_PLUGIN_NAME);
	extract($args);
	$echo_widget = $before_widget;

		$echo_widget .= $before_title;
		$echo_widget .= $options['title'];
		$echo_widget .= $after_title;

	$echo_widget .= ask_ubuntu_hot_widget_get_content ();
	$echo_widget .= $after_widget;
	echo $echo_widget;
}

function ask_ubuntu_hot_widget_get_content () {
	global $AUH_PLUGIN_NAME;
	global $API_KEY;
	
	if( !class_exists('CachedAsk') )
	{
		require "class.cachedask.php";
	}
	
	$options = get_option ($AUH_PLUGIN_NAME);

	$tc = new CachedAsk( 'api.askubuntu.com/1.1/questions', $API_KEY, $options['auh-sort'], $options['auh-items'], '&body=true&answers=true', $options['auh-cache'] );
	$questions = $tc->questions;
	
	$content = "<ul>";
	for( $i = 0; $i < $options['auh-items']; $i++ )
	{
		$question = (object)$questions[$i];
		$question->title = preg_replace("/\\\u([A-Za-z0-9]{4})/", "&#x$1;", $question->title);
		$content   .= "<li><a title=\"{$question->title}\" href=\"http://www.askubuntu.com/questions/{$question->question_id}\" target='_blank'>{$question->title}<span class=\"post-rank\">".($i+1)."</span></a></li>";
	}
	$content .= "</ul>";	
	
	return $content;
}

function ask_ubuntu_hot_widget_setup () {
	global $AUH_PLUGIN_NAME;
	
	$options = get_option ($AUH_PLUGIN_NAME);
	if (!is_array ($options) || empty ($options["title"]) /*|| empty ($options["auh-top-colour"]) || empty ($options["auh-bot-colour"])*/) {
		$options = array ("title" => "hot questions",
				   "auh-items" => 5,
				   "auh-sort" => 'hot',
				   "auh-cache" => 10
				  );
		update_option ($AUH_PLUGIN_NAME, $options);
    }
}

function ask_ubuntu_hot_widget_preferences () {
	global $AUH_PLUGIN_NAME;
	$options = get_option ($AUH_PLUGIN_NAME);

	$sorts = array( 'hot', 'activity', 'votes', 'creation', 'featured', 'week', 'month' );

	if ($_POST["submit-settings"]) {
		$options["auh-items"] = (int)$_POST['auh-items'];
		$options["auh-cache"] = (int)$_POST['auh-cache'];
		$options['title'] = htmlspecialchars ($_POST['title']);
		$options['auh-sort'] = in_array( $_POST['auh-sort'], $sorts ) ? $_POST['auh-sort'] : 'hot';
	}

	update_option($AUH_PLUGIN_NAME, $options);
?>

<p>
	<label for="auh-items">Number of questions (Max 20):</label>
	<select name="auh-items" id="auh-items">
		<option <?php if($options['auh-items'] == "1") { echo "selected"; }?> value="1">1</option>
		<option <?php if($options['auh-items'] == "2") { echo "selected"; }?> value="2">2</option>
		<option <?php if($options['auh-items'] == "3") { echo "selected"; }?> value="3">3</option>
		<option <?php if($options['auh-items'] == "4") { echo "selected"; }?> value="4">4</option>
		<option <?php if($options['auh-items'] == "5") { echo "selected"; }?> value="5">5</option>
		<option <?php if($options['auh-items'] == "6") { echo "selected"; }?> value="6">6</option>
		<option <?php if($options['auh-items'] == "7") { echo "selected"; }?> value="7">7</option>
		<option <?php if($options['auh-items'] == "8") { echo "selected"; }?> value="8">8</option>
		<option <?php if($options['auh-items'] == "9") { echo "selected"; }?> value="9">9</option>
		<option <?php if($options['auh-items'] == "10") { echo "selected"; }?> value="10">10</option>
		<option <?php if($options['auh-items'] == "11") { echo "selected"; }?> value="11">11</option>
		<option <?php if($options['auh-items'] == "12") { echo "selected"; }?> value="12">12</option>
		<option <?php if($options['auh-items'] == "13") { echo "selected"; }?> value="13">13</option>
		<option <?php if($options['auh-items'] == "14") { echo "selected"; }?> value="14">14</option>
		<option <?php if($options['auh-items'] == "15") { echo "selected"; }?> value="15">15</option>
		<option <?php if($options['auh-items'] == "16") { echo "selected"; }?> value="16">16</option>
		<option <?php if($options['auh-items'] == "17") { echo "selected"; }?> value="17">17</option>
		<option <?php if($options['auh-items'] == "18") { echo "selected"; }?> value="18">18</option>
		<option <?php if($options['auh-items'] == "19") { echo "selected"; }?> value="19">19</option>
		<option <?php if($options['auh-items'] == "20") { echo "selected"; }?> value="20">20</option>
	</select>
</p>
<p>
	<label for="auh-cache">Cache time (minutes):</label>
	<input type="text" id="auh-cache" name="auh-cache" value="<?php echo $options['auh-cache'];?>" />
</p>
<p>
	<label for="auh-sort">Sorting method</label>
	<select name="auh-sort" id="auh-sort">
	<?php foreach( $sorts as $sort ) print "<option " . (($options['auh-sort'] == $sort) ? "selected" : "") . " value='$sort'>$sort</option>"; ?>
	</select>
</p>
<p>
	<label for="title">Title:</label>
	<input type="text" id="" name="title" value="<?php echo $options['title'];?>" />
</p>
<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

<?php
}
?>
