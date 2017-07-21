<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Pinterest Sharer
 */
class GA_Sharer_Pinterest extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = 'https://pinterest.com/pin/create/button/?';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'pinterest';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		
		$pinterest_image = '';
 
		if( isset( $display['pinterest_image'] ) ) {

			$pinterest_image = giveasap_get_image_url( $display['pinterest_image'] );

		} 

		if( $pinterest_image == '' || $pinterest_image == null) {
			if( isset( $diplay['images'] ) ) {
				$gallery_images = $display['images'];
				$pinterest_image = the_giveasap_first_gallery_image( $gallery_images );
			}
		}

		$mediaArg = '';

		if( $text != '' ) {
			$mediaArg .= '&description=' . urlencode($text);
		}

		if( $pinterest_image ) {
			$mediaArg .= '&media=' . urlencode( $pinterest_image );
		}



		
		return '&url=' . $url . $mediaArg;
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Pin it', 'giveasap' );
	}

}