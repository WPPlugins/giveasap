<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class to handle everything on the front
 */
class GiveASAP_Front {

	/**
	 * GiveAWAY Steps
	 * @var integer
	 */
	private $step = 1;

	/**
	 * Post variable
	 * @var null
	 */
	private $post = null;

	/**
	 * Posted Data from Form
	 * @var null
	 */
	public $posted_data = null;

	/**
	 * Errors
	 * @var null
	 */
	public $errors = null;

	/**
	 * Unique ID 
	 * @var integer
	 */
	public $uniqid = 0;

	/**
	 * Referent ID - to view your social links
	 * @var integer
	 */
	public $refID = 0;

	/**
	 * Share ID - from sharing links
	 * @var integer
	 */
	public $shareID = 0;

	/**
	 * Array of all registered users
	 * - key = ref/share ID
	 * - value = email
	 * @var array
	 */
	public $registered_users = array();

	/**
	 * Array of all registered entries
	 * - key = ref/share ID
	 * - value = entry
	 * @var array
	 */
	public $registered_entries = array();

	/**
	 * Array of all registered IDs
	 * @var array
	 */
	private $registered_ids = array();

	/**
	 * The permalink
	 * @var string
	 */
	private $permalink = '';

	/**
	 * Sharing methods
	 * @var null
	 */
	private $sharing = null;

	/**
	 * Current Entries
	 * @var integer
	 */
	public $current_entries = 0;

	/**
	 * Registered User
	 * @var null
	 */
	public $user = null;

	/**
	 * User who shared
	 * @var null
	 */
	public $sharer = null;

	/**
	 * GiveASAP Settings
	 * @var array
	 */
	public $giveasap_settings = array();

	/**
	 * Constructing the Front
	 * @param object $post Post Object
	 */
	public function __construct( $post, $display ){
		$this->post = $post;
		$this->permalink = get_permalink( $post->ID );
		$this->errors = new WP_Error();
	    // DEPRECATED $this->get_options();
	    $this->create_uniqid();
	    $this->check_cookie();
		$this->get_ref();
		$this->get_share();
		$this->creating_steps();

		if( $this->step == 2 ) {

			$this->sharing = new GA_Sharing( $this->get_share_link( true ), $this->post->post_title,  $display  );
			add_filter( 'giveasap_sharing_methods', array( $this, 'set_sharing_methods' ) );
			
		}

	}

	/**
	 * Get share link
	 * @return string Link with the sharing tag
	 */
	public function get_share_link( $encode_url = false ){
		$link = $this->permalink;
		if( false === strpos( $link, "?" ) ) {
			$link .= "?";
		}
		$link .= "share=" . $this->get_uniqid();
		if( $encode_url ) {
			$link = urlencode( $link );
		}
		return $link;
	}

	/**
	 * Create unique ID
	 * @return bool
	 */
	public function create_uniqid() {
		$this->uniqid = uniqid();
	}

	/**
	 * Getting registered users and entries
	 * @deprecated 2.0.0. Not in use anymore
	 * @return void 
	 */
	public function get_options() {
		$this->registered_users = get_post_meta( $this->post->ID, 'giveasap_registered_users', true );
		$this->registered_entries = get_post_meta( $this->post->ID, 'giveasap_registered_entries', true );
		if( ! is_array( $this->registered_users ) ) {
			$this->registered_users = array();
		}
		if( ! is_array( $this->registered_entries ) ) {
			$this->registered_entries = array();
		}
		$this->registered_ids = array_keys( $this->registered_users );
	}

	/**
	 * Get referent ID if supplied. This will create the step 2 with sharing links
	 * @return void 
	 */
	public function get_ref() {
		if( $this->step == 1 && isset( $_GET[ 'ref' ] ) && $_GET['ref'] != '' ) {
			$this->refID = $_GET['ref'];
			$this->set_uniqid( $this->refID );
			$this->user = giveasap_get_user( $this->refID, $this->post->ID );

			if( null != $this->user ) {
				// Make sure we are using the ID
				$this->create_cookie();
				 
				$this->set_step( 2 );
			}
		}
	}

	/**
	 * Check cookie, if exists, it will create the step 2 page
	 * @return void 
	 */
	public function check_cookie() {
		if( isset( $_COOKIE ) && isset( $_COOKIE[ 'giveasap_' . $this->post->ID ] ) ) {
			$this->refID = $_COOKIE[ 'giveasap_' . $this->post->ID ];
			$this->set_uniqid( $this->refID );
			$this->user = giveasap_get_user( $this->refID, $this->post->ID );

			if( null != $this->user ) { 
				$this->set_step( 2 );
			}
		}
	}

