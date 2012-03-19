<?php
/*
Plugin Name: Most Read Posts Widget
Plugin URI: http://www.omgubuntu.co.uk
Description: Get most read posts using that post count backend thing.
Author: Niall Molloy
Version: 1.6
Author URI: http://purplejam.co.uk
License: GPL v3
*/

$MRP_PLUGIN_NAME  = "Most Read Posts Widget";
$MRP_WIDGET_TITLE = "Most Read Posts";

add_action("plugins_loaded", "most_read_posts_widget_init");

/*function get_step($top,$bot,$items) {
	if ( $step = (hexdec($top)-hexdec($bot)) ) return $step/($items-2);
	else return 0;
}

function create_gradient ($start_colour,$end_colour,$step_no) {

	$rgb = str_split("$start_colour$end_colour",2);
	$rgb = array(array(hexdec($rgb[0]), get_step($rgb[0],$rgb[3],$step_no)),
		      array(hexdec($rgb[1]), get_step($rgb[1],$rgb[4],$step_no)),
		      array(hexdec($rgb[2]), get_step($rgb[2],$rgb[5],$step_no)));
		      
	$colours = array('#'.$start_colour);

	for ( $i = 0; $i < $step_no; $i++ ) {

		$colour = '#';

		foreach ($rgb as $k => $v) {
			$rgb[$k][0] -= $v[1]; 
			if ( $v[0] < 16 ) $colour .= '0'.dechex($v[0]);
			else $colour .= dechex($v[0]);
		}

		array_push($colours,$colour);
	}
	
	return $colours;
}*/

function most_read_posts_widget_init() {
	global $MRP_WIDGET_TITLE;

	most_read_posts_widget_setup ();
	register_sidebar_widget ($MRP_WIDGET_TITLE, 'most_read_posts_widget_render');
	register_widget_control ($MRP_WIDGET_TITLE, 'most_read_posts_widget_preferences');
}

function most_read_posts_widget_render ($args) {
	global $MRP_PLUGIN_NAME;

	most_read_posts_widget_setup ();
	$options = get_option ($MRP_PLUGIN_NAME);
	extract($args);
	$echo_widget = $before_widget;
	if ($options['mrp-titlebar-visible']) {
		$echo_widget .= $before_title;
		$echo_widget .= $options['title'];
		$echo_widget .= $after_title;
	}
	$echo_widget .= most_read_posts_widget_get_content ();
	$echo_widget .= $after_widget;
	echo $echo_widget;
}

