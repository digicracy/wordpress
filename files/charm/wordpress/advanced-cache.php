<?php
global $wp_ffpc_config ;
$wp_ffpc_config['port']='11211';
$wp_ffpc_config['host']='127.0.0.1';
$wp_ffpc_config['expire']=300;
$wp_ffpc_config['invalidation_method']='1';
$wp_ffpc_config['prefix_meta']='meta-';
$wp_ffpc_config['prefix_data']='data-';
$wp_ffpc_config['charset']='utf-8';
$wp_ffpc_config['pingback_status']='1';
$wp_ffpc_config['debug']='1';
$wp_ffpc_config['syslog']=0;
$wp_ffpc_config['cache_type']='memcache';
$wp_ffpc_config['cache_loggedin']=0;
$wp_ffpc_config['nocache_home']=0;
$wp_ffpc_config['nocache_feed']=0;
$wp_ffpc_config['nocache_archive']=0;
$wp_ffpc_config['nocache_single']=0;
$wp_ffpc_config['nocache_page']=0;
$wp_ffpc_config['apc_compress']=1;


include_once ('/var/www/wp-content/plugins/wp-ffpc/wp-ffpc-common.php');
include_once ('/var/www/wp-content/plugins/wp-ffpc/advanced-cache.php');
