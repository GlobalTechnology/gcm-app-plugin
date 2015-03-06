<?php namespace GlobalTechnology\GlobalMeasurements {
	auth_redirect(); ?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">
		<!--[if lt IE 9]>
		<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
		<![endif]-->
		<?php wp_head(); ?>
		<script type="application/javascript">
			var gma = window.gma = window.gma || {};
			gma.config = <?php echo Plugin::singleton()->appConfig(); ?>;
		</script>
	</head>

	<body <?php body_class(); ?>>
	<div ng-include="'app/template/app.html'"></div>
	<?php wp_footer(); ?>
	</body>
	</html>
<?php }
