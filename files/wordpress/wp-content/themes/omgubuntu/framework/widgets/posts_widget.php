<?php
add_action('widgets_init', 'pyre_posts_load_widgets');

function pyre_posts_load_widgets()
{
	register_widget('Pyre_Posts_Widget');
}

class Pyre_Posts_Widget extends WP_Widget {
	
	function Pyre_Posts_Widget()
	{
		$widget_ops = array('classname' => 'pyre_posts', 'description' => '');

		$control_ops = array('id_base' => 'pyre_posts-widget');

		$this->WP_Widget('pyre_posts-widget', 'Boulevard: Recent Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		$images = true;
		$rating = true;
		
		echo $before_widget;
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'cat' => $categories,
		));
		?>
					
		<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
		<div class="widget-item">
			<?php if(has_post_thumbnail()): ?>
			<?php
			if(has_post_format('video') || has_post_format('audio') || has_post_format('gallery')) {
				$icon = '<span class="thumb-icon-small ' . get_post_format($post->ID) . '"></span>';
			} else {
				$icon = '';
			}
			echo $icon;
			?>
			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'recent-posts-image'); ?>
			<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" class="thumb" /></a>
			<?php endif; ?>
			<h3><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h3>
			<span class="date"><?php the_time('F d, Y'); ?></span>
			<span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span>
		</div>
		<?php endwhile; ?>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		$instance['show_images'] = true;
		$instance['show_rating'] = true;
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 3);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>