	/**
	 * Get the share ID if supplied. This will set an input to count up the entry
	 * @return void 
	 */
	public function get_share() {

		// We don't need that if we are already on step 2
		if( $this->step == 2 ) {
			return;
		}

		if( isset( $_GET[ 'share' ] ) && $_GET['share'] != '' ) {
			$this->shareID = $_GET['share'];
		}

		if( isset( $_POST[ 'user_share' ] ) && $_POST['user_share'] != '' ) {
			$this->shareID = $_POST['user_share'];
		}

		$this->sharer = giveasap_get_user( $this->shareID, $this->post->ID );

		if( null != $this->sharer ) {
			// Let's make sure we are using the ID instead of ref_id
			$this->shareID = $this->sharer->ref_id;
		} else {
			$this->shareID = 0;
		}
	}

	/**
	 * Gets the uniqe ID
	 * @return void 
	 */
	public function get_uniqid() {
		return $this->uniqid;
	}

	/**
	 * Set the uniqe ID
	 * @param string $uniqid 
	 */
	public function set_uniqid( $uniqid ){
		$this->uniqid = $uniqid;
	}

	/**
	 * Show Current Entries of a Referent ID
	 * @return void 
	 */
	public function current_entries(){
		if( null != $this->user ) {
			echo $this->user->entries;
		}
	}

	/**
	 * Get the form link with the ref ID set
	 * @return string Form Action link
	 */
	public function get_form_link(){
		$link = $this->permalink;
		if( false === strpos( $link, "?" ) ) {
			$link .= "?";
		}
		$link .= "ref=" . $this->get_uniqid();

		return $link;
	}

	/**
	 * Set the step 
	 * @param number $step 
	 */		
	public function set_step( $step ) {
		$this->step = $step;
	}

	/**
	 * Get Step 
	 * @return number 
	 */
	public function get_step(){
		return $this->step;
	}

	/**
	 * Check if user exists 
	 * @return boolean
	 */
	public function user_exists() {

		return giveasap_user_exists( $this->posted_data['user_email'], $this->post->ID );
	}

	/**
	 * Add error
	 * @param string $code    
	 * @param string $message 
	 */
	public function add_error( $code, $message ) {
		$this->errors->add( $code, $message );
	}

	/**
	 * Show the error from code
	 * @param  string $code 
	 * @return string       
	 */
	public function show_errors( $code ) {
		$errors = $this->errors->get_error_messages( $code );
		if( ! empty( $errors ) ){
			 foreach( $errors as $error ) {
			 	echo '<p class="giveasap_error">' . $error . '</p>';
			 }
		}
	}

	/**
	 * Register a user
	 * @return void 
	 */
	public function register_user(){
		$ref_id = '';
		
		if( isset( $this->posted_data['user_guid'] ) && $this->posted_data['user_guid'] != '' ) {
			$ref_id = $this->posted_data['user_guid'];
		} else {
			$ref_id = uniqid();
		}

		$registered_id = giveasap_register_user( $this->post->ID, $this->posted_data['user_email'], 1, $ref_id );

		if( $registered_id ) {
			$this->user = giveasap_get_user_by_id( $registered_id, $this->post->ID );
			$this->set_uniqid( $this->user->ref_id );
			$this->set_share_entry();
			$this->create_cookie(); 
			return true;
		}
		
		return false;
	}

