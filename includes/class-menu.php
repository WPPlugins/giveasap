<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class for creating menu pages 
 */
class GiveASAP_Menu extends GiveASAP_WordPressSettings {

	/**
	 * Default options
	 * @var array
	 */
	public $defaultOptions = array(
		'slug' => '', // Name of the menu item
		'title' => '', // Title displayed on the top of the admin panel
		'page_title' => '',
		'parent' => null, // id of parent, if blank, then this is a top level menu
		'id' => '', // Unique ID of the menu item
		'capability' => 'manage_options', // User role
		'icon' => 'dashicons-admin-generic', // Menu icon for top level menus only http://melchoyce.github.io/dashicons/
		'position' => null, // Menu position. Can be used for both top and sub level menus
		'desc' => '', // Description displayed below the title
		'function' => ''
	);

	/**
	 * Gets populated on submenus, contains slug of parent menu
	 * @var null
	 */
	public $parent_id = null;

	/**
	 * Menu options
	 * @var array
	 */
	public $menu_options = array();

	function __construct( $options ) {
		
		$this->menu_options = array_merge( $this->defaultOptions, $options );

		if( $this->menu_options['slug'] == '' ){

			return;
		}

		$this->settings_id = $this->menu_options['slug'];

		$this->prepopulate();

		add_action( 'admin_menu', array( $this, 'add_page' ) );

		add_action( 'wordpressmenu_page_save_' . $this->settings_id, array( $this, 'save_settings' ) );

	}

	/**
	 * Populate some of required options
	 * @return void 
	 */
	public function prepopulate() {

		if( $this->menu_options['title'] == '' ) {
			$this->menu_options['title'] = ucfirst( $this->menu_options['slug'] );
		}

		if( $this->menu_options['page_title'] == '' ) {
			$this->menu_options['page_title'] = $this->menu_options['title'];
		}

	}

	/**
	 * Add the menu page using WordPress API
	 * @return [type] [description]
	 */
	public function add_page() {

		$functionToUse = $this->menu_options['function'];

		if( $functionToUse == '' ) {
			$functionToUse = array( $this, 'create_menu_page' );
		}
		
		if( $this->menu_options['parent'] != null ){
 
			 add_submenu_page( $this->menu_options['parent'],
				$this->menu_options['page_title'],
				$this->menu_options['title'],
				$this->menu_options['capability'],
				$this->menu_options['slug'],
				$functionToUse );

		} else {

			add_menu_page( $this->menu_options['page_title'],
				$this->menu_options['title'],
				$this->menu_options['capability'],
				$this->menu_options['slug'],
				$functionToUse,
				$this->menu_options['icon'],
				$this->menu_options['position'] );

		}
		
	}

	/**
	 * Render the registered tabs
	 * @param  string $active_tab the viewed tab
	 * @return void          
	 */
	public function render_tabs( $active_tab = 'general' ) {

		if( count( $this->tabs ) > 1 ) {

			echo '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">';

				foreach ($this->tabs as $key => $value) {

					echo '<a href="' . admin_url('admin.php?page=' . $this->menu_options['slug'] . '&tab=' . $key ) . '" class="nav-tab ' .  ( ( $key == $active_tab ) ? 'nav-tab-active' : '' ) . ' ">' . $value . '</a>';

				}

			echo '</h2>';
			echo '<br/>';

		}
	}

	/**
	 * Render the save button
	 * @return void 
	 */
	protected function save_button() { 
		?>
		<button type="submit" name="<?php echo $this->settings_id; ?>_save" class="button button-primary">
			<?php _e( 'Save', 'textdomain' ); ?>
		</button>
		<?php
	}

	/**
	 * Save if the button for this menu is submitted
	 * @return void 
	 */
	protected function save_if_submit() {
		if( isset( $_POST[ $this->settings_id . '_save' ] ) ) {
			do_action( 'wordpressmenu_page_save_' . $this->settings_id );
		}
	}

