<?php namespace GlobalTechnology\GlobalMeasurements {

	/**
	 * Localization Text Domain
	 * @var string
	 */
	const TEXT_DOMAIN = 'gmaapp';

	/**
	 * Script / Style Prefix
	 * @var string
	 */
	const PREFIX = 'gmaapp-';

	/**
	 * Page Slug
	 * @var string
	 */
	const PAGE_SLUG = 'gma';

	/**
	 * Plugin Directory
	 * @var string
	 */
	define( 'GlobalTechnology\GlobalMeasurements\PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), \DIRECTORY_SEPARATOR ) );

	/**
	 * Plugin Directory URL
	 * @var string
	 */
	define( 'GlobalTechnology\GlobalMeasurements\PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
}
