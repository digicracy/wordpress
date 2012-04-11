<?php
/*
Plugin Name: Gaug.es
Plugin URI: http://wordpress.org/extend/plugins/gauges/
Description: Enables <a href="http://get.gaug.es/">Gaug.es</a> on all pages. Heavily inspired by <a href="http://www.ksylvest.com/">Kevin Sylvestre</a>.
Version: 1.1
Author: Steve Smith, adapded from Kevin Sylvestre
Author URI: http://get.gaug.es/
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

function activate_gauges() {
  add_option('gauges_site_id', '');
}

function deactive_gauges() {
  delete_option('gauges_site_id');
}

function admin_init_gauges() {
  register_setting('gauges', 'gauges_site_id');
}

function admin_menu_gauges() {
  add_options_page('Gaug.es', 'Gaug.es', 8, 'gauges', 'options_page_gauges');
}

function options_page_gauges() {
  include(WP_PLUGIN_DIR.'/gauges/options.php');
}

function gauges() {

  $gauges_site_id = get_option('gauges_site_id');

  if (trim($gauges_site_id) != '') {
  ?>
  <script type="text/javascript">
    (function() {
      var t   = document.createElement('script');
      t.type  = 'text/javascript';
      t.async = true;
      t.id    = 'gauges-tracker';
      t.setAttribute('data-site-id', '<?php echo esc_js($gauges_site_id) ?>');
      t.src = '//secure.gaug.es/track.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(t, s);
    })();
  </script>
  <?php
  }

}

register_activation_hook(__FILE__, 'activate_gauges');
register_deactivation_hook(__FILE__, 'deactive_gauges');

if (is_admin()) {
  add_action('admin_init', 'admin_init_gauges');
  add_action('admin_menu', 'admin_menu_gauges');
}

if (!is_admin()) {
  add_action('wp_footer', 'gauges', 99);
}

?>