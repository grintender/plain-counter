<?php
/*
 * Plugin Name: Plain Counter
 * Version: 1.0
 * Plugin URI: http://the.gt/plain-counter-demo
 * Description: All things count. Projects done and coffee consumed.
 * Author: grintender
 * Author URI: http://grintender.github.io
 * Requires at least: 4.0
 * Tested up to: 4.5.2
 *
 * Text Domain: plain-counter
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author grintender
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-plain-counter.php' );
require_once( 'includes/class-plain-counter-settings.php' );
require_once( 'includes/class-plain-counter-custom-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-plain-counter-admin-api.php' );

// Load plugin php assets
require_once( 'assets/php/display.php' );

/**
 * Returns the main instance of Plain_Counter to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Plain_Counter
 */
function Plain_Counter () {
	$instance = Plain_Counter::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Plain_Counter_Settings::instance( $instance );
	}

	return $instance;
}

Plain_Counter();


