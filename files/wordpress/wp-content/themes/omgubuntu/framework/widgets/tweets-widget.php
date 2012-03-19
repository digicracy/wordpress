<?php
add_action('widgets_init', 'tweets_load_widgets');

function tweets_load_widgets()
{
	register_widget('Tweets_Widget');
}

class Tweets_Widget extends WP_Widget {
	
	function Tweets_Widget()
	{
		$widget_ops = array('classname' => 'tweets', 'description' => 'Tweets widget let you display Twitter updates.');

		$control_ops = array('id_base' => 'tweets-widget');

		$this->WP_Widget('tweets-widget', 'Boulevard: Tweets', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$twitter_id = $instance['twitter_id'];
		$number = $instance['number'];
		$follow = $instance['follow'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		
		if($twitter_id) { ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
		    $('#tweets_<?php echo $args['widget_id']; ?>').jtwt({
			    count: <?php echo $number; ?>,
				username: '<?php echo $twitter_id; ?>',
				image_size: 0
			});   
		});          
		</script>
			<div id="tweets_<?php echo $args['widget_id']; ?>" class="twitter"></div>
			<span class='follow'><a href='http://twitter.com/<?php echo $twitter_id; ?>'><?php echo $follow; ?></a></span>
			<?php
		}
		
		echo $after_widget;
	}
	
	
	function make_clickable($tweet)
	{
		// Convert URLs into links
		$tweet = make_clickable($tweet);
		
		// Convert usernames to links
		$tweet = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $tweet);
		
		// Convert hashtags to links
		$tweet = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $tweet);

		return $tweet;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['twitter_id'] = $new_instance['twitter_id'];
		$instance['number'] = $new_instance['number'];
		$instance['follow'] = $new_instance['follow'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Recent Tweets', 'twitter_id' => '', 'number' => 5, 'follow' => 'Follow me on Twitter');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('twitter_id'); ?>">Twitter ID:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" value="<?php echo $instance['twitter_id']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">Number of tweets to show:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('follow'); ?>">Follow message:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('follow'); ?>" name="<?php echo $this->get_field_name('follow'); ?>" value="<?php echo $instance['follow']; ?>" />
		</p>
		
	<?php
	}
}
?>