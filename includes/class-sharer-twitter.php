<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Twitter Sharer
 */
class GA_Sharer_Twitter extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = 'https://twitter.com/intent/tweet?';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'twitter';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		return 'url=' . $url .'&text=' . $text;
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Tweet it', 'giveasap' );
	}

}