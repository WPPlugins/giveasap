<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Abstract class for creating settings
 */
abstract class GiveASAP_WordPressSettings {
	/**
	 * ID of the settings
	 * @var string
	 */
	public $settings_id = '';
	/**
	 * Tabs for the settings page
	 * @var array
	 */
	public $tabs = array( 
		'general' => 'General' );
	/**
	 * Settings from database
	 * @var array
	 */
	public $settings = array();
	/**
	 * Array of fields for the general tab
	 * array(
	 * 	'tab_slug' => array(
	 * 		'field_name' => array(),
	 * 		),
	 * 	)
	 * @var array
	 */
	protected $fields = array();
	/** 
	 * Data gotten from POST
	 * @var array
	 */
	protected $posted_data = array();

	/**
	 * Get the settings from the database
	 * @return void 
	 */
	public function init_settings() {
	
		$this->settings = (array) get_option( $this->settings_id );
		foreach ( $this->fields as $tab_key => $tab ) {
			
			foreach ( $tab as $name => $field ) {
				
				if( isset( $this->settings[ $name ] ) ) {
					$this->fields[ $tab_key ][ $name ]['default'] = $this->settings[ $name ];
				}	
			
			}
		}
	}
	/**
	 * Save settings from POST
	 * @return [type] [description]
	 */
	public function save_settings(){
		
	 	$this->posted_data = $_POST;
	 	if( empty( $this->settings ) ) {
	 		$this->init_settings();
	 	}
	 	foreach ($this->fields as $tab => $tab_data ) {
	 		foreach ($tab_data as $name => $field) {
	 			
	 			$this->settings[ $name ] = $this->{ 'validate_' . $field['type'] }( $name );
	 	
	 		}
	 	}
	 	update_option( $this->settings_id, $this->settings );	
	}
	/**
	 * Gets and option from the settings API, using defaults if necessary to prevent undefined notices.
	 *
	 * @param  string $key
	 * @param  mixed  $empty_value
	 * @return mixed  The value specified for the option or a default value for the option.
	 */
	public function get_option( $key, $empty_value = null ) {
		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}
		// Get option default if unset.
		if ( ! isset( $this->settings[ $key ] ) ) {
			$form_fields = $this->fields;
			foreach ( $this->tabs as $tab_key => $tab_title ) {
				if( isset( $form_fields[ $tab_key ][ $key ] ) ) {
				
					$this->settings[ $key ] = isset( $form_fields[ $tab_key ][ $key ]['default'] ) ? $form_fields[ $tab_key ][ $key ]['default'] : '';
				
				}
			}
			
		}
		if ( ! is_null( $empty_value ) && empty( $this->settings[ $key ] ) && '' === $this->settings[ $key ] ) {
			$this->settings[ $key ] = $empty_value;
		}
		return $this->settings[ $key ];
	}

	/**
	 * Validate gallery field
	 * @param  string $key name of the field
	 * @return string     
	 */
	public function validate_gallery( $key ){
		$text  = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $this->posted_data[ $key ] ) ) );
		}
		return $text;
	}

	/**
	 * Validate image field
	 * @param  string $key name of the field
	 * @return string     
	 */
	public function validate_image( $key ){
		$text  = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $this->posted_data[ $key ] ) ) );
		}
		return $text;
	}
  	
  	/**
	 * Validate text field
	 * @param  string $key name of the field
	 * @return string     
	 */
	public function validate_color( $key ){
		$text  = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $this->posted_data[ $key ] ) ) );
		}
		return $text;
	}

  	/**
	 * Validate text field
	 * @param  string $key name of the field
	 * @return string     
	 */
	public function validate_text( $key ){
		$text  = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $this->posted_data[ $key ] ) ) );
		}
		return $text;
	}

	/**
	 * Validate datetime field
	 * @param  string $key name of the field
	 * @return string     
	 */
	public function validate_datetime( $key ){
		$text  = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $this->posted_data[ $key ] ) ) );
		}
		return $text;
	}

	/**
	 * Validate textarea field
	 * @param  string $key name of the field
	 * @return string      
	 */
	public function validate_textarea( $key ){
		$text  = $this->get_option( $key );
		 
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses( trim( stripslashes( $this->posted_data[ $key ] ) ),
				array_merge(
					array(
						'iframe' => array( 'src' => true, 'style' => true, 'id' => true, 'class' => true )
					),
					wp_kses_allowed_html( 'post' )
				)
			);
		}
		return $text;
	}
	/**
	 * Validate WPEditor field
	 * @param  string $key name of the field
	 * @return string      
	 */
	public function validate_wpeditor( $key ){
		$text  = $this->get_option( $key );
		 
		if ( isset( $this->posted_data[ $key ] ) ) {
			$text = wp_kses( trim( stripslashes( $this->posted_data[ $key ] ) ),
				array_merge(
					array(
						'iframe' => array( 'src' => true, 'style' => true, 'id' => true, 'class' => true )
					),
					wp_kses_allowed_html( 'post' )
				)
			);
		}
		return $text;
	}
	/**
	 * Validate select field
	 * @param  string $key name of the field
	 * @return string      
	 */
	public function validate_select( $key ) {
		$value = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$value = stripslashes( $this->posted_data[ $key ] );
		}
		return $value;
	}
	/**
	 * Validate radio
	 * @param  string $key name of the field
	 * @return string      
	 */
	public function validate_radio( $key ) {
		$value = $this->get_option( $key );
		if ( isset( $this->posted_data[ $key ] ) ) {
			$value = stripslashes( $this->posted_data[ $key ] );
		}
		return $value;
	}
	/**
	 * Validate checkbox field
	 * @param  string $key name of the field
	 * @return string      
	 */
	public function validate_checkbox( $key ) {
		$status = '0';
		if ( isset( $this->posted_data[ $key ] ) && ( 1 == $this->posted_data[ $key ] ) ) {
			$status = '1';
		}
		return $status;
	}

	/**
	 * Adding fields 
	 * @param array $array options for the field to add
	 * @param string $tab tab for which the field is
	 */
	public function add_field( $array, $tab = 'general' ) {
		$allowed_field_types = array(
			'text',
			'textarea',
			'wpeditor',
			'select',
			'radio',
			'checkbox',
			'datetime',
			'gallery',
			'image',
			'color' );
		// If a type is set that is now allowed, don't add the field
		if( isset( $array['type'] ) &&$array['type'] != '' && ! in_array( $array['type'], $allowed_field_types ) ){
			return;
		}
		$defaults = array(
			'name' => '',
			'title' => '',
			'default' => '',
			'placeholder' => '',
			'type' => 'text',
			'options' => array(),
			'default' => '',
			'desc' => '',
			);
		$array = array_merge( $defaults, $array );
		if( $array['name'] == '' ) {
			return;
		}
		foreach ( $this->fields as $tabs ) {
			if( isset( $tabs[ $array['name'] ] ) ) {
				trigger_error( 'There is alreay a field with name ' . $array['name'] );
				return;
			}
		}
		// If there are options set, then use the first option as a default value
		if( ! empty( $array['options'] ) && $array['default'] == '' ) {
			$array_keys = array_keys( $array['options'] );
			$array['default'] = $array_keys[0];
		}
		if( ! isset( $this->fields[ $tab ] ) ) {
			$this->fields[ $tab ] = array();
		}
		$this->fields[ $tab ][ $array['name'] ] = $array;
	}
	
	/**
	 * Adding tab
	 * @param array $array options
	 */
	public function add_tab( $array ) {
		$defaults = array(
			'slug' => '',
			'title' => '' );
		$array = array_merge( $defaults, $array );
		if( $array['slug'] == '' || $array['title'] == '' ){
			return;
		}
		$this->tabs[ $array['slug'] ] = $array['title'];
	}

	/**
	 * Get all fields from settings and from filters
	 * @return void 
	 */
	public function get_all_fields() {
		$this->fields = apply_filters( 'giveasap_' . $this->settings_id . '_fields', $this->fields );
	}

	/**
	 * Rendering fields 
	 * @param  string $tab slug of tab
	 * @return void  
	 */
	public function render_fields( $tab ) {

		if( ! isset( $this->fields[ $tab ] ) ) {
			echo '<p>' . __( 'There are no settings on these page.', 'textdomain' ) . '</p>';
			return;
		}
		$loadColorPicker = false;
		$loadDatePicker = false;
		foreach ( $this->fields[ $tab ] as $name => $field ) {
			if( $field['type'] == 'color' ) {
				$loadColorPicker = true;
			}
			if( $field['type'] == 'datetime' ) {
				$loadDatePicker = true;
			}

			$this->{ 'render_' . $field['type'] }( $field );

		}
		?>
		<script type="text/javascript">

			jQuery(document).ready(function() {
				<?php if( $loadColorPicker ): ?>
					jQuery('.color-picker').wpColorPicker();
				<?php endif; ?>
				<?php if( $loadDatePicker ) : ?>
				    jQuery('.datepicker').datepicker({
				        dateFormat : 'dd-mm-yy',
				    });
			    <?php endif; ?>
			});

			</script>
		<?php
	}

	/**
	 * Render Gallery field
	 * @param  string $field options
	 * @return void     
	 */
	public function render_gallery( $field ){
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<!-- Creating a dynamic ID using the metabox ID for JavaScript-->
              <ul id="<?php echo $name; ?>" class="sortable_wordpress_gallery">
                  <?php 
                  // Getting all the image IDs by creating an array from string ( 1,3,5 => array( 1, 3, 5) )
                  $gallery = explode(",", $default );
                  
                  // If there is any ID, create the image for it
                  if( count( $gallery ) > 0 && $gallery[0] != '' ) {
                      foreach ( $gallery as $attachment_id ) {
                          
                          // Create a LI elememnt
                          $output = '<li tabindex="0" role="checkbox" aria-label="' . get_the_title( $attachment_id ) . '" aria-checked="true" data-id="' . $attachment_id . '" class="attachment save-ready selected details">';
                           // Create a container for the image. (Copied from the WP Media Library Modal to use the same styling)
                            $output .= '<div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">';
                              $output .= '<div class="thumbnail">';

                                $output .= '<div class="centered">';
                                 // Get the URL to that image thumbnail
                                  $output .= '<img src="'  . wp_get_attachment_thumb_url( $attachment_id ) . '" draggable="false" alt="">';
                                $output .= '</div>';

                              $output .= '</div>';

                            $output .= '</div>';
                            // Add the button to remove this image if wanted (we set the data-gallery to target the correct gallery if there are more than one)
                            $output .= '<button type="button" data-gallery="#' . $name . '" class="button-link check remove-sortable-wordpress-gallery-image" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>';

                          $output .= '</li>';
                          echo $output;
                      }         
                  }               
                  ?>
              </ul>
                  <!-- Hidden input used to save the gallery image IDs into the database -->
                  <!-- We are also creating dynamic IDs here so that we can easily target them in JavaScript -->
                  <input type="hidden" id="<?php echo $name; ?>_input" name="<?php echo $name; ?>" value="<?php echo $default; ?>" />
                  <!-- Button used to open the WordPress Media Library Modal -->
                  <button type="button" class="button button-primary add-sortable-wordpress-gallery" data-gallery="#<?php echo $name; ?>"><?php _e( 'Add Images', 'giveasap' ); ?></button>
               <?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Render Image field
	 * @param  string $field options
	 * @return void     
	 */
	public function render_image( $field ){
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<!-- Creating a dynamic ID using the metabox ID for JavaScript-->
              <ul id="<?php echo $name; ?>" class="sortable_wordpress_gallery">
                  <?php 
                  // Getting all the image IDs by creating an array from string ( 1,3,5 => array( 1, 3, 5) )
                  $gallery = explode(",", $default );
                  
                  // If there is any ID, create the image for it
                  if( count( $gallery ) > 0 && $gallery[0] != '' ) {
                      foreach ( $gallery as $attachment_id ) {
                          
                          // Create a LI elememnt
                          $output = '<li tabindex="0" role="checkbox" aria-label="' . get_the_title( $attachment_id ) . '" aria-checked="true" data-id="' . $attachment_id . '" class="attachment save-ready selected details">';
                           // Create a container for the image. (Copied from the WP Media Library Modal to use the same styling)
                            $output .= '<div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">';
                              $output .= '<div class="thumbnail">';

                                $output .= '<div class="centered">';
                                 // Get the URL to that image thumbnail
                                  $output .= '<img src="'  . wp_get_attachment_thumb_url( $attachment_id ) . '" draggable="false" alt="">';
                                $output .= '</div>';

                              $output .= '</div>';

                            $output .= '</div>';
                            // Add the button to remove this image if wanted (we set the data-gallery to target the correct gallery if there are more than one)
                            $output .= '<button type="button" data-gallery="#' . $name . '" class="button-link check remove-sortable-wordpress-gallery-image" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>';

                          $output .= '</li>';
                          echo $output;
                      }         
                  }               
                  ?>
              </ul>
                  <!-- Hidden input used to save the gallery image IDs into the database -->
                  <!-- We are also creating dynamic IDs here so that we can easily target them in JavaScript -->
                  <input type="hidden" id="<?php echo $name; ?>_input" name="<?php echo $name; ?>" value="<?php echo $default; ?>" />
                  <!-- Button used to open the WordPress Media Library Modal -->
                  <button type="button" class="button button-primary add-single-wordpress-image" data-gallery="#<?php echo $name; ?>"><?php _e( 'Add Image', 'giveasap' ); ?></button>
               <?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Render text field
	 * @param  string $field options
	 * @return void     
	 */
	public function render_text( $field ){
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<input class="widefat" type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $default; ?>" placeholder="<?php echo $placeholder; ?>" />	
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Render date field
	 * @param  string $field options
	 * @return void     
	 */
	public function render_datetime( $field ){
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<input type="<?php echo $type; ?>" class="datepicker" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $default; ?>" placeholder="<?php echo $placeholder; ?>" />	
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Render textarea field
	 * @param  string $field options
	 * @return void      
	 */
	public function render_textarea( $field ){
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>" ><?php echo $default; ?></textarea>	
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Render WPEditor field
	 * @param  string $field  options
	 * @return void      
	 */
	public function render_wpeditor( $field ){
		
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<?php wp_editor( $default, $name, array('wpautop' => false) ); ?>
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Render select field
	 * @param  string $field options
	 * @return void      
	 */
	public function render_select( $field ) {
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<select name="<?php echo $name; ?>" id="<?php echo $name; ?>" >
					<?php 
						foreach ($options as $value => $text) {
							echo '<option ' . selected( $default, $value, false ) . ' value="' . $value . '">' . $text . '</option>';
						}
					?>
				</select>
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Render radio
	 * @param  string $field options
	 * @return void      
	 */
	public function render_radio( $field ) {
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<?php 
					foreach ($options as $value => $text) {
						echo '<input name="' . $name . '" id="' . $name . '" type="'.  $type . '" ' . checked( $default, $value, false ) . ' value="' . $value . '">' . $text . '</option><br/>';
					}
				?>
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}
	/**
	 * Render checkbox field
	 * @param  string $field options
	 * @return void      
	 */
	public function render_checkbox( $field ) {
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<input <?php checked( $default, '1', true ); ?> type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1" placeholder="<?php echo $placeholder; ?>" />
				<?php echo $desc; ?>
			</td>
		</tr>

		<?php
	}

	/**
	 * Render checkbox field
	 * @param  string $field options
	 * @return void      
	 */
	public function render_color( $field ) {
		extract( $field );
		?>

		<tr>
			<th>
				<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
			</th>
			<td>
				<input type="text" class="color-picker" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $default; ?>" placeholder="<?php echo $placeholder; ?>" />
				<?php if( $desc != '' ) {
					echo '<p class="description">' . $desc . '</p>';
				}?>
			</td>
		</tr>

		<?php
	}
  
	
}