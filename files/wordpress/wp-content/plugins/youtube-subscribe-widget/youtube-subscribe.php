<?php
/*
Plugin Name: YouTube Subscribe widget
Plugin URI: 
Description: Adds a sidebar widget to show a YouTube number of subscribers, number of videos details and SUBSCRIBE button
Author: Milena Dimitrova
Version: 1.0
Author URI: http://flatrocktech.com
*/

// Based on the youtube widget by James Wilson.

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_youtube_subscribe_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_youtube_subscribe($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
				
		$widget = '';

		$widget.="<iframe src='http://www.youtube.com/subscribe_widget?p=%username%' style='border: 1px solid red; overflow: hidden; height: %height%px; width: %width%px; border: none;' scrolling='no' frameborder='0'></iframe>";

		
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_youtube_subscribe');
		$username  =  $options['username'];
		$width = $options['width'];
		$height = $options['height'];
		// this could be a linked title opening the youtube profile in a new tab/window
		$titlelink = ($options['titlelink'] == '1') ? '&titlelink=1' : '';
		if ($titlelink)
		{
			$title  = "<h3 class='widget-title'><a href='http://www.youtube.com/user/".$options['username']."' target='_blank'>".$options['title']."</a></h3>"; 
		} else {
			$title  = "<h3 class='widget-title'>".$options['title']."</h3>"; 
		}
		
		$content = str_replace(
			array(
				"%title%",
				"%username%",
				"%width%",
				"%height%"
			), 
			array(
				$title,
				$username,
				$width,
				$height
			), 
			$widget
		);
		
		echo $before_widget;
		echo $title;
		echo $content;
		echo $after_widget;
	
	}





	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_youtube_subscribe_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_youtube_subscribe');
		if ( !is_array($options) )
			$options = array('title'=>'Find us on YouTube', 
				'username'=> 'milenadimitrova',
				'width' => '200',
				'height' => '200',
				'titlelink' => '0'
			);

		if ( $_POST['youtube-subscribe-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['username'] = strip_tags(stripslashes($_POST['youtube-subscribe-username']));
			$options['title'] = strip_tags(stripslashes($_POST['youtube-subscribe-title']));
			$options['width'] = strip_tags(stripslashes($_POST['youtube-subscribe-width']));
			$options['height'] = strip_tags(stripslashes($_POST['youtube-subscribe-height']));
			$options['titlelink'] = ($_POST['youtube-titlelink'] == "on" ) ? "1" : "0";			
					
			update_option('widget_youtube_subscribe', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$username = htmlspecialchars($options['username'], ENT_QUOTES);
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'], ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		$titlelink = htmlspecialchars($options['titlelink'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form as this will be embedded into the existing form.
		
		echo '<p style="text-align:right;"><label for="youtube-subscribe-username">' . __('Username:') . ' <input style="width: 200px;" id="youtube-subscribe-username" name="youtube-subscribe-username" type="text" value="'.$username.'" /></label></p>';		
		echo '<p style="text-align:right;"><label for="youtube-subscribe-title">' . __('Title:') . ' <input style="width: 200px;" id="youtube-subscribe-title" name="youtube-subscribe-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-subscribe-width">' . __('Width:', 'widgets') . ' <input style="width: 200px;" id="youtube-subscribe-width" name="youtube-subscribe-width" type="text" value="'.$width.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-subscribe-height">' . __('Height:', 'widgets') . ' <input style="width: 200px;" id="youtube-subscribe-height" name="youtube-subscribe-height" type="text" value="'.$height.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="youtube-titlelink">' . __('Link Title:', 'widgets') . ' <input id="youtube-titlelink" name="youtube-titlelink" type="checkbox" ' . (  ($titlelink == '1') ? "checked=\"checked\"" : "" ) . '</label></p>';
		echo '<input type="hidden" id="youtube-subscribe-submit" name="youtube-subscribe-submit" value="1" />';
	}
	

	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('YouTube', 'widgets'), 'widget_youtube_subscribe');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('YouTube', 'widgets'), 'widget_youtube_subscribe_control', 300, 200);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_youtube_subscribe_init');

?>