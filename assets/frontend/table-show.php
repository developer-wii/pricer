<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('init', 'ept_register_shortcodes');
function ept_register_shortcodes() {
      add_shortcode('ept_table', 'ept_table_shortcode');
}
function ept_table_shortcode( $args, $content="") {
        global $wpdb;
        $table_name=$wpdb->prefix.'ept_data';
        $result = $wpdb->get_results ( "SELECT * FROM $table_name" );
        foreach ( $result as $print )   {
         $decode = $print->data;
         echo base64_decode($decode);
         }
}
?>
