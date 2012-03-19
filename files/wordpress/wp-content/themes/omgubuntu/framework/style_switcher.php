<style type='text/css'>
#style-switcher {
	position: fixed; top: 55px; right: 0;
	width: 156px;
}

#style-switcher-container {
	float: right;
	
	background: #ffffff;
	width: 126px;
	border: 1px solid #c1c1c1; border-right: 0;
	
	-webkit-border-top-left-radius: 8px;
	-webkit-border-bottom-left-radius: 8px;
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-bottomleft: 8px;
	border-top-left-radius: 8px;
	border-bottom-left-radius: 8px;

	text-align: center;
}

#style-switcher .hide_switcher {
}

#style-switcher .show_switcher {
	display: none;
}

#style-switcher .hide_switcher img, #style-switcher .show_switcher img { border: 0; float: right; margin: 0; margin-top: 20px; }

#style-switcher-container > div {
	padding-bottom: 15px;
}

#style-switcher h3.first-child {
	-webkit-border-top-left-radius: 8px;
	-moz-border-radius-topleft: 8px;
	border-top-left-radius: 8px;

	border-top: 0;
}

#style-switcher h3 {
	background: url('<?php bloginfo('template_directory'); ?>/framework/views/style_switcher/header_bg.png') repeat-x top left;
	height: 30px;
	line-height: 30px;
	
	font-size: 13px;
	color: #797979;
	text-align: left;
	text-indent: 10px;
	
	border: 1px solid #dedede;
	border-left: 0; border-right: 0;
	margin-bottom: 10px;
}

/* Color Picker */

.colorpicker {
	width: 356px;
	height: 176px;
	overflow: hidden;
	position: absolute;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_background.png);
	font-family: Arial, Helvetica, sans-serif;
	display: none;
	z-index: 10;
}
.colorpicker_color {
	width: 150px;
	height: 150px;
	left: 14px;
	top: 13px;
	position: absolute;
	background: #f00;
	overflow: hidden;
	cursor: crosshair;
}
.colorpicker_color div {
	position: absolute;
	top: 0;
	left: 0;
	width: 150px;
	height: 150px;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_overlay.png);
}
.colorpicker_color div div {
	position: absolute;
	top: 0;
	left: 0;
	width: 11px;
	height: 11px;
	overflow: hidden;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_select.gif);
	margin: -5px 0 0 -5px;
}
.colorpicker_hue {
	position: absolute;
	top: 13px;
	left: 171px;
	width: 35px;
	height: 150px;
	cursor: n-resize;
}
.colorpicker_hue div {
	position: absolute;
	width: 35px;
	height: 9px;
	overflow: hidden;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_indic.gif) left top;
	margin: -4px 0 0 0;
	left: 0px;
}
.colorpicker_new_color {
	position: absolute;
	width: 60px;
	height: 30px;
	left: 213px;
	top: 13px;
	background: #f00;
}
.colorpicker_current_color {
	position: absolute;
	width: 60px;
	height: 30px;
	left: 283px;
	top: 13px;
	background: #f00;
}
.colorpicker input {
	background-color: transparent;
	border: 1px solid transparent;
	position: absolute;
	font-size: 10px;
	font-family: Arial, Helvetica, sans-serif;
	color: #898989;
	top: 4px;
	right: 11px;
	text-align: right;
	margin: 0;
	padding: 0;
	height: 12px;
}
.colorpicker_hex {
	position: absolute;
	width: 72px;
	height: 22px;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_hex.png) top;
	left: 212px;
	top: 142px;
}
.colorpicker_hex input {
	right: 6px;
}
.colorpicker_field {
	height: 22px;
	width: 62px;
	background-position: top;
	position: absolute;
}
.colorpicker_field span {
	position: absolute;
	width: 12px;
	height: 22px;
	overflow: hidden;
	top: 0;
	right: 0;
	cursor: n-resize;
}
.colorpicker_rgb_r {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_rgb_r.png);
	top: 52px;
	left: 212px;
}
.colorpicker_rgb_g {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_rgb_g.png);
	top: 82px;
	left: 212px;
}
.colorpicker_rgb_b {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_rgb_b.png);
	top: 112px;
	left: 212px;
}
.colorpicker_hsb_h {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_hsb_h.png);
	top: 52px;
	left: 282px;
}
.colorpicker_hsb_s {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_hsb_s.png);
	top: 82px;
	left: 282px;
}
.colorpicker_hsb_b {
	background-image: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_hsb_b.png);
	top: 112px;
	left: 282px;
}
.colorpicker_submit {
	position: absolute;
	width: 22px;
	height: 22px;
	background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/colorpicker_submit.png) top;
	left: 322px;
	top: 142px;
	overflow: hidden;
}
.colorpicker_focus {
	background-position: center;
}
.colorpicker_hex.colorpicker_focus {
	background-position: bottom;
}
.colorpicker_submit.colorpicker_focus {
	background-position: bottom;
}
.colorpicker_slider {
	background-position: bottom;
}

