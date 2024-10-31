<?php
/*
Plugin Name: Sermon Podcast for Church Theme Content
Plugin URI: https://getchurchly.com
Description: Adds iTunes ready sermon podcast to Church Theme Content
Version: 1.0.6
Author: Churchly
Author URI: https://getchurchly.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct file access

add_action( 'admin_init', 'churchly_podcast_has_parent_plugin' );
function churchly_podcast_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'church-theme-content/church-theme-content.php' ) ) {
        add_action( 'admin_notices', 'churchly_podcast_plugin_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function churchly_podcast_plugin_notice(){
    ?><div class="error"><p>Sorry, but iTunes Sermon Podcast for Church Theme Content requires the Church Theme Content plugin to be installed and active.</p></div><?php
}


// Include required files

require_once('admin-menu.php');
require_once('includes.php');
require_once('feed.php');


// Add settings link to plugin menu
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'churchly_add_settings_link' );

function churchly_add_settings_link ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( '/options-general.php?page=churchly-podcast' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}