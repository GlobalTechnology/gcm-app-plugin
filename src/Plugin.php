<?php namespace GlobalTechnology\GCMApplication {

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
		}

		public function register_scripts_styles() {
			// Google JSAPI
			wp_register_script( self::GOOGLE_JSAPI, self::GOOGLE_JSAPI_URL );

			// Google Maps API
			$wpgcx = \WPGCXPlugin::singleton();
			wp_register_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?sensor=false&key=' . $wpgcx->site_options->google[ 'maps' ][ 'key' ] );

			// Bootstrap
			wp_register_style( 'bootstrap', PLUGIN_URL . '/app/vendor/bootstrap/dist/css/bootstrap.css' );
			wp_register_style( 'bootstrap-theme', PLUGIN_URL . '/app/vendor/bootstrap/dist/css/bootstrap-theme.css', array( 'bootstrap' ) );
			wp_register_script( 'bootstrap', PLUGIN_URL . '/app/vendor/bootstrap/dist/js/bootstrap.js', array( 'jquery' ) );

			//Angular
			wp_register_script( 'angular', PLUGIN_URL . '/app/vendor/angular/angular.js', array( 'jquery' ) );
			wp_register_script( 'angular-route', PLUGIN_URL . '/app/vendor/angular-route/angular-route.js', array( 'angular' ) );
			wp_register_script( 'angular-bootstrap', PLUGIN_URL . '/app/vendor/angular-bootstrap/ui-bootstrap.js', array( 'angular' ) );
			wp_register_script( 'angular-bootstrap-tpls', PLUGIN_URL . '/app/vendor/angular-bootstrap/ui-bootstrap-tpls.js', array( 'angular-bootstrap' ) );
			wp_register_style( 'angular-ng-grid', PLUGIN_URL . '/app/vendor/ng-grid/ng-grid.css' );
			wp_register_script( 'angular-ng-grid', PLUGIN_URL . '/app/vendor/ng-grid/ng-grid-2.0.14.debug.js', array( 'angular' ) );

			// Marker with LAbel
			wp_register_script( 'markerwithlabel', PLUGIN_URL . '/app/vendor/easy-markerwithlabel/src/markerwithlabel.js' );

			//GCM App
			wp_register_style( 'spinner', PLUGIN_URL . '/app/css/spinner.css' );
			wp_register_style( 'gcmapp', PLUGIN_URL . '/app/css/gcm.css', array( 'bootstrap-theme', 'spinner', 'angular-ng-grid' ) );

			wp_register_script( 'gcmapp-controller-admin', PLUGIN_URL . '/app/js/controllers/adminCtrl.js' );
			wp_register_script( 'gcmapp-controller-church', PLUGIN_URL . '/app/js/controllers/churchCtrl.js' );
			wp_register_script( 'gcmapp-controller-gcm', PLUGIN_URL . '/app/js/controllers/gcmCtrl.js' );
			wp_register_script( 'gcmapp-controller-gcm-map', PLUGIN_URL . '/app/js/controllers/gcmMapCtrl.js' );
			wp_register_script( 'gcmapp-controller-map', PLUGIN_URL . '/app/js/controllers/mapCtrl.js' );
			wp_register_script( 'gcmapp-controller-measurements', PLUGIN_URL . '/app/js/controllers/measurementsCtrl.js' );
			wp_register_script( 'gcmapp-controller-stories', PLUGIN_URL . '/app/js/controllers/storiesCtrl.js' );
			wp_register_script( 'gcmapp-controller-training', PLUGIN_URL . '/app/js/controllers/trainingCtrl.js' );

			wp_register_script( 'gcmapp-services-assignments', PLUGIN_URL . '/app/js/services/assignments.js' );
			wp_register_script( 'gcmapp-services-church', PLUGIN_URL . '/app/js/services/church.js' );
			wp_register_script( 'gcmapp-services-measurement', PLUGIN_URL . '/app/js/services/measurement.js' );
			wp_register_script( 'gcmapp-services-ministries', PLUGIN_URL . '/app/js/services/ministries.js' );
			wp_register_script( 'gcmapp-services-token', PLUGIN_URL . '/app/js/services/token.js' );
			wp_register_script( 'gcmapp-services-training', PLUGIN_URL . '/app/js/services/training.js' );

			wp_register_script( 'gcmapp', PLUGIN_URL . '/app/js/gcmApp.js', array(
				self::GOOGLE_JSAPI,
				'google-maps-api',
				'bootstrap',
				'angular-route',
				'angular-bootstrap-tpls',
				'angular-ng-grid',
				'markerwithlabel',
			) );
			wp_localize_script( 'gcmapp', 'GCM_APP', array(
				'ticket'  => \WPGCXPlugin::singleton()->cas_client()->retrievePT( 'https://stage.sbr.global-registry.org/api/measurements/token', $code, $msg ),
				'api_url' => 'https://stage.sbr.global-registry.org/api',
				'app_url' => PLUGIN_URL . '/app',
			) );

			if ( is_page( PAGE_SLUG ) ) {
				wp_enqueue_style( 'gcmapp' );
				wp_enqueue_script( 'gcmapp' );
				foreach ( array(
							  'gcmapp-controller-admin',
							  'gcmapp-controller-church',
							  'gcmapp-controller-gcm',
							  'gcmapp-controller-gcm-map',
							  'gcmapp-controller-map',
							  'gcmapp-controller-measurements',
							  'gcmapp-controller-stories',
							  'gcmapp-controller-training',
							  'gcmapp-services-assignments',
							  'gcmapp-services-church',
							  'gcmapp-services-measurement',
							  'gcmapp-services-ministries',
							  'gcmapp-services-token',
							  'gcmapp-services-training',
						  ) as $script ) {
					wp_enqueue_script( $script );
				}
			}
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
	}
}
