<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://madebybluejay.com
 * @since      1.0.0
 *
 * @package    Blue_mail_smtp
 * @subpackage Blue_mail_smtp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Blue_mail_smtp
 * @subpackage Blue_mail_smtp/includes
 * @author     Bluejay <plugins@madebybluejay.com>
 */
class Blue_mail_smtp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'blue_mail_smtp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
