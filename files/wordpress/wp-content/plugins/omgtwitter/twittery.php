<?php
/*
Plugin Name: OMGTwit Widget
Plugin URI: http://www.omgubuntu.co.uk
Description: Engadget-style Twitter sidebar widget
Author: Richard Lyon
Version: 0.1
Author URI: http://richardlyon.co.uk/
License: GPL v3
*/

$TWI_PLUGIN_NAME  = "OMGTwitter Widget";
$TWI_WIDGET_TITLE = "OMGTwitter Widget";

add_action("plugins_loaded", "omgtwit_widget_init");

function omgtwit_widget_init() {
	global $TWI_WIDGET_TITLE;

	omgtwit_widget_setup ();
	register_sidebar_widget ($TWI_WIDGET_TITLE, 'omgtwit_widget_render');
	register_widget_control ($TWI_WIDGET_TITLE, 'omgtwit_widget_preferences');
}

function omgtwit_widget_render ($args) {
	global $TWI_PLUGIN_NAME;

	omgtwit_widget_setup ();
	$options = get_option ($TWI_PLUGIN_NAME);
	extract($args);
	$echo_widget = $before_widget;

		$echo_widget .= $before_title;
		$echo_widget .= $options['title'];
		$echo_widget .= $after_title;

	$echo_widget .= omgtwit_widget_get_content ();
	$echo_widget .= $after_widget;
	echo $echo_widget;
}

function omgtwit_widget_get_content () {
	global $TWI_PLUGIN_NAME;
	global $col;

	$options = get_option($TWI_PLUGIN_NAME);
		
	if( !class_exists('CachedTwitter') )
		require_once("class.twitter.php");
		
	$tc = new CachedTwitter( 'http://api.twitter.com/1/statuses/user_timeline.json?include_entities=1&contributor_details=true&include_rts=true&user_id=72915446', $options['twi-cache'] );
	
	// fall back to shit sorting if magic is dead
	$sum = 0;
	foreach($tc->tweets as $t) $sum += $t->retweets;
	$col = ($sum > 0 ? "retweets" : "count");

	$max = 0;
	for( $i = 0; $i < $options['twi-items']; $i++ )
	{
		if( strpos( $rc->tweets->url, "twitter" ) !== false ) continue;
		$max = max($max,$tc->tweets[$i]->$col);
		$tweets[] = $tc->tweets[$i];
	}

	function cmp($a,$b) { global $col; return ($a->$col < $b->$col); }
	usort( $tweets, 'cmp' );
		
	$content .= "<div id='twitter_head'>";
		$content .= "<span>Join the discussion with <a target='_blank' href='http://www.twitter.com/".$tc->owner->screen_name."/followers'>".number_format($tc->owner->followers)."</a> followers</span>";
		$content .= "<a href='http://www.twitter.com/".$tc->owner->screen_name."' target='_blank'>@".$tc->owner->screen_name."</a>";
	$content .= "</div>";
	$content .= "<div id='twitter_body'>";
	
	$sc = $options['twi-scale'];
	
	$opacity_scale = 0.5 / $options['twi-items'];
	$width_ratio = 100 / ($max * $sc);
	$i = 0;
	
	## max hex-pattern #ABC or #123456	
	if( !preg_match( "/^#?((([0-9A-F])([0-9A-F])([0-9A-F]))|(([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})))$/i", $options['twi-color'], $bits ) )
		# if no match, fallback
		$bits = array( 0,0, 0,0,0,0, "1CA0B7", "1C", "A0", "B7" );

	# if not enough matches (would this ever occur?) fallback
	if( count($bits) < 6 )
			$bits = array( 0,0, 0,0,0,0, "1CA0B7", "1C", "A0", "B7" );
	
	# if 3-char code, double up
	if( count($bits) < 10 )
		list( $r, $g, $b ) = array( $bits[3].$bits[3], $bits[4].$bits[4], $bits[5].$bits[5] );
	# else just extract
	else
		list( $r, $g, $b ) = array( $bits[7], $bits[8], $bits[9] );	
	
	$r = hexdec($r);
	$g = hexdec($g);
	$b = hexdec($b);
	
	foreach( $tweets as $tweet )
	{
		if( strlen($tweet->text) > 105 )
			$tweet->text = substr($tweet->text,0,100)."...";
		if( strlen($tweet->text) < 50 )
			$tweet->text .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
		$boxname = $options['twi-box'];
		if( ! isset( $tweet->$boxname ) )
			$boxname = "age";

		$box = $tweet->$boxname;
		if( $boxname == "age" ) $box .= " hours ago";
		if( $boxname == "retweets" ) $box .= " retweets";
		
		$width = max( 30, ($width_ratio * (($max * $sc) - $max + $tweet->$col)) )."%";
		$color = "rgba($r,$g,$b,".round( 1 - ($i++ * $opacity_scale), 3 ).")";
		$content .= "<div style='width: $width; background-color: $color;'><a href='".$tweet->url."'>";
		$content .= "<span class='num'>".$box."</span>";
		$content .= "<span class='text'>".$tweet->text."</span>";
		$content .= "</a></div>\n";
	}
	
	$content .= "</div>";
	
	return $content;
}

