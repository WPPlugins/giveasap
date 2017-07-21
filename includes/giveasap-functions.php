<?php

/**
 * Functions for various GiveASAP actions
 */

if( ! defined( 'ABSPATH' ) ) {
	return;
}

add_action( 'giveasap_has_ended', 'giveasap_has_ended', 10 );
/**
 * Function to be used in a scheduled action 'giveasap_had_ended'
 * @param  int $post_id ID of the giveaway
 * @return void         
 */
function giveasap_has_ended( $post_id ) {
	giveasap_set_status( $post_id, 'giveasap', 'giveasap_ended' );
}

/**
 * Notify Winners by getting all the winners and sending them the email set in the giveaway
 * @param  array	$winners Array of Winners
 * @param  int 		$post_id ID of the giveaway
 * @return void     
 */
function giveasap_notify_winners( $winners, $post_id ) {
	// Get texts settings
	$texts = get_post_meta( $post_id, 'giveasap_text', true );
	// Prize settings
	$prize_options = get_post_meta( $post_id, 'giveasap_prize', true );

	// Email
	$email_text = $texts['winner_email'];
	// Replace the title placeholder
	$email_text = str_replace('{{TITLE}}', $prize_options['prize'], $email_text);
	// Subject Title
	$subject_title = sprintf( __( 'Congratulations, you have won %s', 'giveasap' ), $prize_options['prize'] );

	add_filter( 'wp_mail_content_type', 'giveasap_set_mail_content_type', 20 );

	// For every winner, email him
	foreach ( $winners as $email => $emailed ) {
		if( $emailed == 'no' ) {
			$mail_bool = wp_mail( $email, $subject_title, $email_text );
			if( $mail_bool ) {
				$winners[ $email ] = 'yes';
			}
		}	
	}

	remove_filter( 'wp_mail_content_type', 'giveasap_set_mail_content_type', 20 );

	giveasap_update_winners( $winners, $post_id ); 

}

/**
 * Used for filtering the wp_mail by setting the content in a HTML type
 * @param  string $content_type 
 * @return string               
 */
function giveasap_set_mail_content_type( $content_type ) {
	return 'text/html';
}

/**
 * Select the winners
 * @param  number $post_id 
 * @return mixed  Return array of winner's emails or if not any, returns false
 */
function giveasap_select_winner( $post_id ) {
	// Prize settings
	$prize_options = get_post_meta( $post_id, 'giveasap_prize', true );
	// Number of winners
	$number_of_winners = (int) $prize_options['winners'];
	// Get the entries
	$registered_entries = giveasap_get_entries_for( $post_id );

	if( empty( $registered_entries ) ) {
		return false;
	}
	// Shuffle the array
	shuffle( $registered_entries );

	// Sort it from high to low
	usort( $registered_entries, "giveasap_sort_entries" );

	// Splice the array to get the first entries from 1 to number of winners
	$winner_entries = array_splice( $registered_entries, 0, $number_of_winners);

	$selected_winners = array();

	// If there are any winner entries, get the email from each
	if( !empty( $winner_entries ) ) {
		foreach ( $winner_entries as $user ) {
			$email = $user->email;
			$selected_winners[ $email ] = 'no';
			giveasap_update_meta( $user->id, 'winner', '1' );
		}
		
		giveasap_update_winners( $selected_winners, $post_id );

		/**
		 * Winners were selected
		 *
		 * @since  2.2.0 
		 *
		 * @param  int   $post_id The ID of the Giveaway
		 * @param  array $selected_winners Array of Winner Emails
		 */
		do_action( 'giveasap_selected_winners', $post_id, $selected_winners ); 

		return $selected_winners;
	}
	return false;
}

/**
 * Sorting array values
 * @return number 0 - remains in position, 1 -> moves up, -1 -> moves down
 */
function giveasap_sort_entries( $item_a, $item_b ) {
	
	if( $item_a->entries == $item_b->entries ) {
		return 0;
	}
 
	return ( $item_a->entries > $item_b->entries ) ? -1 : 1;

}

/**
 * Updating the data for winners
 * @param  array $winners 
 * @param  int $post_id 
 * @return void          
 */
function giveasap_update_winners( $winners, $post_id ) {
	update_post_meta( $post_id, 'giveasap_winners', $winners );
}

/**
 * Returning the winners
 * @param  int $post_id 
 * @return array          
 */
function giveasap_get_winners( $post_id ) {
	return get_post_meta( $post_id, 'giveasap_winners', true );
}

/**
 * Returning all the registered users
 * @param  int $post_id 
 * @return array          
 */
function giveasap_get_users( $post_id ) {
	return get_post_meta( $post_id, 'giveasap_registered_users', true );
}

