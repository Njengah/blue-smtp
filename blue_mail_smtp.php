<?php

/**
 * Plugin Name:       Blue Mail SMTP
 * Plugin URI:        http://madebybluejay.com
 * Description:       Plugin helps with WP SMTP mail configuration using custom email settings fields. 
 * Version:           1.0.0
 * Author:            Bluejay
 * Author URI:        http://njengah.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blue-mail-smtp
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BLUE_MAIL_SMTP_VERSION', '1.0.0' );

function activate_blue_mail_smtp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blue_mail_smtp-activator.php';
	Blue_mail_smtp_Activator::activate();
}

function deactivate_blue_mail_smtp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blue_mail_smtp-deactivator.php';
	Blue_mail_smtp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blue_mail_smtp' );
register_deactivation_hook( __FILE__, 'deactivate_blue_mail_smtp' );

require plugin_dir_path( __FILE__ ) . 'includes/class-blue_mail_smtp.php';

function run_blue_mail_smtp() {

	$plugin = new Blue_mail_smtp();
	$plugin->run();

}
run_blue_mail_smtp();
