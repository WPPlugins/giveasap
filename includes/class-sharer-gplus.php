<?php

class GA_Sharer_GPlus extends GA_Sharer {

	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = 'https://plus.google.com/share?';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = 'google-plus';

	/**
	 * Setting the sharing attributes
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string         
	 */
	public function sharing_attributes( $url, $text, $display ){
		return 'url=' . $url;
	}

	/**
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return __( 'Share on Google+', 'giveasap' );
	}

}