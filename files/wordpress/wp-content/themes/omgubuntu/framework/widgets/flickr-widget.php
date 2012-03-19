<?php
add_action('widgets_init', 'flickr_load_widgets');

function flickr_load_widgets()
{
	register_widget('Flickr_Widget');
}

class Flickr_Widget extends WP_Widget {
	
	function Flickr_Widget()
	{
		$widget_ops = array('classname' => 'flickr', 'description' => 'The most recent photos from flickr.');

		$control_ops = array('id_base' => 'flickr-widget');

		$this->WP_Widget('flickr-widget', 'Boulevard: Flickr', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$screen_name = $instance['screen_name'];
		$number = $instance['number'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		
		if($screen_name && $number) { ?>
		<div class="flickr">
			<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $screen_name; ?>"></script>
		</div>
		<?php }
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['screen_name'] = $new_instance['screen_name'];
		$instance['number'] = $new_instance['number'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Photos from Flickr', 'screen_name' => '', 'number' => 8);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('screen_name'); ?>">Flickr ID:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo $instance['screen_name']; ?>" />
			<span>Use <a href="http://idgettr.com/">http://idgettr.com</a> to find your flickr ID.</span>
		</p>


		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">Number of photos to show:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		
	<?php
	}
}
?>