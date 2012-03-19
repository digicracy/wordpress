<?php
/*
Plugin Name: Ask Ubuntu Random Question Widget
Plugin URI: http://www.omgubuntu.co.uk
Description: Get a random question from Ask Ubuntu
Author: Richard Lyon
Version: 0.1
Author URI: http://richardlyon.co.uk/
License: GPL v3
*/

$AUR_PLUGIN_NAME  = "Ask Ubuntu Random Question";
$AUR_WIDGET_TITLE = "Ask Ubuntu Random Question";

add_action("plugins_loaded", "ask_ubuntu_random_widget_init");

if(!function_exists("ask_ubuntu_random_widget_init"))
{
	function ask_ubuntu_random_widget_init() {
		global $AUR_WIDGET_TITLE;

		ask_ubuntu_random_widget_setup ();
		register_sidebar_widget ($AUR_WIDGET_TITLE, 'ask_ubuntu_random_widget_render');
		register_widget_control ($AUR_WIDGET_TITLE, 'ask_ubuntu_random_widget_preferences');
	}

	function ask_ubuntu_random_widget_render ($args) {
		global $AUR_PLUGIN_NAME;

		ask_ubuntu_random_widget_setup ();
		
		$options = get_option ($AUR_PLUGIN_NAME);
		extract($args);
		$echo_widget = $before_widget;

			$echo_widget .= $before_title;
			$echo_widget .= $options['title'];
			$echo_widget .= $after_title;

		$echo_widget .= ask_ubuntu_random_widget_get_content ();
		$echo_widget .= $after_widget;
		echo $echo_widget;
	}

	function ask_ubuntu_random_widget_get_content () {
		global $AUR_PLUGIN_NAME;
		global $API_KEY;
	
	
		if( !class_exists('CachedAsk') )
		{
			require "class.cachedask.php";
		}
	
		$options = get_option ($AUR_PLUGIN_NAME);

		$tc = new CachedAsk( 'api.askubuntu.com/1.1/questions', $API_KEY, $options['aur-sort'], 100, '&body=true&answers=true', $options['aur-cache'] );
		$questions = $tc->questions;
	
	
		$question = $questions[ floor( rand(0,count($questions)-1) ) ];

		// show question.
		$id    = $question->question_id;
		$title = $question->title;
		$url   = "http://www.askubuntu.com/questions/".$id;
		$text  = auh_paras($question->body, $options['aur-len'], " ... <a href='$url' target='_blank'>(cont.)</a>");
		$user  = (object) $question->owner;
		$uid   = $user->user_id;
		$name  = "<a href='http://www.askubuntu.com/users/$uid' target='_blank'>".$user->display_name."</a>";
		$time = auh_nicetime($question->creation_date);
		
		$content = "<div id='auh-question'><a href='$url' target='_blank'>$title</a>";
		$content .= "<div class='auh-userbox'>asked by <em>$name</em> <span>$time</span></div>";
		$content .= "<span>$text</span></div>";
		
		// is there an answer?
		if( $question->answer_count )
		{
			$max_score = 0;
			$answer = null;
			print "<!--";
			foreach( $question->answers as $a )
			{	
				if( $a->accepted )
				{
					print " A!";
					$answer = $a;
					break;
				}
				$score = $a->score + log($a->owner->reputation);
				print " SCORE:".$a->owner->display_name."+".$a->score."+".$a->owner->reputation."=".$score;
				if( $score > $max_score )
				{
					print " R";
					$max_score = $score;
					$answer = $a;
				}
			}
			print "-->";
		
			$aid   = $answer->answer_id;
			$aurl  = $url."#".$aid;
			$atext = auh_paras($answer->body, $options['aur-len'], " ... <a href='$aurl' target='_blank'>(cont.)</a>");
			$auser = $answer->owner;
			$auid   = $auser->user_id;
			$aname = "<a href='http://www.askubuntu.com/users/$auid' target='_blank'>".$answer->owner->display_name."</a>";
			$atime = auh_nicetime($answer->creation_date);
		
			$content .= "<div id='auh-answer'><b>Best Answer</b><div class='auh-userbox'>answered by <em>$aname</em> <span>$atime</span></div><span>$atext</span></div>";
		}
	
		return $content . "<b><a href='$url' target='_blank'>See full discussion...</a></b>";
	}

	function auh_paras( $input, $len = 300, $append = "" )
	{
		$input = strip_tags( $input, "<p><a><i><em><b>" );
		$paras = explode( "<p>", $input );

		$output = "";
		$i = 0;
		while( (strlen($output) + strlen($paras[0]) - 8) < $len && count($paras) && $i++ < 30 )
		{
			$output .= array_shift($paras);
		}

		if( count($paras) )
		{
			$last = array_shift($paras);
			$rem  = $len - strlen($output);

			$rem = max( $rem, strlen($last) -4 );

			$end = substr($last, 0, $rem);

			$preg = "/.*(<[^>]*)$/m";
			if( preg_match( $preg, $end, $m ))
				$end = substr( $last, 0, 0 - strlen($m[1]) );
		
			$output .= $end;
		}
		else
		{
			$append = "";
		}
	
		$output = substr(trim($output),0,-4) . $append . "</p>";
		
		return str_replace( array("<!--","-->"), "", $output );
	}

	function auh_nicetime( $input )
	{
		$diff = time() - $input;
		if( $diff < 3600 )          $time = floor($diff/60) . " minutes ago";
		else if ( $diff < 86400 )   $time = floor($diff/3600) . " hours ago";
		else if ( $diff < 604800 )  $time = "on ".date("l", $q->creation_date);
		else if ( $diff < 2592000 ) $time = floor($secs/86400) . " days ago";
		else                        $time = floor($secs/604800) . " weeks ago";
	
		return $time;
	}

	function ask_ubuntu_random_widget_setup () {
		global $AUR_PLUGIN_NAME;
	
		$options = get_option ($AUR_PLUGIN_NAME);
		if (!is_array ($options) || empty ($options["title"]) /*|| empty ($options["aur-top-colour"]) || empty ($options["aur-bot-colour"])*/) {
			$options = array ("title" => "random question",
						"aur-sort" => 'hot',
						"aur-cache" => 10,
						"aur-len" => 500
					  );
			update_option ($AUR_PLUGIN_NAME, $options);
		 }
	}

	function ask_ubuntu_random_widget_preferences () {
		global $AUR_PLUGIN_NAME;
		$options = get_option ($AUR_PLUGIN_NAME);

		$sorts = array( 'hot', 'activity', 'votes', 'creation', 'featured', 'week', 'month' );

		if ($_POST["submit-settings"]) {
			$options["aur-cache"] = (int)$_POST['aur-cache'];
			$options['title'] = htmlspecialchars ($_POST['title']);
			$options["aur-len"] = (int) $_POST['aur-len'];
			$options['aur-sort'] = in_array( $_POST['aur-sort'], $sorts ) ? $_POST['aur-sort'] : 'hot';
		}

		update_option($AUR_PLUGIN_NAME, $options);
	?>
	<p>
		<label for="aur-cache">Cache time (minutes):</label>
		<input type="text" id="aur-cache" name="aur-cache" value="<?php echo $options['aur-cache'];?>" />
	</p>
	<p>
		<label for="aur-len">Content length limit (characters):</label>
		<input type="text" id="aur-len" name="aur-len" value="<?php echo $options['aur-len'];?>" />
	</p>
	<p>
		<label for="aur-sort">Sorting method</label>
		<select name="aur-sort" id="aur-sort">
		<?php foreach( $sorts as $sort ) print "<option " . (($options['aur-sort'] == $sort) ? "selected" : "") . " value='$sort'>$sort</option>"; ?>
		</select>
	</p>
	<p>
		<label for="title">Title:</label>
		<input type="text" id="" name="title" value="<?php echo $options['title'];?>" />
	</p>
	<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

	<?php
	}
}
?>
