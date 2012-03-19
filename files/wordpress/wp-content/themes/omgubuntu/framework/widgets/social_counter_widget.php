<?php
add_action('widgets_init', 'pyre_social_counter_load_widgets');

function pyre_social_counter_load_widgets()
{
	register_widget('Pyre_Social_Counter_Widget');
}

class Pyre_Social_Counter_Widget extends WP_Widget {
	
	function Pyre_Social_Counter_Widget()
	{
		$widget_ops = array('classname' => 'pyre_social_counter', 'description' => 'Show number of RSS subscribes, twitter followers and facebook fans.');

		$control_ops = array('id_base' => 'pyre_social_counter-widget');

		$this->WP_Widget('pyre_social_counter-widget', 'Boulevard: Social Counter', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = $instance['title'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}		
		?>
		<!-- BEGIN WIDGET -->		
		<?php if(get_option('pyre_facebook_id')): ?>
		<div class="social-box">
			<?php
			$interval = 3600;
			
			if($_SERVER['REQUEST_TIME'] > get_option('pyre_facebook_cache_time')) {
				@$api = wp_remote_get('http://graph.facebook.com/' . get_option('pyre_facebook_id'));
				@$json = json_decode($api['body']);
				
				if($json->likes >= 1) {
					update_option('pyre_facebook_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
					update_option('pyre_facebook_followers', $json->likes);
					update_option('pyre_facebook_link', $json->link);
				}
			}
			?>
			
			<a href='<?php echo get_option('pyre_facebook_link'); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/facebook.png" alt="Fan us on Facebook"  width='48' height='48' /></a>
			
			<div class="social-box-text">
				<span class="social-arrow"></span>
				<span class="social-box-descrip"><?php _e('Connect on Facebook', 'pyre'); ?></span>
				<span class="social-box-count"><?php echo get_option('pyre_facebook_followers'); ?> <?php _e('Fans', 'pyre'); ?></span>
			</div>
		</div>
		<?php endif; ?>
		
		<?php if(get_option('pyre_twitter_id')): ?>
		<div class="social-box">
			<a href='http://twitter.com/<?php echo get_option('pyre_twitter_id'); ?>'><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" alt="Follow on Twitter" width="48" height="48" /></a>
			<?php
			$interval = 3600;
			
			if($_SERVER['REQUEST_TIME'] > get_option('pyre_twitter_cache_time')) {
				@$api = wp_remote_get('http://twitter.com/statuses/user_timeline/' . get_option('pyre_twitter_id') . '.json');
				@$json = json_decode($api['body']);
				
				if(@$api['headers']['x-ratelimit-remaining'] >= 1) {
					update_option('pyre_twitter_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
					update_option('pyre_twitter_followers', $json[0]->user->followers_count);
				}
			}
			?>
			<div class="social-box-text">
				<span class="social-arrow"></span>
				<span class="social-box-descrip"><?php _e('Follow on Twitter', 'pyre'); ?></span>
				<span class="social-box-count"><?php echo get_option('pyre_twitter_followers'); ?> <?php _e('Followers', 'pyre'); ?></span>
			</div>
		</div>
		<?php endif; ?>
		
		<?php
		if(get_option('pyre_feedburner')) {
			$rss = get_option('pyre_feedburner');
		} else {
			$rss = get_bloginfo('rss2_url');
		}
		?>
		<div class="social-box">
			<a href='<?php echo $rss; ?>'><img src="<?php echo get_template_directory_uri(); ?>/images/rss.png" alt="Subsribe to RSS" width="48" height="48" /></a>
			
			<?php
			if(get_option('pyre_feedburner')) {
				$interval = 43200;
				
				if($_SERVER['REQUEST_TIME'] > get_option('pyre_feedburner_cache_time')) {
					@$api = wp_remote_get('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=' . get_option('pyre_feedburner'));
					@$xml = new SimpleXmlElement($api['body'], LIBXML_NOCDATA);
					@$feedburner_followers = (string) $xml->feed->entry['circulation'];
					
					if($feedburner_followers >= 1) {
						update_option('pyre_feedburner_cache_time', $_SERVER['REQUEST_TIME'] + $interval);
						update_option('pyre_feedburner_followers', $feedburner_followers);
					}
				}
			}
			?>
			
			<div class="social-box-text">
				<span class="social-arrow"></span>
				<span class="social-box-descrip"><?php _e('Subscribe to RSS Feed', 'pyre'); ?></span>
				<?php if(get_option('pyre_feedburner_followers')): ?>
				<span class="social-box-count"><?php echo get_option('pyre_feedburner_followers'); ?> <?php _e('Subscribers', 'pyre'); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Subscribe & Follow');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php }
}
?>