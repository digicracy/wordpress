<?php
/*
Plugin Name:    Martha is Hott
Description:    Makes Wordpress frakkin Work with Multi-Domains
Author:         Brandon Holtsclaw
Version:        0.5
Author URI:     http://brandonholtsclaw.com/
*/
$martha_home = preg_replace('!://[a-z0-9.-]*!', '://' . $_SERVER['HTTP_HOST'], get_option('home'));

function martha_home() {
  global $martha_home;
  return $martha_home;
}
 
function martha_replace_host($url, $path = '') {
  return preg_replace('!://[a-z0-9.-]*!', '://' . $_SERVER['HTTP_HOST'], $url);
}
 
add_filter('pre_option_home', 'martha_home');
add_filter('pre_option_siteurl', 'martha_home');
add_filter('pre_option_url', 'martha_home');
add_filter('stylesheet_uri', 'martha_replace_host');
add_filter('admin_url', 'martha_replace_host');
