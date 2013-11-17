<?php
/**
 * Trying to build a BuddyPress Plugin Boilerplate...
 *
 * Inpired by the WordPress Plugin Boilerplate
 * by tommcfarlin <https://github.com/tommcfarlin>
 *
 * @package   BuddyPlug
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       BuddyPlug
 * Plugin URI:        http://buddyplug.uri
 * Description:       BuddyPlug is a BuddyPress plugin template
 * Version:           1.0.0
 * Author:            imath
 * Author URI:        http://imathi.eu
 * Text Domain:       buddyplug
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 * GitHub Plugin URI: https://github.com/imath/buddyplug
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'BuddyPlug' ) ) :
/**
 * Main BuddyPlug Class
 *
 * @since BuddyPlug (1.0.0)
 */
class BuddyPlug {
	/**
	 * Instance of this class.
	 *
	 * @package BuddyPlug
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Some init vars
	 *
	 * @package BuddyPlug
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	public static $init_vars = array(
		'component_id'        => 'buddyplug',
		'component_root_slug' => 'buddyplug',
		'component_name'      => 'BuddyPlug',
		'bp_version_required' => '1.8.1'
	);

	/**
	 * Initialize the plugin
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_hooks();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @package BuddyPlug
	 * @since 1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Sets some globals for the plugin
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 * 
	 * @global $wp_admin_bar object
	 */
	private function setup_globals() {
		/** BuddyPlug globals ********************************************/
		$this->version                = '1.0.0';
		$this->domain                 = 'buddyplug';
		$this->file                   = __FILE__;
		$this->basename               = plugin_basename( $this->file );
		$this->plugin_dir             = plugin_dir_path( $this->file );
		$this->plugin_url             = plugin_dir_url( $this->file );
		$this->lang_dir               = trailingslashit( $this->plugin_dir . 'languages' );
		$this->includes_dir           = trailingslashit( $this->plugin_dir . 'includes' );
		$this->includes_url           = trailingslashit( $this->plugin_url . 'includes' );
		$this->component_includes_dir = trailingslashit( $this->includes_dir . 'component-includes' );
		$this->groups_includes_dir    = trailingslashit( $this->includes_dir . 'groups-includes' );
		$this->plugin_js              = trailingslashit( $this->includes_url . 'js' );
		$this->plugin_css             = trailingslashit( $this->includes_url . 'css' );

		/** Component specific globals ********************************************/
		$this->component_id                     = self::$init_vars['component_id'];
		$this->component_slug                   = self::$init_vars['component_root_slug'];
		$this->component_name                   = self::$init_vars['component_name'];
		$this->component_primary_subnav_slug    = 'buddyplug-primary';
		$this->component_secondary_subnav_slug  = 'buddyplug-secondary';
		$this->component_primary_subnav_name    = 'BuddyPlug Primary';
		$this->component_secondary_subnav_name  = 'BuddyPlug Secondary';
		$this->component_user_settings_nav_name = 'BuddyPlug User Settings';

	}

