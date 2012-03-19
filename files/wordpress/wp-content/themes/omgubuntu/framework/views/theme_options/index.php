<?php include_once('header.php'); ?>

<form action='' enctype='multipart/form-data'>
	<div class='top_button'>
		<img class='save_tip' style='display: none;' src='<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/save_tip.png' alt='' />
		<input type='submit' name='save_changes' value='' class='save_changes' />
	</div>
	<div style='clear: both;'></div>
	<div id='general_settings' class='mainTab'>
		<div id='general'>
			<?php $this->upload('logo', 'Logo', 'Upload your logo'); ?>
			<?php $this->upload('favicon', 'Favicon', 'Upload your Favicon'); ?>
			<?php $this->textarea('header_banner', 'Header Banner Code', ''); ?>
			<?php $this->text('feedburner', 'Feedburner URL', ''); ?>
		</div>
		<div id='analytics' style='display: none;'>
			<?php $this->textarea('analytics', 'Analaytics Code', ''); ?>
		</div>
		<div id='social_media' style='display: none;'>
			<?php $this->text('twitter_id', 'Twitter ID', ''); ?>
			<?php $this->text('facebook_id', 'Facebook ID', 'If your facebook page URL is http://facebook.com/cocacola, your facebook id is "coacola". If your facebook page ID has numbers for e.g: http://facebook.com/cocacola/7846745634632 then your facebook ID is "7846745634632".'); ?>
		</div>
		<div id='theme_footer' style='display: none;'>
			<?php $this->textarea('footer_left', 'Footer Text Left', ''); ?>
			<?php $this->textarea('footer_right', 'Footer Text Right', ''); ?>
		</div>
	</div>
	<div id='homepage_settings' style='display: none;' class='mainTab'>
		<?php $this->checkbox('featured_slider', 'Show featured slider'); ?>
		<?php $this->text('featured_posts', 'Featured Posts Count', ''); ?>
		<?php $this->text('featured_tag', 'Featured Posts Slider Tag', 'Posts with tag in this field will show up on homepage featured posts slider.'); ?>
		<?php $this->select('slider_effect', array(
			'random' => 'Random',
			'sliceDown' => 'Slice Down',
			'sliceDownLeft' => 'Slice Down Left',
			'sliceUp' => 'Slice Up',
			'sliceUpLeft' => 'Slice Up Left',
			'sliceUpDown' => 'Slice Up Down',
			'sliceUpDownLeft' => 'Slice Up Down Left',
			'fold' => 'Fold',
			'fade' => 'Fade',
			'slideInRight' => 'Slide In Right',
			'slideInLeft' => 'Slide In Left',
			'boxRandom' => 'Box Random',
			'boxRain' => 'Box Rain',
			'boxRainReverse' => 'Box Rain Reverse',
			'boxRainGrow' => 'Box Rain Grow',
			'boxRainGrowReverse' => 'Box Rain Grow Reverse',
		),
		'Slider Effect'); ?>
		<?php $this->text('slider_speed', 'Slider Speed', ''); ?>
	</div>
	<div id='posts_settings' style='display: none;' class='mainTab'>
		<?php $this->checkbox('posts_navigation', 'Show posts navigation'); ?>
		<?php $this->checkbox('posts_featured', 'Show featured image on posts'); ?>
		<?php $this->checkbox('author', 'Show author info box on posts'); ?>
		<?php $this->checkbox('tags', 'Show tags on posts'); ?>
		<?php $this->checkbox('categories', 'Show categories on posts'); ?>
		<?php $this->checkbox('related', 'Show related posts box on posts'); ?>
		<?php $this->checkboxes(array(
			'twitter' => 'Twitter',
			'facebook' => 'Facebook',
			'digg' => 'Digg',
			'stumbleupon' => 'StumbleUpon',
			'reddit' => 'Reddit',
			'tumblr' => 'Tumblr',
			'email' => 'Email',
			'google' => 'Google +1',
		),
		'Social Media Support'); ?>
	</div>
	<div id='appearence_settings' style='display: none;' class='mainTab'>
		<?php $this->colorpicker('top_nav_color', 'Top Navigation Color'); ?>
		
		<?php $this->colorpicker('main_nav_color', 'Main Navigation Color'); ?>
		
		<?php $this->colorpicker('bg_color', 'Background Color'); ?>
		
		<?php $this->colorpicker('link_color', 'Link Color'); ?>
		
		<?php $this->textarea('custom_css', 'Custom CSS', ''); ?>
		
		<?php $this->textarea('custom_js', 'Custom Javascript', ''); ?>
	</div>
	<div class='reset_save'>
		<div class='reset_button'>
			<input onclick='return confirm("Click OK to reset. Any settings will be lost!");' type='submit' name='reset' value='' class='reset_btn' />
			<img class='reset_tip' style='display: none;' src='<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/reset_tip.png' alt='' />
		</div>
		<div class='bottom_button'>
			<img class='save_tip' style='display: none;' src='<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/save_tip.png' alt='' />
			<input type='submit' name='save_changes' value='' class='save_changes' />
		</div>
		<div style='clear: both;'></div>
	</div>
	<div style='clear: both;'></div>
</form>

<?php include_once('footer.php'); ?>