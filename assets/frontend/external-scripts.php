<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('wp_enqueue_scripts', 'ept_public_front_scripts');

function ept_public_front_scripts() {

	wp_register_style('webindiainc-css', plugins_url('/css/new-age.css',__FILE__));
  wp_register_style('webindiainc-css-1', plugins_url('/css/main.css',__FILE__));
  wp_register_style('webindiainc-css-2', plugins_url('/css/webindia-style.css',__FILE__));
  wp_register_style('webindiainc-css-3', plugins_url('/css/webindia-ribbon.css',__FILE__));
  wp_register_style('webindiainc-css-4', plugins_url('/css/bootstrap.min.css',__FILE__));
 // wp_register_style('webindiainc-css-5', plugins_url('/css/enlight-bootstrap-toggle.min.css',__FILE__));
//	wp_register_style('webindiainc-css-6', plugins_url('/css/enlight-font-awesome.min.css',__FILE__));
  //wp_register_style('webindiainc-css-7', plugins_url('/css/enlight-simple-line-icons.css',__FILE__));

  wp_enqueue_style('webindiainc-css');
  wp_enqueue_style('webindiainc-css-1');
  wp_enqueue_style('webindiainc-css-2');
  wp_enqueue_style('webindiainc-css-3');
  wp_enqueue_style('webindiainc-css-4');
 // wp_enqueue_style('webindiainc-css-5');
 // wp_enqueue_style('webindiainc-css-6');
  //wp_enqueue_style('webindiainc-css-7');

}
