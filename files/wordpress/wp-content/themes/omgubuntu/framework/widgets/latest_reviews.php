<?php
add_action('widgets_init', 'pyre_latest_reviews_load_widgets');

function pyre_latest_reviews_load_widgets()
{
	register_widget('Pyre_Latest_Reviews_Widget');
}

class Pyre_Latest_Reviews_Widget extends WP_Widget {
	
	function Pyre_Latest_Reviews_Widget()
	{
		$widget_ops = array('classname' => 'pyre_latest_reviews', 'description' => '');

		$control_ops = array('id_base' => 'pyre_latest_reviews-widget');

		$this->WP_Widget('pyre_latest_reviews-widget', 'Boulevard: Latest Reviews', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		$title = $instance['title'];
		$posts = $instance['posts'];
		
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
			'meta_query' => array(
				array(
					'key' => 'pyre_overall_score',
					'value' => '0.5',
					'compare' => '>='
				)
			),
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
			<?php if(
				get_post_meta(get_the_ID(), 'pyre_overall_score', true)
			): ?>
			<div class="post-review-widget">
				<?php if(get_post_meta(get_the_ID(), 'pyre_overall_score', true)): ?>
				<span><img src="<?php bloginfo('template_directory'); ?>/images/stars/<?php echo get_post_meta(get_the_ID(), 'pyre_overall_score', true); ?>.png" alt="" /></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
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
		$instance['posts'] = $new_instance['posts'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Latest Reviews', 'posts' => 3);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>