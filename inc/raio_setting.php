<?php
 function raio_dashboard(){
?>
 <div class="wrap raio-about-wrap about-wrap">
	<?php
		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'raio_main_page_start' ); ?>
		<h1><?php esc_html_e('Rockon All in One', 'rockon-all-in-one'); ?></h1>
		
	<?php
		/**
		 * Fires after the main page `<h1>` heading tag.
		 *
		 * @since 1.3.0
		 */
		do_action( 'raio_main_page_after_header' );
		?>

		<div class="about-text raio-about-text">
			<?php esc_html_e('Thank you for choosing Rockon All in one!', 'rockon-all-in-one'); ?>
		</div>
		<div class="raio-badge"></div>
	<?php
		/**
		 * Fires before the About Page changelog.
		 *
		 * @since 1.4.0
		 */
		do_action( 'raio_main_page_before_changelog' ); ?>

		<h2><?php printf( esc_html__( "What's new in version %s", 'rockon-all-in-one' ), RAIO_VERSION ); ?></h2>
		<div class="changelog about-integrations">
			<div class="raio-feature feature-section col three-col">
				<div>
					<h2><?php esc_html_e('Breadcrumbs', 'rockon-all-in-one'); ?></h2>
					<p><?php esc_html_e( 'Breadcrumbs option with shortcode', 'rockon-all-in-one' ); ?></p>
				</div>
				<div>
					<h2><?php esc_html_e( 'Content limit', 'rockon-all-in-one' ); ?></h2>
					<p><?php esc_html_e( 'Extract content limit according need available with short-code', 'rockon-all-in-one' ); ?></p>
				</div>
				<div>
					<h2><?php esc_html_e( 'Disable/Enable Comments', 'rockon-all-in-one' ); ?></h2>
					<p><?php esc_html_e( 'You are easily Disable or Enable Comments on everywhere with on check-box', 'rockon-all-in-one' ); ?></p>
				</div>
				<div>
					<h2><?php esc_html_e( 'Added Option add script header or footer', 'rockon-all-in-one' ); ?></h2>
					<p><?php esc_html_e( 'You are easily add script on header & footer using RockOn Plugin', 'rockon-all-in-one'); ?></p>
				</div>
				<div class="last-feature">
					<h2><?php esc_html_e( 'Move Script to the footer & improve the speed', 'rockon-all-in-one' ); ?></h2>
					<p><?php esc_html_e( 'Move your scripts to the footer to help speed up perceived page load times and improve user experience.', 'rockon-all-in-one'); ?></p>
				</div>
			</div>
		</div>
		<!--<div class="extranotes">
			<h1>More from RockOn VS Studios</h1>
			<div class="rovspromos-about">
			<p>
			<a href="https://wordpress.org/plugins/rockon-owl-slider/">
			<img src="#" alt="">
			</a>
			</p>
			<p>
			<a href="#">
			<img src="#" alt="">
			</a>
			</p>
			<p>
			<a href="#">
			<img src="#" alt="">
			</a>
			</p>
			</div>	
		</div>-->
 </div>
<?php } ?>