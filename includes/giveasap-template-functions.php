<?php
/**
 * Functions for getting values in templates
 */
if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Creating the gallery
 * @param  array $gallery_images  Array of image ID's
 * @param  string $size           Image Size
 * @return string                 HTML
 */
function the_giveasap_gallery( $gallery_images, $size = 'full' ) {
	if( $gallery_images != '') {

		$gallery_images_array = explode( ',', $gallery_images );
		$content = '<ul class="giveasap_gallery">';
			foreach ( $gallery_images_array as $image_id ) {
	                	
            	$image = wp_get_attachment_image_src( $image_id, $size );
				$html = '';
				if( $image ) {
					$html = '<img src="' . $image[0] . '" />';
				}
                
                if( $html == '' ) {
                	continue;
                } 

                $content .= '<li>';
                	 
                		$content .= $html;
                 
                $content .= '</li>';
            }
			
		$content .= '</ul>';
		echo $content;
		 
	}
}

/**
 * Applying only the core WordPress the_content hooks for rendering content
 * @param  boolean $echo If false, the the content will be returned
 * @return mixed         Void if $echo true, string if $echo is false
 */
function giveasap_the_content( $echo = true ) {
	$content = apply_filters( 'giveasap_the_content', get_the_content() );
	if( $echo ) {
		echo $content;
	} else {
		return $content;
	}
}

/**
 * Returning the first image of the gallery
 * @param  string $gallery_images String of IDs separated by a comma
 * @return string                 URL of the image
 */
function the_giveasap_first_gallery_image( $gallery_images ) {
	if( $gallery_images != '') {

		$gallery_images_array = explode( ',', $gallery_images );
		$first_image = $gallery_images_array[0];
		$image = wp_get_attachment_image_src( $first_image, 'full' );
		return $image[0];
	}
}

/**
 * Getting the URL of the image
 * @param  int 		$image_id 	ID of the image
 * @param  string 	$size 		Size of the image
 * @return string           	URL of the image
 */
function giveasap_get_image_url( $image_id, $size = 'full' ) {
	if( !is_numeric( $image_id ) ) {
		return false;
	}

	$image = wp_get_attachment_image_src( $image_id, $size );
	return $image[0];
}

/**
 * Displays an alert if a giveaway has ended
 * @return void 
 */
function giveasap_show_ended_alert() {
	echo '<div class="giveasap_alert giveasap_ended">';
		_e( 'Ended', 'giveasap' );
	echo '</div>';
}
 