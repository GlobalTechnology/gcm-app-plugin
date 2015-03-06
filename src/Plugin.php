<?php namespace GlobalTechnology\GlobalMeasurements {

	class Plugin {
		/**
		 * Singleton instance
		 * @var Plugin
		 */
		private static $instance;

		/**
		 * Returns the Plugin singleton
		 * @return Plugin
		 */
		public static function singleton() {
			if ( ! isset( self::$instance ) ) {
				$class          = __CLASS__;
				self::$instance = new $class();
			}
			return self::$instance;
		}

		/**
		 * Prevent cloning of the class
		 * @internal
		 */
		private function __clone() {
		}

		CONST GOOGLE_JSAPI = 'google-jsapi';
		CONST GOOGLE_JSAPI_URL = 'https://www.google.com/jsapi';

		private function __construct() {
			$this->register_actions_filters();
		}

		private function register_actions_filters() {
			add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts_styles' ), 10, 0 );
			add_filter( 'template_include', array( $this, 'template_include' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'deregister_scripts_styles' ), 1000, 1 );
			add_filter( 'clean_url', array( $this, 'fix_requirejs_script' ), 11, 1 );
		}

		public function register_scripts_styles() {
			wp_register_style( 'bootstrap', PLUGIN_URL . '/app/vendor/bootstrap/dist/css/bootstrap.css' );
			wp_register_style( 'bootstrap-theme', PLUGIN_URL . '/app/vendor/bootstrap/dist/css/bootstrap-theme.css', array( 'bootstrap' ) );
			wp_register_style( 'spinner', PLUGIN_URL . '/app/css/spinner.css' );
			wp_register_style( 'gcmapp', PLUGIN_URL . '/app/css/gcm.css', array( 'bootstrap-theme', 'spinner', 'angular-ng-grid' ) );
			wp_register_script( 'requirejs', PLUGIN_URL . '/app/vendor/requirejs/require.js', array(), false, true );

			if ( is_page( PAGE_SLUG ) ) {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_style( 'gcmapp' );
			}
		}

		function fix_requirejs_script( $url ) {
			if ( strpos( $url, 'app/vendor/requirejs/require.js' ) )
				return "$url' data-main='app/js/main.js";
			return $url;
		}

		public function deregister_scripts_styles() {
			global $wp_styles;
			if ( is_page( PAGE_SLUG ) ) {
				$stylesheet = get_stylesheet_uri();
				foreach ( $wp_styles->registered as $handle => $dependency ) {
					/** @var $dependency \_WP_Dependency */
					if ( $dependency->src === $stylesheet ) {
						wp_deregister_style( $handle );
						break;
					}
				}
			}
		}

		public function template_include( $template ) {
			if ( is_page( PAGE_SLUG ) ) {
				return PLUGIN_DIR . '/page-template.php';
			}
			return $template;
		}

		public function appConfig() {
			$casClient = \WPGCXPlugin::singleton()->cas_client();

			// This should be configured with plugin settings
			return json_encode( array(
				'ticket'     => $casClient->retrievePT( 'https://stage-measurements.global-registry.org/v1/token', $code, $msg ),
				'appUrl'     => PLUGIN_URL . '/app',
				'mobileapps' => array(
					array(
						'label' => 'iOS',
						'link' => 'itms-services://?action=download-manifest&url=https://downloads.global-registry.org/stage/ios/gma.plist',
					),
					array(
						'label' => 'Android',
						'link' => 'https://play.google.com/store/apps/details?id=com.expidevapps.android.measurements.demo',
					)
				),
				'api'        => array(
					'measurements' => 'https://stage-measurements.global-registry.org/v1',
					'refresh'      => admin_url( 'admin-ajax.php?action=gmaapp-refresh' ),
					'logout'       => $casClient->getServerLogoutURL()
				),
				'namespace'  => 'gma-app',
				'googlemaps' => 'https://maps.googleapis.com/maps/api/js?sensor=false',
			) );
		}
	}
}
