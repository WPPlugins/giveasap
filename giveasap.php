<?php

/**
 * Plugin Name: Simple Giveaways
 * Plugin URI:  http://www.wpsimplegiveaways.com
 * Description: Create beautiful giveaways and grow your email list.
 * Version:     2.2.8
 * Author:      Igor Benic
 * Author URI:  http://ibenic.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: giveasap
 * PHP version 5
 *
 * @category Plugin
 * @package  WordPress
 * @author   Igor Benić <i.benic@hotmail.com>
 * @license  GPL2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     http://ibenic.com
 *
 * @fs_premium_only /includes/pro.php, /public/templates/template1_image-right.php, /public/templates/template1_image.php, /public/templates/template2.php, /public/templates/template3.php, /public/assets/css/style_template1_image-right.css, /public/assets/css/style_template1_image.css, /public/assets/css/style_template2.css, /public/assets/css/style_template3.css, /public/assets/less/style_template1_image-right.less, /public/assets/less/style_template1_image.less, /public/assets/less/style_template2.less, /public/assets/less/style_template3.less
 */
// Create a helper function for easy SDK access.
function giv_fs()
{
    global  $giv_fs ;
    
    if ( !isset( $giv_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $giv_fs = fs_dynamic_init( array(
            'id'             => '396',
            'slug'           => 'giveasap',
            'type'           => 'plugin',
            'public_key'     => 'pk_c3503a67ed0a3814e2a092853633c',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'    => 'edit.php?post_type=giveasap',
            'contact' => false,
        ),
            'is_live'        => true,
        ) );
    }
    
    return $giv_fs;
}

// Init Freemius.
giv_fs();
if ( !defined( 'WPINC' ) ) {
    return;
}
define( 'GASAP_ROOT', plugin_dir_path( __FILE__ ) );
define( 'GASAP_URI', plugin_dir_url( __FILE__ ) );
/**
 * Load plugin textdomain.
 *
 * @return void
 */
