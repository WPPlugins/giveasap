<?php

if( ! defined( 'ABSPATH' ) ) {
    return;
}

add_action( 'save_post', 'giveasap_save_post_schedule', 99, 2);

function giveasap_save_post_schedule( $post_id, $post ){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }

	if( get_post_type( $post ) !== 'giveasap' ) {
		return $post_id;
	}

	if( ! isset( $_POST['post_type' ] ) ) {
		return $post_id;
	}
    
    // Check the user's permissions.
    if ( 'page' == $_POST['post_type'] ) {
      if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
      }
    } else {
      if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
      }
    }

    $prize_options = get_post_meta( $post_id, 'giveasap_prize', true );
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
    
    $timestamp = wp_next_scheduled( 'giveasap_has_ended', array( $post_id ) );
    
    if( $timestamp ) {
        wp_unschedule_event( $timestamp, 'giveasap_has_ended', array( $post_id ) );
    }
    
    giveasap_update_meta( $post_id, 'end_time', $the_end_time );
     
    // If the end time is in the past, dont schedule it
    if( $current_time > $the_end_time ) {
        if( 'publish' == $post->post_status ) {
            giveasap_set_status( $post_id, 'giveasap', 'giveasap_ended' );
        }
        return $post_id;

    }

    // Set it back to publish
    giveasap_set_status( $post_id, 'giveasap', 'publish', 'giveasap_ended' );
    
    wp_schedule_single_event( $the_end_time, 'giveasap_has_ended', array( $post_id ) );
    
}