<?php

/**
 * GiveASAP Updates
 */

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Updates for Version 2.0.0
 * On this version we will move every giveaway entry into the new table
 * Getting entries from meta giveasap_registered_users and giveasap_registered_entries
 * @return void 
 * @since  2.0.0
 */
function giveasap_update_200() {
	global $wpdb;

	// Make sure we are creating the database
	$installer = new GA_Installer();
	$installer->create_db();

	// Get all giveaways
	$giveaways = get_posts( array(
		'posts_per_page' => -1,
		'post_status' => 'any',
		'post_type'   => 'giveasap'
	));

	foreach ( $giveaways as $giveaway ) {
		
		$users = get_post_meta( $giveaway->ID, 'giveasap_registered_users', true );
		$entries = get_post_meta( $giveaway->ID, 'giveasap_registered_entries', true );

		if( is_array( $users ) ) { 
			foreach ( $users as $ref_id => $email ) {
				$user_entry = isset( $entries[ $ref_id ] ) ? $entries[ $ref_id ] : 0;
				giveasap_register_user( $giveaway->ID, $email, $user_entry, $ref_id );
			}
		}
	}
}

/**
 * Updates for Version 2.2.6
 * @return void 
 * @since  2.2.6 
 */
function giveasap_update_206() {

	$giveaways = get_posts( array(
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'post_type'   => 'giveasap'
	));

	foreach ( $giveaways as $giveaway ) {
		
		$post_id = $giveaway->ID;

		$schedule = get_post_meta( $post_id, 'giveasap_schedule', true );
	    $class_schedule = new GA_Schedule( $schedule );
	    
	    // Get the current Server Time
	    $current_time = time();
	    
	    // Get the Current WordPress time
	    $wp_current_time = current_time( 'timestamp' );
	    
	    $timezone = get_option('timezone_string'); 
	 
	    $difference = 0;

	    if( ! $timezone ) {
	        // Get the difference between. Difference will be minus if that time did not happen yet (such as GMT+0 and GMT+6)
	       $difference = $wp_current_time - $current_time;
	    }
	    
	    // Multiply by -1 to get + so NY != -6 --> == +6
	    $the_end_time = (-1) * $difference + $class_schedule->get_end_timestamp();
	    giveasap_update_meta( $post_id, 'end_time', $the_end_time );
      
	}
}