/**
 * Setting the status to a giveaway
 * @param  int 		$post_id     
 * @param  string 	$post_type  	Post Type which status we want to update 
 * @param  string 	$status     	Status to update
 * @param  string 	$from_status 	From which status
 * @return bool     				True if updated, false if not         
 */
function giveasap_set_status( $post_id, $post_type, $status, $from_status = 'publish'){
	if( get_post_type( $post_id ) == $post_type ){
		 global $wpdb;
 
		  $update_status = $wpdb->update( 
			$wpdb->posts, 
			array( 
				'post_status' =>  $status,	// string 
			), 
			array( 'ID' => intval( $post_id ), 'post_type' => $post_type, 'post_status' => $from_status ), 
			array( 
				'%s',	// value1 
			), 
			array( '%d', '%s', '%s' ) 
		);
		 
		if( $update_status ){

			wp_transition_post_status( $status, $from_status, get_post($post_id) );
			return true;

		}
		return false;
	}
}

/**
 * Shuffle assocciative array
 * @param  array &$array 
 * @return boolean         
 */
function giveasap_shuffle_assoc( &$array ) {
    $keys = array_keys($array);

    shuffle($keys);

    foreach($keys as $key) {
        $new[$key] = $array[$key];
    }

    $array = $new;

    return true;
}

/**
 * Register User in a giveaway
 * @param integer $giveaway_id The Giveaway ID (post ID)
 * @param string $email Email entry
 * @param integer $entry Entries
 * @param string $ref_id Reference ID (used for < 2.0.0 )
 * @return mixed         Returns ID if successful, otherwise false 
 */
function giveasap_register_user( $giveaway_id, $email, $entry = 1, $ref_id = '' ) {
	global $wpdb;
	$insert = $wpdb->insert( 
		$wpdb->giveasap_entries, 
		array( 
			'post_id' => $giveaway_id, 
			'email' => $email,
			'entries' => $entry,
			'ref_id' => $ref_id,
			'date'   => current_time( 'mysql' )
		), 
		array( 
			'%d', 
			'%s',
			'%d',
			'%s',
			'%s'
		) 
	);

	if( $insert ) {
		return $wpdb->insert_id;
	} 

	return $insert;
}

/**
 * Updating the User entry value
 * @param  integer $user_id The ID of the registered user
 * @param  integer $entry   Value
 * @return boolean          
 */
function giveasap_update_user_entry( $user_id, $entry ) {
	global $wpdb;
	$update = $wpdb->update( 
		$wpdb->giveasap_entries, 
		array( 
			'entries' => $entry
		), 
		array( 'id' => $user_id ), 
		array( 
			'%d',	// value1
		), 
		array( '%d' ) 
	);

	if( false !== $update ) {
		return true;
	}

	return false;
}

/**
 * Get the number of subscribers
 * @param  integer $giveaway_id 
 * @return array              
 */
function giveasap_get_subscribed_count( $giveaway_id ) {
	global $wpdb;

	$results = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $wpdb->giveasap_entries . ' WHERE post_id = %d', $giveaway_id ) );

	$count = null !== $results ? $results : 0;
	return $count;
}

/**
 * Get all the entries from a Giveaway
 * @param  integer $giveaway_id 
 * @return array              
 */
function giveasap_get_entries_for( $giveaway_id ) {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->giveasap_entries . ' WHERE post_id = %d', $giveaway_id ) );

	return $results;
}

/**
 * Find and return the user 
 * @param  string $id 
 * @param  number $post_id Giveaway ID
 * @return array     
 */
function giveasap_get_user( $id, $post_id ) {
	global $wpdb;

	$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->giveasap_entries . ' WHERE post_id = %d AND ( ref_id = %s )', $post_id, $id ));

	return $user;
}

/**
 * Find and return the user 
 * @param  string $id 
 * @param  number $post_id Giveaway ID
 * @return array     
 */
function giveasap_get_user_by_id( $id, $post_id ) {
	global $wpdb;

	$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->giveasap_entries . ' WHERE post_id = %d AND id = %d', $post_id, $id ));

	return $user;
}

/**
 * Find the user by email
 * @param  string $email 
 * @param  number $post_id Giveaway ID
 * @return bool        
 */
function giveasap_user_exists( $email, $post_id ) {
	global $wpdb;

	$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->giveasap_entries . ' WHERE post_id = %d AND email = %s', $post_id, $email ));

	if( null == $user ) {
		return false;
	}
	return true;
}

/**
 * Get WordPress Date format into JavaScript
 * @return string
 */
function giveasap_get_JS_date_format( $format = '' ) {
	$date_format = ( '' == $format ) ? get_option( 'date_format' ) : $format;
	$date_format = str_replace( 'd', 'dd', $date_format );
	$date_format = str_replace( 'j', 'd', $date_format );
	$date_format = str_replace( 'Y', 'yy', $date_format ); 
	$date_format = str_replace( 'm', 'mm', $date_format ); 
	return $date_format;
}