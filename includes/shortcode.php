<?php

/**
 * Shortcode functionality
 */

if( ! defined( 'ABSPATH' ) ) {
	return;
}

function giveasap_shortcode( $atts ) {
	 $attributes = shortcode_atts( array(
        'id' => '0', 
        'description' => ''
    ), $atts );

	if( $attributes['id'] == '0' ) {
		return '';
	}

	$giveaway = get_post( $attributes['id'] );

	if( $giveaway->post_type = 'giveasap' && $giveaway->post_status !== 'publish' ) {
		return;
	}

	$gasap = gasap();
	$gasap->load_scripts();
	$gasap->giveasap_style();
		 
 	$giveasap_front = new GiveASAP_Front( $giveaway, array() );
 	$schedule = get_post_meta( $giveaway->ID, 'giveasap_schedule', true );
	$ga_schedule = new GA_Schedule( $schedule, $giveaway );
	$giveasap_ended = false;

	if( $ga_schedule->has_ended() ) {
		$giveasap_ended = true;
	} 

	$timezone = get_option('gmt_offset');
 	$giveasap_settings = get_option( 'giveasap_settings' );

 	$output = '';
 	ob_start();

 	if( ! $giveasap_ended ) {

 		if( $giveasap_front->get_step() == 1 ) {

		 	if( $giveasap_settings['google_site_key'] ) { ?>
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<?php
			}

			if ( '' !== $attributes['description'] ) {
				echo '<p>' . apply_filters( 'giveasap_shortcode_description', $attributes['description'] ) . '</p>';
			}
		 	?>
		 	<div data-timezone="<?php echo $timezone; ?>" data-end="<?php echo giveasap_get_end_time( $giveaway->ID ); ?>" class="giveasap_countdown">

			</div>
		 	<form class="giveasap_form" action="<?php echo $giveasap_front->get_form_link(); ?>" method="post">
				<input type="hidden" name="user_guid" value="<?php echo $giveasap_front->get_uniqid(); ?>" />

				<?php if( $giveasap_front->shareID != 0 ) { ?>
					<input type="hidden" name="user_share" value="<?php echo $giveasap_front->shareID; ?>" />
				<?php } ?>

				<?php $giveasap_front->show_errors( 'email' ); ?>
				<input required="required" class="giveasap_input" type="email" name="user_email" value="" placeholder="<?php _e( 'Enter your email', 'giveasap' ); ?>" />
				
				<?php if( $giveasap_settings['google_site_key'] ) { ?>
					<?php $giveasap_front->show_errors( 'google' ); ?>
					<div class="g-recaptcha" data-sitekey="<?php echo $giveasap_settings['google_site_key']; ?>"></div>
				<?php } ?>
				<button type="submit" class="giveasap_button" name="giveasap_submit"><?php _e( 'Enter', 'giveasap' ); ?></button>
				
			</form>
		<?php 

		} else { 
			// Visitor has registered already
			// First, load the scripts if not loaded already
		?>
		<script type="text/javascript">
			
			if( ! giveasap_fontawesome_loaded ) {
				var link = document.createElement( "link" );
				link.href = "//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css";
				link.type = "text/css";
				link.rel = "stylesheet";
				link.media = "all";

				document.getElementsByTagName( "head" )[0].appendChild( link );
				giveasap_fontawesome_loaded = true;
			}
			if( ! giveasap_shortcode_loaded ) {
				var link = document.createElement( "link" );
				link.href = '<?php echo GASAP_URI; ?>/public/assets/css/giveasap-shortcode.css';
				link.type = "text/css";
				link.rel = "stylesheet";
				link.media = "all";

				document.getElementsByTagName( "head" )[0].appendChild( link );
				giveasap_shortcode_loaded = true;
			}
		</script>
		  
		<div class="giveasap-box">
		    <h3><?php _e( 'You are participating in this giveaway', 'giveasap' ); ?></h3>
			<p class="giveasap-entries"><?php _e( 'Your Current Entries:', 'giveasap' ); ?> <strong><?php $giveasap_front->current_entries(); ?></strong></p> 
			<h3 class="giveasap-entries-title"><?php _e( 'Share & collect entries', 'giveasap' ); ?></h3>
			<?php 

			$giveasap_front->render_sharing_methods();

			?>
		</div>
		<?php

		}

		?>

	<?php } else { ?>
		<div class="giveasap_alert">
			<?php _e( 'Ended', 'giveasap' ); ?>
		</div>
	<?php } ?>
	<a href="<?php echo $giveasap_front->get_form_link(); ?>"><?php _e( 'Read more about it here.', 'giveasap' ); ?></a>
<?php
	$output = ob_get_clean();
	return $output;
}
add_shortcode( 'giveaway', 'giveasap_shortcode' );