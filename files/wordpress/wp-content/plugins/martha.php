<?php
/*
Plugin Name:    Martha
Description:    Makes Wordpress frakkin Work with Multi-Domains
Author:         Brandon Holtsclaw
Version:        0.5
Author URI:     http://brandonholtsclaw.com/
*/

$badwolf = str_split($_SERVER['HTTP_HOST'],":");

$martha_home = preg_replace('!://[a-z0-9.-]*!', '://' . $badwolf[0], get_option('home'));

function martha_home() {
  global $martha_home;
  return $martha_home;
}
 
function martha_replace_host($url, $path = '') {
  global $badwolf;
  return preg_replace('!://[a-z0-9.-]*!', '://' . $badwolf[0], $url);
}

function martha_clear() {
  $GLOBALS["super_cache_enabled"] = 1;
  wp_cache_clear_cache();
}

if (!wp_next_scheduled('martha_clear_hook')) {
  wp_schedule_event(time(),'hourly','marth_clear_hook');
}

add_action('martha_clear_hook', 'martha_clear');
add_filter('pre_option_home', 'martha_home');
add_filter('pre_option_siteurl', 'martha_home');
add_filter('pre_option_url', 'martha_home');
add_filter('stylesheet_uri', 'martha_replace_host');
add_filter('admin_url', 'martha_replace_host');
