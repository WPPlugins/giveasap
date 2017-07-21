<?php

/**
 * GiveASAP Widget to Enable Entering
 */

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Adds Foo_Widget widget.
 */
class GiveASAP_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'giveasap_widget', // Base ID
			esc_html__( 'Simple Giveaway', 'giveasap' ), // Name
			array( 'description' => esc_html__( 'Display a form for people to enter the giveaway', 'giveasap' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if( empty( $instance['giveaway']) || 0 == $instance['giveaway'] ) {
			return;
		}

		$post = get_post( $instance['giveaway'] );

		if( $post->post_status !== 'publish' ) {
			return;
		}

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
	 	$giveasap_front = new GiveASAP_Front( $post, array() );
	 	$schedule = get_post_meta( $post->ID, 'giveasap_schedule', true );
    	$ga_schedule = new GA_Schedule( $schedule, $post );
    	$timezone = get_option('gmt_offset');
	 	$giveasap_settings = get_option( 'giveasap_settings' );
	 	if( $giveasap_front->get_step() == 1 ) {

		 	if( $giveasap_settings['google_site_key'] ) { ?>
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<?php
			}
			if ( ! empty( $instance['description'] ) ) {
				echo '<p>' . apply_filters( 'giveasap_widget_description', $instance['description'] ) . '</p>';
			}
		 	?>
		 	<div data-timezone="<?php echo $timezone; ?>" data-end="<?php echo giveasap_get_end_time( $post->ID ); ?>" class="giveasap_countdown">

			</div>
		 	<form class="giveasap_form" action="<?php echo $giveasap_front->get_form_link(); ?>" method="post">
				<input type="hidden" name="user_guid" value="<?php echo $giveasap_front->get_uniqid(); ?>" />

				<?php if( $giveasap_front->shareID != 0 ) { ?>
					<input type="hidden" name="user_share" value="<?php echo $giveasap_front->shareID; ?>" />
				<?php } ?>

				<?php $giveasap_front->show_errors( 'email' ); ?>
				<input required="required" class="giveasap_input" type="email" name="user_email" value="" placeholder="<?php _e( 'Enter your email', 'giveasap' ); ?>" />
				
				<?php if( $giveasap_settings['google_site_key'] ) { ?>
					<?php $giveasap_front->show_errors( 'google' ); ?>
					<div class="g-recaptcha" data-sitekey="<?php echo $giveasap_settings['google_site_key']; ?>"></div>
				<?php } ?>
				<button type="submit" class="giveasap_button" name="giveasap_submit"><?php _e( 'Enter', 'giveasap' ); ?></button>
				
			</form>
		<?php } else { 
			// Visitor has registered already
			// First, load the scripts if not loaded already
			?>
			<script type="text/javascript">
				if( typeof giveasap_fontawesome_loaded == 'undefined' ) {
					giveasap_fontawesome_loaded = false;
				}

				if( typeof giveasap_shortcode_loaded == 'undefined' ) {
					giveasap_shortcode_loaded = false;
				}

				if( ! giveasap_fontawesome_loaded ) {
					var link = document.createElement( "link" );
					link.href = "//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css";
					link.type = "text/css";
					link.rel = "stylesheet";
					link.media = "all";

					document.getElementsByTagName( "head" )[0].appendChild( link );
					giveasap_fontawesome_loaded = true;
				}
				if( ! giveasap_shortcode_loaded ) {
					var link = document.createElement( "link" );
					link.href = '<?php echo GASAP_URI; ?>/public/assets/css/giveasap-shortcode.css';
					link.type = "text/css";
					link.rel = "stylesheet";
					link.media = "all";

					document.getElementsByTagName( "head" )[0].appendChild( link );
					giveasap_shortcode_loaded = true;
				}
			</script>
			  
			<div class="giveasap-box">
			    <h3><?php _e( 'You are participating in this giveaway', 'giveasap' ); ?></h3>
				<p class="giveasap-entries"><?php _e( 'Your Current Entries:', 'giveasap' ); ?> <strong><?php $giveasap_front->current_entries(); ?></strong></p> 
				<h3 class="giveasap-entries-title"><?php _e( 'Share & collect entries', 'giveasap' ); ?></h3>
				<?php 

				$giveasap_front->render_sharing_methods();

				?>
			</div>
		<?php } ?>
		<a href="<?php echo $giveasap_front->get_form_link(); ?>"><?php _e( 'Read more about it here.', 'giveasap' ); ?></a>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$selected_giveaway = isset( $instance['giveaway'] ) ? $instance['giveaway'] : 0;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$description = isset( $instance['description'] ) ? $instance['description'] : '';
		$giveaways = get_posts( array(
			'post_type' => 'giveasap',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));
		 
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'giveasap' ); ?></label> 
			<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_attr_e( 'Description:', 'giveasap' ); ?></label> 
			<textarea class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php echo $description; ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'giveaway' ) ); ?>"><?php esc_attr_e( 'Giveaway:', 'giveasap' ); ?></label> 
			<?php if( ! empty( $giveaways ) ) { ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'giveaway' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'giveaway' ) ); ?>">
				<option value="0" <?php selected( $selected_giveaway, 0, true ); ?>><?php _e( 'Select a Giveaway', 'giveasap' ); ?></option>
				<?php

			 
					foreach ( $giveaways as $giveaway ) {
						echo '<option ' . selected( $selected_giveaway, $giveaway->ID, false ) . ' value="' . $giveaway->ID . '">' . $giveaway->post_title . '</option>';
					}
				

				?>
			</select>
			<?php } else { 
				echo '<br/>'; 
				echo '<em>' . __( 'There is no Giveaway currently running', 'giveasap' ) . '</em>'; 
			} ?>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['giveaway'] = ( ! empty( $new_instance['giveaway'] ) ) ? strip_tags( $new_instance['giveaway'] ) : '';
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';

		return $instance;
	}

} // class Foo_Widget