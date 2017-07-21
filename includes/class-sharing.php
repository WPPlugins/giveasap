<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class for creating and rendering all the sharing methods
 */
class GA_Sharing {

	public $sharing_methods = array();

	public $text = '';

	public $url = '';

	public $display_settings = '';

	public function __construct( $url, $text, $display ) {
		$this->text = $text;
		$this->url = $url;
	 
		$this->display_settings = $display;
		
	}

	public function get_encoded_text(){
		return htmlspecialchars(urlencode(html_entity_decode( $this->text, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
	}

	public function render(){
		$this->sharing_methods = apply_filters( 'giveasap_sharing_methods', array() );
	 
		if( ! empty( $this->sharing_methods ) ){
			echo '<ul class="giveasap_sharing row">';
			foreach ( $this->sharing_methods as $sharing_id => $sharing_object ) {
				 
				echo '<li class="sharing_method ' . $sharing_id . '">' . $sharing_object->get_link( $this->url, $this->get_encoded_text(), $this->display_settings ) . '</li>';

			}
			echo '</ul>';
		}
	}

}