function omgtwit_widget_setup () {
	global $TWI_PLUGIN_NAME;
	
	$options = get_option ($TWI_PLUGIN_NAME);
	if (!is_array ($options) || empty ($options["title"]) /*|| empty ($options["twi-top-colour"]) || empty ($options["twi-bot-colour"])*/) {
		$options = array ("title" => "trending on twitter",
				   "twi-items" => 5,
				   "twi-cache" => 10,
				   'twi-color' => '#33CCFF',
				   'twi-box' => 'age',
				   'twi-scale' => 5
				  );
		update_option ($TWI_PLUGIN_NAME, $options);
    }
}

function omgtwit_widget_preferences () {
	global $TWI_PLUGIN_NAME;
	$options = get_option ($TWI_PLUGIN_NAME);

	if ($_POST["submit-settings"]) {
		$options["twi-items"] = (int)$_POST['twi-items'];
		$options["twi-cache"] = (int)$_POST['twi-cache'];
		
		$options['twi-color'] = $_POST['twi-color'];
		$options['twi-box'] = $_POST['twi-box'];
		$options['twi-scale'] = (int)$_POST['twi-scale'];
		
		$options['title'] = htmlspecialchars ($_POST['title']);
	}

	update_option($TWI_PLUGIN_NAME, $options);
?>

<p>
	<label for="twi-items">Number of tweets (Max 10):</label>
	<select name="twi-items" id="twi-items">
		<option <?php if($options['twi-items'] == "1") { echo "selected"; }?> value="1">1</option>
		<option <?php if($options['twi-items'] == "2") { echo "selected"; }?> value="2">2</option>
		<option <?php if($options['twi-items'] == "3") { echo "selected"; }?> value="3">3</option>
		<option <?php if($options['twi-items'] == "4") { echo "selected"; }?> value="4">4</option>
		<option <?php if($options['twi-items'] == "5") { echo "selected"; }?> value="5">5</option>
		<option <?php if($options['twi-items'] == "6") { echo "selected"; }?> value="6">6</option>
		<option <?php if($options['twi-items'] == "7") { echo "selected"; }?> value="7">7</option>
		<option <?php if($options['twi-items'] == "8") { echo "selected"; }?> value="8">8</option>
		<option <?php if($options['twi-items'] == "9") { echo "selected"; }?> value="9">9</option>
		<option <?php if($options['twi-items'] == "10") { echo "selected"; }?> value="10">10</option>
	</select>
</p>

<p>
	<label for="twi-scale">Width scaling (rec. 5):</label>
	<select name="twi-scale" id="twi-scale">
		<option <?php if($options['twi-scale'] == "1") { echo "selected"; }?> value="1">1</option>
		<option <?php if($options['twi-scale'] == "2") { echo "selected"; }?> value="2">2</option>
		<option <?php if($options['twi-scale'] == "3") { echo "selected"; }?> value="3">3</option>
		<option <?php if($options['twi-scale'] == "4") { echo "selected"; }?> value="4">4</option>
		<option <?php if($options['twi-scale'] == "5") { echo "selected"; }?> value="5">5</option>
		<option <?php if($options['twi-scale'] == "6") { echo "selected"; }?> value="6">6</option>
		<option <?php if($options['twi-scale'] == "7") { echo "selected"; }?> value="7">7</option>
		<option <?php if($options['twi-scale'] == "8") { echo "selected"; }?> value="8">8</option>
		<option <?php if($options['twi-scale'] == "9") { echo "selected"; }?> value="9">9</option>
		<option <?php if($options['twi-scale'] == "10") { echo "selected"; }?> value="10">10</option>
	</select>
</p>


<p>
	<label for="twi-box">RH Box contents:</label>
	<select name="twi-box" id="twi-box">
		<?php $sorts = array( 'age', 'retweets', 'url' ); ?>
		<?php foreach( $sorts as $sort ) print "<option " . (($options['twi-box'] == $sort) ? "selected" : "") . " value='$sort'>$sort</option>"; ?>
	</select>
</p>

<p>
	<label for="twi-color">Background color (eg #1CA0B7):</label>
	<input type="text" id="twi-color" name="twi-color" value="<?php echo $options['twi-color'];?>" />
</p>

<p>
	<label for="twi-cache">Cache time (minutes):</label>
	<input type="text" id="twi-cache" name="twi-cache" value="<?php echo $options['twi-cache'];?>" />
</p>
<p>
	<label for="title">Title:</label>
	<input type="text" id="" name="title" value="<?php echo $options['title'];?>" />
</p>
<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

<?php
}
?>
