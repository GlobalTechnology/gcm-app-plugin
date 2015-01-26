<?php auth_redirect(); ?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<form id="form1">
	<div ng-app="gcmApp" ng-include="GCM_APP.app_url + '/template/gcm_app.html'">
	</div>

	<script type="text/javascript">
		(function ( $ ) {
			jQuery( '.nav-tabs li' ).on( 'click', function ( event ) {
				jQuery( '.nav-tabs li' ).removeClass( 'active' ); // remove active class from tabs
				jQuery( this ).addClass( 'active' ); // add active class to clicked tab
			} );

			function setActiveTab() {
				var hash = window.location.hash;

				var activeTab = $( '.nav-tabs a[href="' + hash + '"]' );
				if ( activeTab.length ) {
					activeTab.tab( 'show' );
				} else {
					jQuery( '.nav-tabs a:first' ).tab( 'show' );
				}
			}

			//If a bookmark was used to a particular page, make sure to activate the correct tab:
			$( document ).ready( function () {
				setActiveTab();
			} );

			//When history.pushState() activates the popstate event, switch to the currently
			//selected tab aligning with the page being navigated to from history.
			$( window ).on( 'popstate', function () {
				setActiveTab();
			} );

		})( jQuery );
	</script>
</form>
<?php wp_footer(); ?>
</body>
</html>