	/**
	 * Create Cookie for a registered user
	 * @return void 
	 */
	public function create_cookie() {
		 setcookie( 'giveasap_' . $this->post->ID, $this->user->ref_id, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Register the entry
	 * @deprecated 2.0.0 Not in use anymore
	 * @return void 
	 */
	public function register_entry() {
		$this->registered_entries[ $this->uniqid ] = 1;
		update_post_meta( $this->post->ID, 'giveasap_registered_entries', $this->registered_entries );
		$this->set_share_entry();
	}

	/**
	 * Process Google Captcha
	 * @return void 
	 */
	public function process_captcha() {
		/* If Google Site Key exists */
		if( $this->giveasap_settings['google_site_key'] ) {

			if( ! isset( $this->posted_data['g-recaptcha-response'] ) ) {
				$this->add_error('google', __( 'Google Captcha failed', 'giveasap' ) );
				$this->set_step( 1 );
				return;
			}

			if( $this->posted_data['g-recaptcha-response'] == '' ) {
				$this->add_error('google', __( 'Google Captcha failed', 'giveasap' ) );
				$this->set_step( 1 );
				return;
			}

			$google_post = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', 
				array(
					'body' => array(
						'secret' => $this->giveasap_settings['google_secret_key'],
						'response' => $this->posted_data['g-recaptcha-response']
						)
					));

			if( is_wp_error( $google_post ) ){
				$this->add_error('google', $google_post->get_error_message() );
				$this->set_step( 1 );
				return;
			}

			$google_json = json_decode( wp_remote_retrieve_body( $google_post ), true );
			 
			if( ! $google_json['success'] ){

				$google_error_strings = array(
					'missing-input-secret' =>	__( 'The secret parameter is missing.', 'giveasap' ),
					'invalid-input-secret' =>	__( 'The secret parameter is invalid or malformed.', 'giveasap' ),
					'missing-input-response' =>	__( 'The response parameter is missing.', 'giveasap' ),
					'invalid-input-response' =>	__( 'The response parameter is invalid or malformed.', 'giveasap' )
				);

				$google_errors = isset( $google_json['error-codes'] ) ? $google_json['error-codes'] : '';
				if( is_array( $google_errors ) ){
					foreach ($google_errors as $error_code ) {
						$this->add_error('google', $google_error_strings[ $error_code ] );
					}
				} else {
					$error = isset( $google_error_strings[ $google_errors ] ) ? $google_error_strings[ $google_errors ] : __( 'Something went wrong with Captcha. Try again.', 'giveasap' );
					$this->add_error('google', $error );
				}

				$this->set_step( 1 );
				return;
			}

		}
	}

	/**
	 * Create the steps and register if needed
	 * @return void
	 */
	public function creating_steps(){
		$this->posted_data = $_POST;
		if( $this->posted_data ) {
			$this->giveasap_settings = get_option('giveasap_settings');

			if( ! isset( $this->posted_data['user_email'] ) ) {
				return;
			}

			if( $this->posted_data['user_email'] == '' ) {
				$this->add_error('email', __( 'Email is empty', 'giveasap' ) );
				$this->set_step( 1 );
				return;
			}

			if( $this->user_exists() ) {
				$this->add_error('email', __( 'Email is already subscribed', 'giveasap' ) );
				$this->set_step( 1 );
				return;
			}

			$this->process_captcha();
			$errors = $this->errors->get_error_codes();
			if( ! $errors ){

				$registered = $this->register_user();

				if( $registered ) {
					$subscriber_email = isset( $this->giveasap_settings['subscriber_email'] ) ? $this->giveasap_settings['subscriber_email']  : '';
					$subscriber_email_subject = isset( $this->giveasap_settings['subscriber_email_subject'] ) ? $this->giveasap_settings['subscriber_email_subject'] : __( 'Thank you, {{TITLE}} for entering in this giveaway', 'giveasap' );
					if( $subscriber_email != '' ) {
						$email = $this->posted_data['user_email'];
						$title = $this->post->post_title;
						$entry_link = $this->permalink . '?ref=' . $this->user->ref_id;
						$share_link = $this->permalink . '?share=' . $this->user->id;

						$subscriber_email_subject = str_replace('{{TITLE}}', $title, $subscriber_email_subject);

						$subscriber_email = str_replace('{{TITLE}}', $title, $subscriber_email);
						$subscriber_email = str_replace('{{ENTRY_LINK}}', $entry_link, $subscriber_email);
						$subscriber_email = str_replace('{{SHARE_LINK}}', $share_link, $subscriber_email);
	 
						add_filter( 'wp_mail_content_type', 'giveasap_set_mail_content_type', 20 );

						$mail_bool = wp_mail( $email, $subscriber_email_subject, $subscriber_email );

						remove_filter( 'wp_mail_content_type', 'giveasap_set_mail_content_type', 20 );
					}
					
					$this->set_step( 2 );
				} else {
					$this->add_error( 'email', __( 'Something went wrong. Try again please.', 'giveasap' ) );
				}
			}

		}

	}

	/**
	 * Set the entry for the shared one
	 * @return void 
	 */
	public function set_share_entry(){
		if( null != $this->user ) {
			if( isset( $_POST['user_share'] ) ) {
				$this->shareID = $_POST['user_share'];
				$this->sharer = giveasap_get_user( $this->shareID, $this->post->ID );
			}

			if( null != $this->sharer && ( $this->sharer->id != $this->user->id ) ) {
				$entries = (int) $this->sharer->entries;
				$entries += apply_filters( 'giveasap_entry_value', 1, $this->post->ID );
				
				giveasap_update_user_entry( $this->sharer->id, $entries );
				// DEPRECATED: $this->registered_entries[ $this->shareID ] = $entries;
				// DEPRECATED: update_post_meta( $this->post->ID, 'giveasap_registered_entries', $this->registered_entries );
				
			}
		}

	}

	/**
	 * Rendering all the sharing methods
	 * @return void 
	 */
	public function render_sharing_methods() {
		$this->sharing->render();
	}

	/**
	 * Setting all sharing methods
	 * @param array $methods
	 */
	public function set_sharing_methods( $methods ) {
		$methods['giveasap_facebook'] = new GA_Sharer_Facebook();
		$methods['giveasap_twitter'] = new GA_Sharer_Twitter();
		$methods['giveasap_gplus'] = new GA_Sharer_GPlus();
		$methods['giveasap_linkedin'] = new GA_Sharer_LinkedIN();
		$methods['giveasap_pinterest'] = new GA_Sharer_Pinterest();
		$methods['giveasap_link'] = new GA_Sharer_Link();
		return $methods;
	}


}