function giveasap_load_textdomain()
{
    load_plugin_textdomain( 'giveasap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'giveasap_load_textdomain' );
/**
 * The constructor Single-ton class that contains every information
 *
 * @category Plugin
 * @package  WordPress
 * @author   Igor Benić <i.benic@hotmail.com>
 * @license  GPL2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     http://ibenic.com
 */
class GiveASAP
{
    /**
     * Plugin Version
     * @var string
     */
    private  $version = '2.2.8' ;
    /**
     * Instance
     * @var null
     */
    private static  $_instance = null ;
    /**
     * Meta object to operate with meta
     * @var null
     */
    public  $meta = null ;
    /**
     * Disabling the contructor method
     */
    private function __construct()
    {
    }
    
    /**
     * Returns the version of GiveASAP
     * @return string
     */
    public function version()
    {
        return $this->version;
    }
    
    /**
     * Getting the instance of this class
     * If there is no instance, it will create one
     * @return GiveASAP
     */
    public static function getInstance()
    {
        if ( null === static::$_instance ) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
    
    /**
     * Running Admin dependencies we need for this plugin
     * @return void
     */
    public function run_admin_dependencies()
    {
        include_once GASAP_ROOT . '/includes/giveasap-metabox.php';
        include_once GASAP_ROOT . '/admin/class-ga-column.php';
        include_once GASAP_ROOT . '/admin/documentation/screens.php';
    }
    
    /**
     * Running all admin functions and including files
     * @return void
     */
    public function run_admin()
    {
        if ( !is_admin() ) {
            return;
        }
        add_action( 'load-post.php', array( $this, 'run_admin_on_post' ) );
        add_action( 'load-post-new.php', array( $this, 'run_admin_on_post' ) );
        include_once GASAP_ROOT . '/includes/class-menu.php';
        include_once GASAP_ROOT . '/admin/custom-metabox.php';
    }
    
    /**
     * Running Admin files on the GiveASAP CPT
     * @return void
     */
    public function run_admin_on_post()
    {
        require_once GASAP_ROOT . '/admin/giveasap-cpt-metabox.php';
        require_once GASAP_ROOT . '/admin/giveasap-cpt-schedule.php';
    }
    
    /**
     * Running all hooks and filters
     * @return void
     */
    public function run_actions()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_filter( 'giveasap_the_content', 'wptexturize' );
        add_filter( 'giveasap_the_content', 'convert_smilies', 20 );
        add_filter( 'giveasap_the_content', 'wpautop' );
        add_filter( 'giveasap_the_content', 'shortcode_unautop' );
        add_filter( 'giveasap_the_content', 'prepend_attachment' );
        add_filter( 'giveasap_the_content', 'wp_make_content_images_responsive' );
        add_filter( 'giveasap_the_content', 'do_shortcode', 11 );
        
        if ( isset( $GLOBALS['wp_embed'] ) ) {
            add_filter( 'giveasap_the_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
            add_filter( 'giveasap_the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
        }
        
        add_filter( 'embed_oembed_html', array( $this, 'html_video_wrapper' ), 10 );
        add_action( 'widgets_init', array( $this, 'register_widgets' ) );
        add_action( 'init', array( $this, 'check_versions' ) );
    }
    
    /**
     * Checking for version, updating if necessary
     * @return void
     */
    public function check_versions()
    {
        
        if ( !defined( 'IFRAME_REQUEST' ) && get_option( 'giveasap_version', '1.4' ) !== $this->version() ) {
            $installer = new GA_Installer();
            $installer->install();
            $installer->update( get_option( 'giveasap_version', '1.4' ) );
            do_action( 'giveasap_updated' );
        }
    
    }
    
    /**
     * Video Wrapper for embedded content
     *
     * @param  string $html
     *
     * @return string
     */
    public function html_video_wrapper( $html )
    {
        $return = '<div class="giveasap-video-container">' . $html . '</div>';
        return $return;
    }
    
    /**
     * Running only public actions
     * @return void
     */
    public function run_public_actions()
    {
        if ( is_admin() ) {
            return;
        }
        add_filter( 'template_include', array( $this, 'giveasap_template' ) );
        add_action( 'init', array( $this, 'check_for_widget' ) );
    }
    
    /**
     * Check if the Widget is Active to Enqueue scripts
     * @return void
     */
    public function check_for_widget()
    {
        
        if ( is_active_widget( false, false, 'giveasap_widget' ) ) {
            add_action( 'wp_head', array( $this, 'giveasap_style' ) );
            wp_enqueue_script(
                'giveasap-jquery-plugin',
                GASAP_URI . 'public/assets/js/jquery.plugin.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'giveasap-jquery-countdown',
                GASAP_URI . 'public/assets/js/jquery.countdown.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                'giveasap-js',
                GASAP_URI . 'public/assets/js/giveasap.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        }
    
    }
    
    public function load_scripts()
    {
        
        if ( !wp_script_is( 'jquery' ) ) {
            wp_deregister_script( 'jquery' );
            wp_register_script(
                'jquery',
                includes_url( '/js/jquery/jquery.js' ),
                false,
                null,
                true
            );
            wp_enqueue_script( 'jquery' );
        }
        
        // Check if we enqueued it already
        if ( !wp_script_is( 'giveasap-jquery-plugin' ) ) {
            wp_enqueue_script(
                'giveasap-jquery-plugin',
                GASAP_URI . 'public/assets/js/jquery.plugin.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        }
        // Check if we enqueued it already
        if ( !wp_script_is( 'giveasap-jquery-countdown' ) ) {
            wp_enqueue_script(
                'giveasap-jquery-countdown',
                GASAP_URI . 'public/assets/js/jquery.countdown.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        }
        // Check if we enqueued it already
        if ( !wp_script_is( 'giveasap-js' ) ) {
            wp_enqueue_script(
                'giveasap-js',
                GASAP_URI . 'public/assets/js/giveasap.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        }
    }
    
    public function giveasap_style()
    {
        ?>
        <style type="text/css">
            .giveasap_countdown .countdown-row {
                display: flex;
                justify-content: center;
            }

            .giveasap_countdown .countdown-section {
                display: inline-block;
                text-align: center;
                padding: 5px;
            }

            .giveasap_countdown .countdown-amount {
                display: block;
                font-weight: 600;
                font-size: 1.5em;
            }
        </style>
		<?php 
    }
    
    /**
     * Registering Widgets
     * @return void
     */
    public function register_widgets()
    {
        register_widget( 'GiveASAP_Widget' );
    }
    
    /**
     * Returning the GiveASAP template
     *
     * @param  string $template Part of a GiveASAP template
     *
     * @return string
     */
    public function giveasap_template( $template )
    {
        
        if ( !is_admin() && is_singular( 'giveasap' ) ) {
            $template_part = 'template';
            $new_template = GASAP_ROOT . '/public/giveasap_' . $template_part . '.php';
            // Filtering the template path to be used in extensions
            $new_template = apply_filters( 'giveasap_template_path', $new_template );
            if ( file_exists( $new_template ) ) {
                return $new_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Enqueuing Scripts on the admin side
     *
     * @param  string $hook_suffix
     *
     * @return void
     */
    public function admin_enqueue_scripts( $hook_suffix )
    {
        $hook_scripts = false;
        if ( $hook_suffix == 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'giveasap' ) {
            $hook_scripts = true;
        }
        if ( $hook_suffix == 'post.php' && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'giveasap' ) {
            $hook_scripts = true;
        }
        
        if ( $hook_scripts ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'gasap-admin-js', GASAP_URI . '/admin/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ) );
            wp_enqueue_style(
                'gasap-jquery',
                GASAP_URI . '/admin/assets/css/jquery-ui.theme.min.css',
                array(),
                time(),
                'all'
            );
            wp_enqueue_style(
                'gasap-admin-css',
                GASAP_URI . '/admin/assets/css/admin.css',
                array(),
                time(),
                'all'
            );
        }
    
    }
    
    /**
     * Running all dependencies
     * @return void
     */
    public function run_dependencies()
    {
        global  $wpdb ;
        include_once GASAP_ROOT . '/includes/abstracts/settings.php';
        include_once GASAP_ROOT . '/includes/abstracts/class-sharer.php';
        include_once GASAP_ROOT . '/includes/giveasap-template-functions.php';
        include_once GASAP_ROOT . '/includes/giveasap-time-functions.php';
        include_once GASAP_ROOT . '/includes/giveasap-functions.php';
        include_once GASAP_ROOT . '/includes/functions-meta.php';
        include_once GASAP_ROOT . '/includes/giveasap-cpt.php';
        include_once GASAP_ROOT . '/includes/class-status.php';
        include_once GASAP_ROOT . '/includes/class-schedule.php';
        include_once GASAP_ROOT . '/includes/class-ga-installer.php';
        include_once GASAP_ROOT . '/includes/class-sharing.php';
        include_once GASAP_ROOT . '/includes/class-sharer-facebook.php';
        include_once GASAP_ROOT . '/includes/class-sharer-twitter.php';
        include_once GASAP_ROOT . '/includes/class-sharer-gplus.php';
        include_once GASAP_ROOT . '/includes/class-sharer-linkedin.php';
        include_once GASAP_ROOT . '/includes/class-sharer-pinterest.php';
        include_once GASAP_ROOT . '/includes/class-sharer-link.php';
        include_once GASAP_ROOT . '/includes/widget.php';
        include_once GASAP_ROOT . '/includes/updates.php';
        include_once GASAP_ROOT . '/includes/shortcode.php';
        
        if ( is_admin() ) {
            $this->run_admin_dependencies();
        } else {
            include_once GASAP_ROOT . '/includes/giveasap-front.php';
        }
        
        global  $giv_fs ;
        // Extending with GiveASAP table
        $wpdb->giveasap_entries = $wpdb->prefix . 'giveasap_entries';
        $wpdb->giveasapmeta = $wpdb->prefix . 'giveasap_meta';
    }
    
    /**
     * Running filters
     * Used to add other filters
     * @return void
     */
    public function run_filters()
    {
        do_action( 'giveasap_filters' );
    }

}
/**
 * Returns the Singleton instance of GiveASAP class
 * @return GiveASAP
 */
function gasap()
{
    return GiveASAP::getInstance();
}

/**
 * Run the GiveASAP Plugin by calling all core methods
 * @return void
 */
function gasap_run()
{
    $giveasap = gasap();
    do_action( 'giveasap_init' );
    $giveasap->run_dependencies();
    $giveasap->run_admin();
    $giveasap->run_actions();
    $giveasap->run_public_actions();
    $giveasap->run_filters();
}

// We will wait until everything is loaded
add_action( 'plugins_loaded', 'gasap_run' );
/**
 * Activation Hook
 * @return void
 */
function giveasap_activate()
{
    include_once GASAP_ROOT . '/includes/giveasap-cpt.php';
    include_once GASAP_ROOT . '/includes/class-ga-installer.php';
    giveasap_cpt();
    // Flushing Rewrite Rules
    flush_rewrite_rules();
    $installer = new GA_Installer();
    $installer->install();
}

register_activation_hook( __FILE__, 'giveasap_activate' );