.colorSelector {
    position: relative;
    width: 27px;
    height: 27px;
    background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/select.png);
    float:left;
}
.colorSelector div {
    position: absolute;
    top: 4px;
    left: 3px;
    width: 21px;
    height: 19px;
    background: url(<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/colorpicker/select.png) center;
}

#style-switcher input[type=text] {
	background: url('<?php bloginfo('template_directory'); ?>/framework/views/theme_options/images/input_bg.png') repeat-x top left;
	color: #828282;
	font-size: 12px;
	border: 1px solid #d0d0d0;
	width: 60%;
	padding: 5px;
}

#style-switcher .colorpicker_wrapper .input { position: relative; }
#style-switcher .colorpicker_wrapper .input input { padding-top: 10px; padding-bottom: 10px; }
#style-switcher .colorSelector { position: absolute; top: 6px; right: 25px; }

#style-switcher img { width: 20px; height: 20px; border: 1px solid #dcdcdc; margin: 2px; }
#style-switcher .selected img { border: 1px solid #333; }
</style>

<script type='text/javascript'>
jQuery(document).ready(function($) {
	<?php include 'views/theme_options/js/colorpicker.js'; ?>

	jQuery('#link_color .colorSelector').ColorPicker({
		color: '#bc0c0c',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('#link_color .colorSelector div').css('backgroundColor', '#' + hex);
			jQuery('#link_color input').val(hex);
			
			jQuery('.item .item-meta .category a, .post-content a, #sidebar .widget-item .comments a, .post-meta .category a').css('color', '#' + hex);
			jQuery('.item-thumb .comments, .nivo-caption .category, .post-comment-box').css('background-color', '#' + hex);
		}
	});
	
	jQuery('#top_nav .colorSelector').ColorPicker({
		color: '#121212',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('#top_nav .colorSelector div').css('backgroundColor', '#' + hex);
			jQuery('#top_nav input').val(hex);
			
			jQuery('#header-top-wrapper').css('background-color', '#' + hex);
		}
	});
	
	
	jQuery('#main_nav .colorSelector').ColorPicker({
		color: '#121212',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('#main_nav .colorSelector div').css('backgroundColor', '#' + hex);
			jQuery('#main_nav input').val(hex);
			
			jQuery('#navigation-wrapper').css('background-color', '#' + hex);
		}
	});

	jQuery('#style-switcher .hide_switcher').click(function(e) {
		e.preventDefault();
		
		jQuery('#style-switcher-container').hide();
		
		jQuery(this).hide();
		jQuery('#style-switcher .show_switcher').show();
	});
	
	jQuery('#style-switcher .show_switcher').click(function(e) {
		e.preventDefault();
		
		jQuery('#style-switcher-container').show();
		
		jQuery(this).hide();
		jQuery('#style-switcher .hide_switcher').show();
	});
});
</script>
<div id='style-switcher'>
	<div id='style-switcher-container'>
		<div id='link_color' class='colorpicker_wrapper'>
			<h3>Link Color</h3>
			
			<div class='input'>
				<input type='text' value='bc0c0c' name='link_color' id='link_color' />
				<div class="colorSelector"><div style='background-color: #bc0c0c;'></div></div>
			</div>
		</div>
		<div id='top_nav' class='colorpicker_wrapper'>
			<h3>Top Nav Color</h3>
			
			<div class='input'>
				<input type='text' value='121212' name='top_nav' id='top_nav' />
				<div class="colorSelector"><div style='background-color: #121212;'></div></div>
			</div>
		</div>
		<div id='main_nav' class='colorpicker_wrapper'>
			<h3>Main Nav Color</h3>
			
			<div class='input'>
				<input type='text' value='121212' name='main_nav' id='main_nav' />
				<div class="colorSelector"><div style='background-color: #121212;'></div></div>
			</div>
		</div>
	</div>
	
	<a href='#' class='hide_switcher'><img src='<?php bloginfo('template_directory'); ?>/framework/views/style_switcher/hide_switcher.png' alt='Hide'></a>
	<a href='#' class='show_switcher'><img src='<?php bloginfo('template_directory'); ?>/framework/views/style_switcher/show_switcher.png' alt='Show'></a>
	
	<div class='clear'></div>
</div>