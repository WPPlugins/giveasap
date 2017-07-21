<?php
/**
 * Template #1
 * 
 * @package WordPress
 * @subpackage GiveASAP
 * @since GiveASAP 1.0
 */
 


$gallery_images = $display["images"];

if( isset( $display['logo'] ) && $display['logo'] != '' ) {
	$image_url = giveasap_get_image_url( $display['logo'] );
	$logo_link = isset( $display['logo_link'] ) ? $display['logo_link'] : '';

	echo '<div class="giveasap_logo_container">';

		if( $logo_link ) {
			echo '<a target="_blank" href="' . $logo_link . '">';
		}

		echo '<img class="giveasap_logo" src="' . $image_url . '" title="Logo" />';
		
		if( $logo_link ) {
			echo '</a>';
		}

	echo '</div>';
}


?>

<div class="giveasap_box">
	<header class="giveasap_section">
		<h1><?php echo the_title(); ?></h1>
		<?php echo giveasap_the_content(); ?>
	</header>
	<?php 
		if( $ga_schedule->has_ended() ){
			giveasap_show_ended_alert();
		} else {
	?>
		<div id="countdown" data-timezone="<?php echo $timezone; ?>" data-end="<?php echo giveasap_get_end_time(); ?>" class="giveasap_countdown">

		</div>
	<?php } ?>

	<ul class="giveasap_meta">
		<li class="person">
			<span class="fa fa-user-times"></span> 
			<?php echo sprintf( _n( '%s <small>winner</small>', '%s <small>winners</small>', $prize["winners"], 'giveasap' ), number_format_i18n( $prize["winners"] ) ); ?>
		</li>
		<li class="value">
			<span class="fa fa-money"></span>
			<small><?php _e( 'Value:', 'giveasap' ); ?></small>
			<?php echo $prize["value"]; ?>  
		</li>
	</ul>
	<?php the_giveasap_gallery( $gallery_images ); ?>
	
	<?php if( ! $giveasap_ended ) { ?>

		<?php if( $giveasap_front->get_step() == 1 ) { ?>
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
		<?php } ?>

		<?php if( $giveasap_front->get_step() == 2 ) { ?>
			<p class="giveasap-entries"><?php _e( 'Your Current Entries:', 'giveasap' ); ?> <strong><?php $giveasap_front->current_entries(); ?></strong></p> 
			<h3 class="giveasap-entries-title"><?php _e( 'Share & collect entries', 'giveasap' ); ?></h3>
			<?php $giveasap_front->render_sharing_methods(); ?>
		<?php } ?>

	<?php } else { ?>
		<div class="giveasap_alert">
			<?php _e( 'Ended', 'giveasap' ); ?>
		</div>
	<?php } ?>
	 
	<footer class="giveasap_section">
		<ul class="giveasap_meta items-2 row">
			<li class="time icon-aside">
				<span class="fa fa-2x fa-calendar"></span>
				<div class="meta-content">
					<small><?php _e( 'Ends:', 'giveasap' ); ?></small>
					<?php echo $ga_schedule->get_wp_format('end'); ?> <?php echo $schedule["end_time"]; ?>
					<?php if( $timezone_short ) { echo $timezone_short; } ?>
				</div>
			</li>
			<li class="time icon-aside">
				<span class="fa fa-2x fa-calendar"></span>
				<div class="meta-content">
					<small><?php _e( 'Winner Announcement:', 'giveasap' ); ?></small>
					<?php echo $ga_schedule->get_wp_format('winner'); ?> <?php echo $schedule["winner_time"]; ?>
					<?php if( $timezone_short ) { echo $timezone_short; } ?>
				</div>
			</li>
		</ul>
		<div class="giveasap_rules">
			<h2 class="giveasap_heading"><span class="fa fa-exclamation-circle"></span> <?php _e( 'Rules', 'giveasap' ); ?></h2>
			<?php 

				$rules_text = get_extended( $text['rules_text'] ); 
				echo $rules_text['main'];
				if( $rules_text['extended'] != '' ) {
					echo '<button id="giveasap_show_rules" class="giveasap_button secondary">' . __( 'Show Rules', 'giveasap' ) . '</button>';
			?>
					<div class="giveasap_rules_extended">
						<?php echo $rules_text['extended']; ?>
					</div>
			<?php } ?>

		</div>
		<?php
			 
	        if ( ! defined( 'GIVEASAP_PRO' ) ) { 
	        ?>

            <a href="https://wordpress.org/plugins/giveasap/" class="giveasap_button-secondary "><?php _e( 'Powered by Simple Giveaways', 'giveasap' ); ?></a>
			<?php
        }
        ?>
	</footer>
</div>