<?php

class HTML_Component_Embed {
	/**
	 * @var HTML_Component_Embed
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \HTML_Component_Embed
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new HTML_Component_Embed();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.0.1
	 */
	public function setup_hooks() {
		add_shortcode( 'html_component', array( $this, 'display_html_component' ) );
	}

	/**
	 * Displays the HTML powering the component and renders the component HTML.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_html_component( $atts ) {
		if ( ! isset( $atts['url'] ) ) {
			return '<!-- No HTML component URL specified. -->';
		}

		$url = esc_url( $atts['url'] );

		if ( false === apply_filters( 'allowed_html_component_url', false, $url ) ) {
			return '<!-- The URL for this component is not allowed as an embed. -->';
		}

		$cache_key = 'html:' . md5( $url );
		$content = wp_cache_get( $cache_key );

		if ( $content ) {
			return $content;
		}

		$component_response = wp_remote_get( $url );

		if ( is_wp_error( $component_response ) ) {
			return '<!-- Error retrieving component HTML. -->';
		}

		$component_html = wp_remote_retrieve_body( $component_response );
		$component_html_esc = htmlspecialchars( $component_html );

		$content = $component_html;
		$content .= '<pre><code>' . $component_html_esc . '</code></pre>';

		wp_cache_set( $cache_key, $content, '', 3600 );

		return $content;
	}
}
