<?php

/*
Plugin Name: Wemake Tag Manager
Plugin URI: http://wemake.co.il
Version: 1.34.5
Author: Wemake Team
Author URI: http://wemake.co.il
License: GPL2
*/
/*
Copyright 2017  Wemake Team  (email : alex@wemake.co.il)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Constants

define("WMTM_PLUGIN_NAME", 'Wemake Tag Manager');
define("WMTM_PLUGIN_SLUG", 'wemake-tagman');
define("WMTM_PLUGIN_VERSION", '1.34.5');
define("WMTM_ABSPATH", dirname( __FILE__ ));
define("WMTM_URI", plugins_url().'/'.WMTM_PLUGIN_SLUG);
define("WMTM_AJAX_DEBUG", true);

define('WMTM_DOC_ROOT', preg_replace('/\/$/', '', ABSPATH));
define('WMTM_HTTPS_ON', (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? true : false));
define('WMTM_HTTP_HOST', (WMTM_HTTPS_ON ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']);

// PHP version

if(version_compare(phpversion(), '5.6.40', '<')){
    add_action('admin_notices', function(){
        $message = 'Your server is running PHP version '.phpversion().' but '.WMTM_PLUGIN_NAME.' requires at least 5.6.40. The plugin does not work.';
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html( $message ) );
    });
    return false;
}

// Functions

require_once(WMTM_ABSPATH . '/inc/functions.php');

// AJAX actions

if(isset($_GET['action']) && (function_exists('wp_doing_ajax') &&  wp_doing_ajax() || defined('DOING_AJAX'))){
    require_once(WMTM_ABSPATH . '/inc/action.php');
}

// Languages

add_action('init', function(){
    if((is_admin() || is_multisite() && is_network_admin()) && function_exists('get_user_locale')){
        $locale = get_user_locale();
    }elseif(function_exists('get_locale')){
        $locale = get_locale();
    }else{
        $locale = 'en_US';
    }
    load_textdomain(WMTM_PLUGIN_SLUG, WMTM_ABSPATH.'/languages/'.$locale.'.mo');
});

// Run controllers

add_action("wp_loaded", function(){
    if(is_admin() || is_multisite() && is_network_admin()){
        require_once(WMTM_ABSPATH . '/inc/admin.php');
    }else{
        require_once(WMTM_ABSPATH . '/inc/frontend.php');
    }
});

?>
