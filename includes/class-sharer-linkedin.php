<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * LinkedIN Sharer
 */
class GA_Sharer_LinkedIN extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = 'https://www.linkedin.com/shareArticle?mini=true';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'linkedin';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		return '&url=' . $url . '&title=' . $text;
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Share on LinkedIN', 'giveasap' );
	}

}