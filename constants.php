<?php namespace GlobalTechnology\GCMApplication {

	/**
	 * Localization Text Domain
	 * @var string
	 */
	const TEXT_DOMAIN = 'gcmapp';

	/**
	 * Script / Style Prefix
	 * @var string
	 */
	const PREFIX = 'gcmapp-';

	/**
	 * Page Slug
	 * @var string
	 */
	const PAGE_SLUG = 'gcm-app';

	/**
	 * Plugin Directory
	 * @var string
	 */
	define( 'GlobalTechnology\GCMApplication\PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), \DIRECTORY_SEPARATOR ) );

	/**
	 * Plugin Directory URL
	 * @var string
	 */
	define( 'GlobalTechnology\GCMApplication\PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
}
