<?php

/**
 * GiveASAP Installer
 */

if( ! defined( 'ABSPATH') ) {
	return;
}

/**
 * Class to perform creating database and other stuff
 * @since  2.0.0
 */
class GA_Installer {

	public $updates = array(
		'2.0.0' => 'giveasap_update_200',
		'2.2.6' => 'giveasap_update_206'
	);

	/**
	 * Start the installation
	 * @return void 
	 */
	public function install() {

		if ( ! defined( 'GASAP_INSTALLING' ) ) {
			define( 'GASAP_INSTALLING', true );
		}

		$this->create_settings();
		$this->create_db();

	}

	/**
	 * Start the installation
	 * @return void 
	 */
	public function update( $from_version ) {

		if ( ! defined( 'GASAP_UPDATING' ) ) {
			define( 'GASAP_UPDATING', true );
		}

		foreach ( $this->updates as $version => $update_function ) {
			if ( version_compare( $from_version, $version, '<' ) ) {
				 $update_function();
			}
		}

		update_option( 'giveasap_version', gasap()->version() );

	}

	/**
	 * Create the Database
	 * @return void 
	 */
	public function create_db() {

		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $this->get_schema() );
	}

	/**
	 * Create Settings
	 * @return void
	 */
	public function create_settings() {
		$settings = get_option( 'giveasap_settings', 'not_installed' );

		if( 'not_installed' === $settings ) {
			add_option( 'giveasap_settings', array(
				'subscriber_email_subject' => __( 'You have enrolled to {{TITLE}}. Good Luck!', 'giveasap' ),
				'subscriber_email' => __( 'Thank you for subscribing to {{TITLE}}<br/>You can check your entries at {{ENTRY_LINK}}<br/>To get more entries and have a higher chance of winning, please do share your link: {{SHARE_LINK}}', 'giveasap' ),

			));
		}
	}

	/**
	 * Get Table schema.
	 * @return string
	 */
	private function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of WordPress 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 *
		 * This may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
		 * indexes first causes too much load on some servers/larger DB.
		 */
		$max_index_length = 191;

		$tables = "
CREATE TABLE {$wpdb->prefix}giveasap_entries (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  post_id bigint(20) NOT NULL,
  email longtext NOT NULL,
  entries bigint(20) NOT NULL DEFAULT 1,
  ref_id varchar(32),
  date datetime,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
) $collate;
CREATE TABLE {$wpdb->prefix}giveasap_meta (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `giveasap_id` bigint(20) NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `giveasap_id` (`giveasap_id`),
  KEY `meta_key` (`meta_key`)
) $collate";

		return $tables;
	}

}