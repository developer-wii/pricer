<?php
/*
Plugin Name: Enlight Pricer
Plugin URI: https://www.wpenlight.com
Description: A Simple plugin to create Pricing table.
Version: 1.0
Author: wpenlight
License: GPL2
License URI:https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: Enlight Pricer Table
*/
// Prevent direct call
if ( ! defined( 'ABSPATH' ) ) exit;

/* !8. ADMIN PAGES */
include_once( plugin_dir_path( __FILE__ ) . 'assets/admin/admin-pages.php' );

/* Post type */
  include ( plugin_dir_path( __FILE__ ). 'includes/post-type.php');

/* !4. EXTERNAL SCRIPTS */
include_once( plugin_dir_path( __FILE__ ) . 'assets/admin/internal-scripts.php' );
include_once( plugin_dir_path( __FILE__ ) . 'assets/frontend/external-scripts.php' );

/* !2. SHORTCODES */
include_once( plugin_dir_path( __FILE__ ) . 'assets/frontend/table-show.php' );
//include_once( plugin_dir_path( __FILE__ ) . 'assets/admin/table-data.php' );

//Create database table on activaton
register_activation_hook( __FILE__, 'ept_plugin_create_table' );
function ept_plugin_create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'ept_data';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		`name` text NOT NULL,
		`shortcode` text NOT NULL,
		`user_id` int(8) NOT NULL,
		`data` text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

//delete table on plugin deactivation
register_deactivation_hook( __FILE__, 'ept_plugin_remove_table' );
function ept_plugin_remove_table() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'ept_data';
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("ept_pricer_db_version");
}
add_action ('wp_ajax_save_data','ept_save_data');
add_action ('wp_ajax_norpiv_save_data','ept_save_data');
function ept_save_data()
{
  global $wpdb;
$table_name= $wpdb->prefix.'ept_data';
$data=esc_html($_POST['f0']);
$Sanitize_Html = sanitize_html_class($data);
$id=get_current_user_id();
$Sanitize_User = sanitize_user($id);
$title = esc_html($_POST['f2']);
$shortcode = esc_html($_POST['f3']);
/*$numRows = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
    if($numRows == 0){*/
              $wpdb->insert(
              $table_name,
              array('data' => $data, 'user_id' => $Sanitize_User,'name' =>$title,'shortcode' => $shortcode),
              array('%s', '%s')
        );
/*} else {
				echo $data;
        $wpdb->update(
                $table_name, //table
                array('data' => $data),
                array('user_id' => $Sanitize_User),
                array('%s'),
                array('%s')
        );
      }*/
}
?>