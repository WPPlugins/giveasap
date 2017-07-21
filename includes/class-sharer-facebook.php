<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Facebook sharer
 */
class GA_Sharer_Facebook extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = 'https://www.facebook.com/sharer/sharer.php?';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'facebook';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		return 'u=' . $url;
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Share on Facebook', 'giveasap' );
	}

}