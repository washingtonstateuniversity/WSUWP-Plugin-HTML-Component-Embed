<?php

namespace WSU\HTML_Component_Embed;

add_action( 'plugins_loaded', 'WSU\HTML_Component_Embed\setup_hooks' );

/**
 * Provide the plugin version for enqueued scripts and styles.
 *
 * @since 0.0.1
 *
 * @return string
 */
function plugin_version() {
	return '0.0.1';
}

/**
 * Setup hooks to include.
 *
 * @since 0.0.1
 */
function setup_hooks() {
	add_shortcode( 'html_component', 'WSU\HTML_Component_Embed\display_html_component' );
}

/**
 * Display the HTML powering the component and render the component HTML.
 *
 * @param array $atts
 *
 * @return string
 */
function display_html_component( $atts ) {
	if ( ! isset( $atts['url'] ) ) {
		return '<!-- No HTML component URL specified. -->';
	}

	$url = esc_url( $atts['url'] );

	if ( false === apply_filters( 'allowed_html_component_url', false, $url ) ) {
		return '<!-- The URL for this component is not allowed as an embed. -->';
	}

	wp_enqueue_style( 'highlightjs', plugins_url( '/css/github-gist.css', dirname( __FILE__ ) ), array(), plugin_version() );
	wp_enqueue_script( 'highlightjs', plugins_url( '/js/highlight.pack.js', dirname( __FILE__ ) ), array(), plugin_version(), true );

	$cache_key = 'html:' . md5( $url );
	$content = wp_cache_get( $cache_key );

	if ( $content ) {
		maybe_highlight_syntax_script();
		return $content;
	}

	$component_response = wp_remote_get( $url );

	if ( is_wp_error( $component_response ) ) {
		return '<!-- Error retrieving component HTML. -->';
	}

	$component_html = wp_remote_retrieve_body( $component_response );
	$component_html_esc = htmlspecialchars( $component_html );

	$content = '<div class="html-component-embed">';
	$content .= $component_html;
	$content .= '<pre><code class="html">' . $component_html_esc . '</code></pre>';
	$content .= '</div>';

	wp_cache_set( $cache_key, $content, '', 3600 );

	maybe_highlight_syntax_script();

	return $content;
}

/**
 * Load the syntax highlighting script if this is the first
 * HTML component embeded on the page.
 *
 * @since 0.0.1
 */
function maybe_highlight_syntax_script() {
	if ( false === has_action( 'wp_footer', 'WSU\HTML_Component_Embed\highlight_syntax_script' ) ) {
		add_action( 'wp_footer', 'WSU\HTML_Component_Embed\highlight_syntax_script' );
	}
}

/**
 * Initiate syntax highlighting once the page has loaded so that all embedded HTML
 * components are processed.
 *
 * @since 0.0.1
 */
function highlight_syntax_script() {
	?>
	<script>jQuery( document ).ready( function( $ ) { $( "pre code" ).each( function( i, block ) { hljs.highlightBlock( block ); } ); } );</script>
	<?php
}
