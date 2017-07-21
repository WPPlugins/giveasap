<?php 

/**
 * Time Functions
 */

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Returns the ABBR for a given timezone.
 * If the timezone is an offset (exp. -1.5, 2.4 ) we are getting the Name of that timezone exp. (America/Los Angeles, Europe/Zagreb)
 * @param  string $offset
 * @return string           Abbreviation of the given timezone
 */
function giveasap_get_timezone_short( $offset = null ) {

	$timezone = false;

	if( null === $offset ) {
		$offset = get_option( 'gmt_offset' );
	}

	if ( 0 == $offset ) {
		return 'UTC';
	}

	$city = get_option( 'timezone_string' );

	if ( $city ) {
		return $city;
	}

	$seconds = $offset * 60 * 60;

	$abb_array = timezone_abbreviations_list();

	foreach ( $abb_array as $abbr ) {
		foreach ( $abbr as $city ) {
			if ( $city['offset'] == $seconds ) {
				$timezone =  $city['timezone_id'];
			}
		}
	}

	if( ! $timezone ) {
		return false;
	}

	$dateTime = new DateTime();

	$dateTime->setTimeZone( new DateTimeZone( $timezone ) );

	return $dateTime->format('T'); 
}

/**
 * Get the calculated End Time for the Giveaway
 * @param  integer $post_id 
 * @return mixed           
 */
function giveasap_get_end_time( $post_id = 0 ) {
	if( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	}
	 
	return giveasap_get_meta( $post_id, 'end_time', true );
}