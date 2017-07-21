<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Link for sharing
 */
class GA_Sharer_Link extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = '';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'envelope';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		 
		return '';
	}

	/**
	 * Returning the HTML for the link
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string          
	 */
	public function get_link( $url, $text, $display ) {
		
		return '<input type="text" readonly value="' . urldecode( $url ) . '"/><p class="giveasap_description">' . __( 'Copy and share the link where you want.', 'giveasap') . '</p>';
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Share Link', 'giveasap' );
	}

}