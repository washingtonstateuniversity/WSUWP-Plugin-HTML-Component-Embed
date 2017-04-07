<?php
/*
Plugin Name: HTML Component Embed
Version: 0.0.1
Description: Embed external HTML components in a style guide or other web document.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-HTML-Component-Embed
Text Domain: [Plugin Text Domain]
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-html-component-embed.php';

add_action( 'after_setup_theme', 'HTML_Component_Embed' );
/**
 * Start things up.
 *
 * @return \HTML_Component_Embed
 */
function HTML_Component_Embed() {
	return HTML_Component_Embed::get_instance();
}
