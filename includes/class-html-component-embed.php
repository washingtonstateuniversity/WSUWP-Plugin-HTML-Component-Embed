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
	public function setup_hooks() {}
}
