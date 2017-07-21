<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Abstract class for creating sharing methods
 */
abstract class GA_Sharer {
	
	/**
	 * Link used for sharing
	 * @var string
	 */
	public $sharer_link = '';

	/**
	 * Icons class
	 * @var string
	 */
	public $sharer_icon = '';

	/**
	 * Returning the HTML for the link
	 * @param  string $url     
	 * @param  string $text    
	 * @param  array $display Display options
	 * @return string          
	 */
	public function get_link( $url, $text, $display ) {
		
		return '<a target="_blank" href="' . $this->sharer_link . $this->sharing_attributes( $url, $text, $display ) . '">' . $this->get_sharer_icon() . $this->get_sharer_text() . '</a>';
	}

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
	 * Returning the text for the button
	 * @return string 
	 */
	public function get_sharer_text(){
		return '';
	}

	/**
	 * Returning the sharer icon
	 * @return [type] [description]
	 */
	public function get_sharer_icon() {
		if( $this->sharer_icon === false ) {
			return '';
		}
		return '<span class="fa fa-' . $this->sharer_icon . '"></span>';
	}
}