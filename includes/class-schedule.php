<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class for controlling the scheduling in the plugin
 */
class GA_Schedule {

	/**
	 * End Date
	 * @var null
	 */
	private $end_date = null;

	/**
	 * End Time
	 * @var null
	 */
	private $end_time = null;	

	/**
	 * Start Date
	 * @var null
	 */
	private $start_date = null;

	/**
	 * Start Time
	 * @var null
	 */
	private $start_time = null;

	/**
	 * Winner Date
	 * @var null
	 */
	private $winner_date = null;

	/**
	 * Timestamp Start
	 * @var null
	 */
	private $start_timestamp = null;

	/**
	 * Timestamp End
	 * @var null
	 */
	private $end_timestamp = null;

	/**
	 * Timestamp Announcement for Winner
	 * @var null
	 */
	private $winner_timestamp = null;

	/**
	 * Winner Time
	 * @var null
	 */
	private $winner_time = null;

	/**
	 * Date Format
	 * @var string
	 */
	private $date_format = '';

	/**
	 * Giveaway
	 * @var null
	 */
	private $giveaway = null;

	/**
	 * Setting the attributes with the schedule options
	 * @param array $schedule Schedule options
	 */
	public function __construct( $schedule, $giveaway = null ) {

		$this->end_date = $schedule['end_date'];
		$this->end_time = $schedule['end_time'];
		$this->start_date = $schedule['start_date'];
		$this->start_time = $schedule['start_time'];
		$this->winner_date = $schedule['winner_date'];
		$this->winner_time = $schedule['winner_time'];
		$this->date_format = isset( $schedule['date_format'] ) ? $schedule['date_format'] : 'd-m-Y';
		$current_offset = get_option('gmt_offset');
		$tzstring = get_option('timezone_string');

		if( $tzstring ) {
			date_default_timezone_set( $tzstring );
		}

		$this->end_timestamp = $this->get_timestamp( $this->end_date, $this->end_time );
		$this->start_timestamp = $this->get_timestamp( $this->start_date, $this->start_time );
		$this->winner_timestamp = $this->get_timestamp( $this->winner_date, $this->winner_time );

		if( $giveaway ) {

			if( is_int( $giveaway ) ) {
				$giveaway = get_post( $giveaway );
			}

			$this->giveaway = $giveaway;
		}

	}

	/**
	 * Getting the timestamp
	 * @param  string $date 
	 * @param  string $time 
	 * @return int       
	 */
	public function get_timestamp( $date, $time ) {

		$date_format = 'd-m-Y';
		$datetime = DateTime::createFromFormat( $date_format . ' H:i', $date . ' ' . $time );
		return $datetime->getTimestamp();
	}

	/**
	 * Returns the Winner timestamp
	 * @return int 
	 */
	public function get_winner_timestamp() {
		return $this->winner_timestamp;
	}

	/**
	 * Returns the Ending timestamp
	 * @return int 
	 */
	public function get_end_timestamp() {
		return $this->end_timestamp;
	}

	/**
	 * Returning the formatted date
	 * @param  string $format Format 
	 * @param  string $from   Which date to use, start|end|winner
	 * @return string         Formatted date
	 */
	public function format( $format, $from = 'start' ){
		 
		$date = "now";
		switch ( $from ) {
			case 'start': 
				$date = $this->start_date;
				break;
			case 'end':
				$date = $this->end_date;
				break;
			case 'winner':
				$date = $this->winner_date;
				break;
			default:
				break;
		}
		$date_format = 'd-m-Y';
		$datetime = DateTime::createFromFormat( $date_format, $date );
		$the_format = $datetime->format( $format );

		return $the_format;
	}

	/**
	 * Getting the Formatted date from WP 
	 * @param  string $from 
	 * @return string       
	 */
	public function get_wp_format( $from ) {
		return $this->format( get_option( 'date_format' ), $from );
	}

	/**
	 * Getting the countdown format for creating countdown
	 * @return string 
	 */
	public function get_countdown_format(){
		return $this->format( 'Y-m-d', 'end' ) . ' ' . $this->end_time . ':00';
	}

	/**
	 * Check if the current Giveaway had ended
	 * @return boolean 
	 */
	public function has_ended() {
		
		$datetime = new DateTime();
		
		$now = $datetime->getTimestamp();
	 	
	 	$giveaway_id = 0;

	 	if( null !== $this->giveaway ) {
	 		$giveaway_id = $this->giveaway->ID;
	 	}
	 	 
		$datetime->setTimestamp( giveasap_get_end_time( $giveaway_id ) );
		
		$end = $datetime->getTimestamp();
		 
		if( $now > $end ) {
			return true;
		}
		return false;
	}



}