	/**
	 * Create the menu page
	 * @return void 
	 */
	public function create_menu_page() {

		$this->save_if_submit();

		$tab = 'general';

		if( isset( $_GET['tab'] ) ) {
			$tab = $_GET['tab'];
		}

		$this->init_settings();

		?>
		<div class="wrap">
			<h2><?php echo $this->menu_options['page_title'] ?></h2>
			<?php
				if ( ! empty( $this->menu_options['desc'] ) ) {
					?><p class='description'><?php echo $this->menu_options['desc'] ?></p><?php
				}

				$this->render_tabs( $tab );

			?>
			<form method="POST" action="">
				<div class="postbox">
					<div class="inside">
						<table class="form-table">
							<?php $this->render_fields( $tab ); ?>
						</table>
						<?php $this->save_button(); ?>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

}

/**
 * Class for creating tabs in a menu page
 */
class GiveASAP_MenuTab {

	/**
	 * Slug of the tab
	 * @var string
	 */
	public $slug;

	/**
	 * Title of the tab
	 * @var string
	 */
	public $title;

	/**
	 * Menu
	 * @var GiveASAP_Menu
	 */
	public $menu;

	/**
	 * Creating a tab
	 * @param array        $options 
	 * @param GiveASAP_Menu $menu    
	 */
	function __construct( $options, GiveASAP_Menu $menu ){

		$this->slug = $options['slug'];
		$this->title = $options['title'];
		$this->menu = $menu;

		$this->menu->add_tab( $options );

	}

	/**
	 * Add field to this tab
	 * @param [type] $array [description]
	 */
	public function add_field( $array ){

		$this->menu->add_field( $array, $this->slug );
	}
}

/**
 * Creating Menus
 */

$giveASAP_menu = new GiveASAP_Menu( array(
	'parent' => 'edit.php?post_type=giveasap',
	'slug' => 'giveasap_settings',
	'title' => __( 'Settings', 'giveasap'),
	'desc' => __( 'GiveASAP Settings', 'giveasap'),
	'position' => 99,
));

$giveASAP_doc = new GiveASAP_Menu( array(
	'parent' => 'edit.php?post_type=giveasap',
	'slug' => 'giveasap_documentation',
	'title' => __( 'Documentation', 'giveasap'), 
	'position' => 99,
	'function' => 'giveasap_documentation'
));

$giveASAP_menu->add_field(array(
	'name' => 'subscriber_email_subject',
	'title' => __( 'Subscriber Subject', 'giveasap'),
	'desc' => __( 'Subject that will be sent to new subscribers.<br/>Placeholders:<br/> {{TITLE}} : giveaway title', 'giveasap' ), 
	'default' => __( 'You have enrolled to {{TITLE}}. Good Luck!', 'giveasap' )
	));

$giveASAP_menu->add_field(array(
	'name' => 'subscriber_email',
	'title' => __( 'Subscriber Email', 'giveasap'),
	'desc' => __( 'Notification email with the links. <br/>Placeholders:<br/> {{TITLE}} : giveaway title<br/>{{ENTRY_LINK}} : link to see their entries with sharing button<br/>{{SHARE_LINK}} : link to share', 'giveasap' ),
	'type' => 'wpeditor',
	'default' => __( 'Thank you for subscribing to {{TITLE}}<br/>You can check your entries at {{ENTRY_LINK}}<br/>To get more entries and have a higher chance of winning, please do share your link: {{SHARE_LINK}}', 'giveasap' )
	));

$giveASAP_menu_google = new GiveASAP_MenuTab( array(
		'slug' => 'google_tab',
		'title' => __( 'Google Captcha', 'giveasap')
	),
	$giveASAP_menu
	);

$giveASAP_menu_google->add_field(array(
	'name' => 'google_site_key',
	'title' => __( 'Google CAPTCHA - Site Key', 'giveasap' ), 
	));

$giveASAP_menu_google->add_field(array(
	'name' => 'google_secret_key',
	'title' => __( 'Google CAPTCHA - Secret Key', 'giveasap' ), 
	));


 
