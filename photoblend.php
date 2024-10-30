<?php
/**
 * Plugin Name: WP Blend Photo
 * Plugin URI: 
 * Description: A cool photo editor plugin to edit your image like Blend.
 * Version: 1.0.0
 * Author: Azhar Khan
 * Author URI: mailto:mazharahmedkhan010@gmail.com
 * License: GPL2
 */

//Defining Constants

include_once( dirname( __FILE__ ) . '/includes/blp-settings-page.php' );
include_once( dirname( __FILE__ ) . '/includes/blp-form.php' );

if ( !defined( 'BLENDPHOTO_BASE_URL' ) ) {
	define( 'BLENDPHOTO_BASE_URL' , dirname( __FILE__ ));
}
if ( !defined( 'BLENDPHOTO_HOST_URL' ) ) {
	define( 'BLENDPHOTO_HOST_URL' , plugins_url() . "/blend-photos/" );
}

//Include Javascript and css
function blend_photo_scripts() {
	wp_enqueue_style( 'blendphoto_styles', plugin_dir_url(__FILE__) . 'css/jquery.Jcrop.css' );
	wp_enqueue_style( 'blendphoto_basic_styles', plugin_dir_url(__FILE__) . 'css/blp-style.css' );
	wp_enqueue_script( 'blendphoto_script_setup', plugin_dir_url(__FILE__) . 'js/cropsetup.js', array(), '1.0.0', true );
	wp_enqueue_script( 'blendphoto_script_crop', plugin_dir_url(__FILE__) . 'js/jquery.Jcrop.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'blend_photo_scripts' );

function blp_blendphoto_admin() {
	wp_enqueue_style( 'style-name', plugin_dir_url(__FILE__) . 'css/blp-admin-style.css' );
	wp_enqueue_script( 'script-name', plugin_dir_url(__FILE__) . 'js/blp-settings.js', array(), '1.0.0', true );
    if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
	}else{
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
	}
}
add_action( 'admin_enqueue_scripts', 'blp_blendphoto_admin' );