	/**
	 * Checks BuddyPress version
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	public static function buddypress_version_check() {
		// taking no risk
		if( !defined( 'BP_VERSION' ) )
			return false;

		return version_compare( BP_VERSION, self::$init_vars['bp_version_required'], '>=' );
	}

	/**
	 * Checks if current blog is the one where is activated BuddyPress
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	public static function buddypress_site_check() {
		global $blog_id;

		if( !function_exists( 'bp_get_root_blog_id' ) )
			return false;

		if( $blog_id != bp_get_root_blog_id() )
			return false;
		
		return true;
	}

	/**
	 * Includes the needed files
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	private function includes() {
		require( $this->includes_dir . 'functions.php' );

		if( bp_is_active( 'groups' ) )
			require( $this->includes_dir . 'groups.php' );

		if( is_admin() )
			require( $this->includes_dir . 'admin.php' );
	}

	/**
	 * Sets the key hooks to add an action or a filter to
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	private function setup_hooks() {
		// Bail if BuddyPress version is not supported or current blog is not the one where BuddyPress is activated
		if( ! self::buddypress_version_check() || ! self::buddypress_site_check() )
			return;

		//Actions
		// loads the languages..
		add_action( 'bp_init',            array( $this, 'load_textdomain' ), 6 );
		add_action( 'bp_include',         array( $this, 'load_component'  )    );
		add_action( 'bp_enqueue_scripts', array( $this, 'cssjs'           )    );

		//Filters
		if( bp_is_active( 'groups' ) )
			add_filter( 'groups_forbidden_names', array( $this, 'groups_forbidden_names' ), 10, 1 );
	}

	/**
	 * Loads the component
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	public function load_component() {
		require( $this->includes_dir . 'component.php' );
	}

	/**
	 * Enqueues the js and css files only if BuddyPlug needs it
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 * 
	 * @uses bp_is_active() to check if a BuddyPress component is active
	 * @uses buddyplug_is_component_are() to check if we are in a BuddyPlug component area
	 * @uses buddyplug_is_group_area() to check if we are in a BuddyPlug group area
	 * @uses buddyplug_is_current_component() are we the current component ?
	 * @uses buddyplug_is_directory() are we on a BuddyPlug directory page ?
	 * @uses buddyplug_is_user_settings() are we on the user's setting BuddyPlug page ?
	 * @uses buddyplug_is_group_front() are we on the BuddyPlug group main page ?
	 * @uses buddyplug_is_group_edit() are we on the BuddyPlug group admin page ?
	 * @uses buddyplug_is_group_create() are we on the BuddyPlug group creation step ?
	 * @uses wp_enqueue_style() to safely add our style to WordPress queue
	 * @uses wp_enqueue_script() to safely add our script to WordPress queue
	 * @uses wp_localize_script() to attach some vars to it
	 */
	public function cssjs() {

		if( ( bp_is_active( $this->component_id ) && buddyplug_is_component_area() ) || ( bp_is_active( 'groups' ) && buddyplug_is_group_area() ) ) {

			$localized_script = array();

			if( bp_is_active( $this->component_id ) ) {
				$localized_script = array_merge( $localized_script, array(
						'component'     => buddyplug_is_current_component(),
						'directory'     => buddyplug_is_directory(),
						'user_settings' => buddyplug_is_user_settings()
					)
				);
			}

			if( bp_is_active( 'groups' ) ) {
				$localized_script = array_merge( $localized_script, array(
						'group_front'  => buddyplug_is_group_front(),
						'group_edit'   => buddyplug_is_group_edit(),
						'group_create' => buddyplug_is_group_create()
					)
				);
			}

			// CSS is Theme's territory, so let's help him to easily override our css.
			$css_datas = (array) $this->css_datas();

			wp_enqueue_style( $css_datas['handle'], $css_datas['location'], false, $this->version );
			wp_enqueue_script( 'buddyplug-js', $this->plugin_js . 'script.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'buddyplug-js', 'buddyplug_vars', $localized_script );
		}
		
	}

	/**
	 * The theme can override plugin's css
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	public function css_datas() {
		$file = 'css/buddyplug.css';
		
		// Check child theme
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) ) {
			$location = trailingslashit( get_stylesheet_directory_uri() ) . $file ; 
			$handle   = 'buddyplug-child-css';

		// Check parent theme
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . $file ) ) {
			$location = trailingslashit( get_template_directory_uri() ) . $file ;
			$handle   = 'buddyplug-parent-css';

		// use our style
		} else {
			$location = $this->includes_url . $file;
			$handle   = 'buddyplug-css';
		}

		return array( 'handle' => $handle, 'location' => $location );
	}

	/**
	 * Adds our component name to group forbidden names
	 * 
	 * Let's avoid troubles between BuddyPlug component user nav and group nav
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 */
	public function groups_forbidden_names( $forbidden = array() ) {
		$forbidden = array_merge( $forbidden, array( $this->component_slug, $this->component_name ) );

		return $forbidden;
	}

	/**
	 * Loads the translation files
	 *
	 * @package BuddyPlug
	 * @since 1.0.0
	 * 
	 * @uses get_locale() to get the language of WordPress config
	 * @uses load_texdomain() to load the translation if any is available for the language
	 */
	public function load_textdomain() {
		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale', get_locale(), $this->domain );
		$mofile        = sprintf( '%1$s-%2$s.mo', $this->domain, $locale );

		// Setup paths to current locale file
		$mofile_local  = $this->lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/buddyplug/' . $mofile;

		// Look in global /wp-content/languages/buddyplug folder
		load_textdomain( $this->domain, $mofile_global );

		// Look in local /wp-content/plugins/buddyplug/languages/ folder
		load_textdomain( $this->domain, $mofile_local );
	}

	/**
	 * Creates the WordPress page for the plugin
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 * 
	 * @uses bp_core_get_directory_page_ids() to get the array of directory pages
	 * @uses wp_insert_post() to create the WordPress page
	 * @uses bp_core_update_directory_page_ids() to update the directory pages with our component
	 */
	public static function activation() {
		// Bail if BuddyPress version is not supported or current blog is not the one where BuddyPress is activated
		if( ! self::buddypress_version_check() || ! self::buddypress_site_check() )
			return;

		$directory_pages = function_exists( 'bp_core_get_directory_page_ids' ) ? bp_core_get_directory_page_ids() : false;

		if( empty( $directory_pages ) )
			return;

		$buddyplug_component_id = self::$init_vars['component_id'];

		if( empty( $directory_pages[$buddyplug_component_id] ) ) {

			$buddyplug_component_page_id = wp_insert_post( array( 
													'comment_status' => 'closed', 
													'ping_status'    => 'closed', 
													'post_title'     => self::$init_vars['component_name'],
													'post_content'   => '',
													'post_name'      => self::$init_vars['component_root_slug'],
													'post_status'    => 'publish', 
													'post_type'      => 'page' 
													) );
			
			$directory_pages[$buddyplug_component_id] = $buddyplug_component_page_id;
			bp_core_update_directory_page_ids( $directory_pages );
		}

		do_action( 'buddyplug_activation' );
	}

	/**
	 * Deletes the WordPress page for the plugin
	 * 
	 * @package BuddyPlug
	 * @since 1.0.0
	 * 
	 * @uses bp_core_get_directory_page_ids() to get the array of directory pages
	 * @uses wp_delete_post() to delete the WordPress page
	 * @uses bp_core_update_directory_page_ids() to update the directory pages with our component
	 */
	public static function deactivation() {
		// Bail if BuddyPress version is not supported or current blog is not the one where BuddyPress is activated
		if( ! self::buddypress_version_check() || ! self::buddypress_site_check() )
			return;

		$directory_pages = function_exists( 'bp_core_get_directory_page_ids' ) ? bp_core_get_directory_page_ids() : false;

		if( empty( $directory_pages ) )
			return;

		$buddyplug_component_id = self::$init_vars['component_id'];

		if( !empty( $directory_pages[$buddyplug_component_id] ) ) {
			// let's remove the page as the plugin is deactivated.
			
			$buddyplug_page_id = $directory_pages[$buddyplug_component_id];
			wp_delete_post( $buddyplug_page_id, true );
			
			unset( $directory_pages[$buddyplug_component_id] );
			bp_core_update_directory_page_ids( $directory_pages );
		}


		do_action( 'buddyplug_deactivation' );
	}

	
}

// Let's start !
function buddyplug() {
	return BuddyPlug::get_instance();
}
// Not too early and not too late ! 9 seems ok ;)
add_action( 'bp_include', 'buddyplug', 9 );

// Activation
add_action( 'activate_' . plugin_basename( __FILE__ ) , array( 'BuddyPlug', 'activation' ) );

// Deactivation
add_action( 'deactivate_' . plugin_basename( __FILE__ ) , array( 'BuddyPlug', 'deactivation' ) );

endif;