function most_read_posts_widget_get_content () {
	global $MRP_PLUGIN_NAME, $wpdb;

	$options = get_option ($MRP_PLUGIN_NAME);

	$popular_posts = $wpdb->get_results("

	SELECT p.ID, p.post_title, u.display_name
	FROM $wpdb->posts as p
	LEFT JOIN $wpdb->users AS u ON p.post_author = u.ID
	WHERE post_date   >  DATE_SUB(CURDATE(), INTERVAL 2 WEEK) 
	  AND post_status = 'publish'
	  AND post_type   = 'post'
	ORDER BY YEAR(post_date) DESC, 
		 WEEK(post_date) DESC,
		 p.comment_count DESC 
	LIMIT {$options["mrp-items"]}

	");

	$i = 1;

	/*$top_size = 100;
	$bot_size = 45;

	$step_size  = ($top_size-$bot_size)/$options["mrp-items"];
	$colours = create_gradient ($options['mrp-top-colour'],$options['mrp-bot-colour'],$options["mrp-items"]);*/

	$content = "<ul>";

	foreach($popular_posts as $post) {

		$content   .= "<li><a "/*style=\"font-size:" . $top_size . "%;color:".$colours[$i].";\" */."title=\"Read '{$post->post_title}' by {$post->display_name}\" href=\"".get_bloginfo( 'url' )."?p={$post->ID}\">{$post->post_title}<span class=\"post-rank\">$i</span></a></li>";
		$i++;
	}
	return $content . "</ul>";
}

function most_read_posts_widget_setup () {
	global $MRP_PLUGIN_NAME;

	$options = get_option ($MRP_PLUGIN_NAME);
	if (!is_array ($options) || empty ($options["title"]) /*|| empty ($options["mrp-top-colour"]) || empty ($options["mrp-bot-colour"])*/) {
		$options = array ("title" => "Top Posts",
				   "mrp-items" => 10,
				   "mrp-titlebar-visible" => true/*,
				   "mrp-top-colour" => "F15722",
				   "mrp-bot-colour" => "F19B22"*/
				  );
		update_option ($MRP_PLUGIN_NAME, $options);
    }
}

function most_read_posts_widget_preferences () {
	global $MRP_PLUGIN_NAME;
	$options = get_option ($MRP_PLUGIN_NAME);

	if ($_POST["submit-settings"]) {
		$options["mrp-items"] = (int)$_POST['mrp-items'];
		#$options["mrp-top-colour"] = $_POST['mrp-top-colour'];
		#$options["mrp-bot-colour"] = $_POST['mrp-bot-colour'];
		if ($_POST["mrp-titlebar-visible"]) $options["mrp-titlebar-visible"] = true;
		else $options["mrp-titlebar-visible"] = false;
		$options['title'] = htmlspecialchars ($_POST['title']);
	}

	update_option($MRP_PLUGIN_NAME, $options);
?>

<p>
	<label for="mrp-items">Number of items to display (Max 20):</label>
	<select name="mrp-items" id="mrp-items">
		<option <?php if($options['mrp-items'] == "1") { echo "selected"; }?> value="1">1</option>
		<option <?php if($options['mrp-items'] == "2") { echo "selected"; }?> value="2">2</option>
		<option <?php if($options['mrp-items'] == "3") { echo "selected"; }?> value="3">3</option>
		<option <?php if($options['mrp-items'] == "4") { echo "selected"; }?> value="4">4</option>
		<option <?php if($options['mrp-items'] == "5") { echo "selected"; }?> value="5">5</option>
		<option <?php if($options['mrp-items'] == "6") { echo "selected"; }?> value="6">6</option>
		<option <?php if($options['mrp-items'] == "7") { echo "selected"; }?> value="7">7</option>
		<option <?php if($options['mrp-items'] == "8") { echo "selected"; }?> value="8">8</option>
		<option <?php if($options['mrp-items'] == "9") { echo "selected"; }?> value="9">9</option>
		<option <?php if($options['mrp-items'] == "10") { echo "selected"; }?> value="10">10</option>
		<option <?php if($options['mrp-items'] == "11") { echo "selected"; }?> value="11">11</option>
		<option <?php if($options['mrp-items'] == "12") { echo "selected"; }?> value="12">12</option>
		<option <?php if($options['mrp-items'] == "13") { echo "selected"; }?> value="13">13</option>
		<option <?php if($options['mrp-items'] == "14") { echo "selected"; }?> value="14">14</option>
		<option <?php if($options['mrp-items'] == "15") { echo "selected"; }?> value="15">15</option>
		<option <?php if($options['mrp-items'] == "16") { echo "selected"; }?> value="16">16</option>
		<option <?php if($options['mrp-items'] == "17") { echo "selected"; }?> value="17">17</option>
		<option <?php if($options['mrp-items'] == "18") { echo "selected"; }?> value="18">18</option>
		<option <?php if($options['mrp-items'] == "19") { echo "selected"; }?> value="19">19</option>
		<option <?php if($options['mrp-items'] == "20") { echo "selected"; }?> value="20">20</option>
	</select>
</p>
<?php/*<p>
	<label for="mrp-top-colour">Text Top Colour:</label>
	<input type="text" id="mrp-top-colour" name="mrp-top-colour" value="<?php echo $options['mrp-top-colour'];?>" />
</p>
<p>
	<label for="mrp-bot-colour">Text Bottom Colour:</label>
	<input type="text" id="mrp-bot-colour" name="mrp-bot-colour" value="<?php echo $options['mrp-bot-colour'];?>" />
</p>
<p>*/?>
	<label for="mrp-titlebar-visible">Display Widget Title Bar:</label>
	<input type="checkbox" id="mrp-titlebar-visible" name="mrp-titlebar-visible" value="mrp-titlebar-visible" <?php if($options['mrp-titlebar-visible']) { echo "checked"; }?>/>
</p>
<p>
	<label for="title">Title:</label>
	<input type="text" id="" name="title" value="<?php echo $options['title'];?>" />
</p>
<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

<?php
}
?>