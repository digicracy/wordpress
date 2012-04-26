<?php

function rmrf($dir, $delete_parent = false)
{
        $search = (is_dir($dir)) ? glob($dir . '/*') : glob($dir);
        foreach( $search as $file )
        {
                if( is_dir($file) )
                {
                        rmrf($file, true);
                }
                else
                {
                        unlink($file);
                }
        }

        if( $delete_parent )
        {
                rmdir($dir);
        }
}

require_once(__DIR__ . '/../wp-config.php');

if( $l = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) )
{
        mysql_select_db(DB_NAME);
        $r = mysql_query('SELECT COUNT(ID) FROM wp_posts WHERE post_type = "post" AND post_modified > "' . date('Y-m-d H:i:s', strtotime('-1 min 30 seconds')) . '"', $l);
        $total = array_shift(mysql_fetch_row($r));

        if( (INT)$total > 0 )
        {
                echo 'Purge! In a minute';
                sleep(rand(5, 40));

                rmrf('/mnt/ramdisk/proxy-cache');
                rmrf('/var/www/omgubuntu.co.uk/wp-content/cache/supercache');
                rmrf('/var/www/omgubuntu.co.uk/wp-content/cache/wp-cache-*');
                system('service php5-fpm restart');
                system('service nginx restart');
        }
}

