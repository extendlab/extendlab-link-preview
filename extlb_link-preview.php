<?php
/**
 * @package  ExtlLinkPreview
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
Plugin Name: Extendlab – Link Preview
Plugin URI: https://extendlab.de/wp-plugins/link-preview.html
Description: Plugin to show a short preview of (internal) linked pages or posts.
Version: 1.0.0
Author: Extendlab
Author URI: https://extendlab.de
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: extlb_link-preview
Domain Path: /languages
*/

// ADD THE PLUGIN SCRIPS AND STYLES
function extlbScriptsStyles(){
	wp_register_script( 'extlb_link-preview', plugins_url( '/assets/js/extlb_link-preview.js', __FILE__ ), array( 'jquery' ), '1.1', true );
	wp_enqueue_script( 'extlb_link-preview' );

	wp_localize_script( 'extlb_link-preview', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'special_value' => 'insert your stuff' ) );

	wp_enqueue_style( 'extlb_link-preview', plugins_url( '/assets/css/extlb_link-preview.css', __FILE__ ), '', '1.1' );
}
add_action( 'wp_enqueue_scripts', 'extlbScriptsStyles' );

// Adding links to plugins-overview-page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'extlbPluginSiteLinks' );
function extlbPluginSiteLinks( $links ) {
	$mylinks = array(
		'<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=extlb-options-page') ) .'">' . esc_html__('Settings', 'extlb_link-preview') . '</a>',
		'<a href="https://extendlab.de" target="_blank">' . esc_html__('More by Extendlab', 'extlb_link-preview') . '</a>'
	);

	return array_merge( $links, $mylinks );
}

// Load translation
add_action('plugins_loaded', 'extlbTranslations');
function extlbTranslations() {
	load_plugin_textdomain( 'extlb_link-preview', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}

// Create custom image size
function extlbImageSizes(){
	add_image_size( 'extlb_post_thumbnail', 350, 160, array( 'center', 'center' ) );
}
add_action( 'init', 'extlbImageSizes' );

// HERE THE AJAX-FUNCTION
function extlbShowLinkPreview (){
	$link = esc_url_raw($_POST['link']);
	$status = 'success';
	$status_message = esc_html__('Transmission successful!', 'extlb_link-preview');
	$options = array(
		'darkmode' => (get_option('extlb_darkmode') == 'on') ? true : false,
		'disable_mobile' => (get_option('extlb_disable_mobile') == 'on') ? true : false,
		'hide_thumbnails' => (get_option('extlb_hide_thumbnails') == 'on') ? true : false,
		'link_selector' => esc_attr( get_option('extlb_link_selector') )
	);

	$parsed_link = parse_url( $link );

	$this_host = get_site_url();
	$this_host = str_replace('http://', '', $this_host);
	$this_host = str_replace('https://', '', $this_host);

	$link_host = $parsed_link['host'];
	$link_path = $parsed_link['path'];

	if ($this_host == $link_host) {
		$link_type = 'intern';

		$this_post = extlbGetPostByLink( $link );

	} else {
		$link_type = 'extern';
	}

	$post_title = $this_post->post_title;
	$post_content = extlbGenerateExcerpt( $this_post->post_content );
	$post_thumbnail = null;
	if (has_post_thumbnail($this_post)) {
		$post_thumbnail = get_the_post_thumbnail_url($this_post, 'extlb_post_thumbnail');
	}

	if ($post_title == NULL || $post_content == NULL) {
		$status = 'error';
		$status_message = esc_html__('Post-Title or Post-Content is NULL.', 'extlb_link-preview');
	}

	$return = [
		'status' => $status,
		'status_message' => $status_message,
		'link_type' => $link_type,
		'title' => $post_title,
		'excerpt' => $post_content,
		'thumbnail' => $post_thumbnail,
		'read_more_text' => esc_html__('Read more...', 'extlb_link-preview'),
		'options' => $options
	];

	header('Content-Type: application/json');
	echo json_encode($return);

	wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_show_link_preview', 'extlbShowLinkPreview' );
add_action( 'wp_ajax_nopriv_show_link_preview', 'extlbShowLinkPreview' );

function extlbGetPostByLink( $link ) {
	$post_ID = url_to_postid( $link );
	return get_post( $post_ID );
}

function extlbGenerateExcerpt( $text, $chars = 100 ) {
	$text = wp_strip_all_tags( $text );

	if(strlen($text) > $chars) {
		$excerpt = substr($text, 0, $chars).'...';

		return $excerpt;
	}else {
		return $text;
	}
}

// Includes
require_once plugin_dir_path( __FILE__ ) . 'includes/options-page.php';