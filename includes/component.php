<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'BuddyPlug_Component' ) ) :
/**
 * Main BuddyPlug Component Class
 *
 * @see http://codex.buddypress.org/developer/bp_component/
 */
class BuddyPlug_Component extends BP_Component {

	/**
	 * Constructor method
	 *
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 *
	 * @uses buddyplug_get_component_id() to get the id of the component
	 * @uses buddyplug_get_component_name() to get the name of the component
	 * @uses buddyplug_get_component_includes_dir() to get component's include dir
	 * @uses buddypress() to get BuddyPress main instance
	 * @uses bp_is_active() to check if settings component is active
	 */
	function __construct() {

		parent::start(
			buddyplug_get_component_id(),
			buddyplug_get_component_name(),
			buddyplug_get_component_includes_dir()
		);

	 	$this->includes();
		
		buddypress()->active_components[$this->id] = '1';

		if( bp_is_active( 'settings' ) ) {
			add_action( 'bp_settings_setup_nav',       array( $this, 'setup_settings_bp_nav' ) );
			add_action( 'bp_settings_setup_admin_bar', array( $this, 'setup_settings_wp_nav' ) );
		}

		/**
		 * You could also register a custom post type from here...
		 * 
		 * eg: add_action( 'init', array( &$this, 'register_post_types' ), 9 );
		 */
	}

	/**
	 * Sets some global for the component
	 *
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 *
	 * @param $args array
	 * @uses buddyplug_get_component_slug() to get the slug of the component
	 * @uses buddypress() to get BuddyPress main instance
	 */
	public function setup_globals( $args = array() ) {
		$bp = buddypress();
		
		$args = array(
			'slug'                  => buddyplug_get_component_slug(),
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : buddyplug_get_component_slug(),
			'has_directory'         => true,
			'search_string'         => __( 'Search BuddyPlug...', 'buddyplug' ),
			'notification_callback' => 'buddyplug_format_notifications',
		);

		parent::setup_globals( $args );
	}

	/**
	 * Includes the needed files
	 *
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 *
	 * @param $args array
	 * @uses buddyplug_get_component_slug() to get the slug of the component
	 * @uses buddypress() to get BuddyPress main instance
	 */
	public function includes( $includes = array() ) {

		// Files to include
		$includes = array(
			'functions.php',
			'screens.php',
		);

		parent::includes( $includes );

	}

	/**
	 * Builds the BuddyPress user nav
	 * 
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 * 
	 * @param $main_nav array
	 * @param $sub_nav array
	 * @uses  buddyplug_get_component_primary_subnav_slug() to get the slug for subnav
	 * @uses  bp_displayed_user_domain() to get the displayed user profile url
	 * @uses  bp_loggedin_user_domain() to get the loggedin user profil url
	 * @uses  buddyplug_get_component_primary_subnav_name() to get the primary nav name
	 * @uses  buddyplug_get_component_primary_subnav_slug() to get the primary nav slug
	 * @uses  buddyplug_get_component_secondary_subnav_name() to get the primary nav name
	 * @uses  buddyplug_get_component_secondary_subnav_slug() to get the secondary nav slug
	 */
	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {
		
		$main_nav = array(
			'name'                => $this->name,
			'slug'                => $this->slug,
			'position'            => 90,
			'screen_function'     => 'buddyplug_screen_main_nav',
			'default_subnav_slug' => buddyplug_get_component_primary_subnav_slug(),
			'item_css_id'         => $this->id
		);

		// Determine user to use
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			return;
		}

		$buddyplug_link = trailingslashit( $user_domain . $this->slug );

		// Add the My Widgets and My Home nav item
		$sub_nav[] = array(
			'name'            => buddyplug_get_component_primary_subnav_name(),
			'slug'            => buddyplug_get_component_primary_subnav_slug(),
			'parent_url'      => $buddyplug_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'buddyplug_screen_main_nav',
			'position'        => 10,
			'item_css_id'     => buddyplug_get_component_primary_subnav_slug()
		);
		$sub_nav[] = array(
			'name'            => buddyplug_get_component_secondary_subnav_name(),
			'slug'            => buddyplug_get_component_secondary_subnav_slug(),
			'parent_url'      => $buddyplug_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'buddyplug_screen_secondary_subnav',
			'position'        => 20,
			'item_css_id'     => buddyplug_get_component_secondary_subnav_slug()
		);
		
		parent::setup_nav( $main_nav, $sub_nav );
		
	}
	
	/**
	 * Builds the BuddyPress loggedin user nav in WP Admin Bar
	 * 
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 * 
	 * @param $wp_admin_nav array
	 * @uses  bp_loggedin_user_domain() to get the loggedin user profil url
	 * @uses  buddyplug_get_component_primary_subnav_name() to get the primary nav name
	 * @uses  buddyplug_get_component_primary_subnav_slug() to get the primary nav slug
	 * @uses  buddyplug_get_component_secondary_subnav_name() to get the primary nav name
	 * @uses  buddyplug_get_component_secondary_subnav_slug() to get the secondary nav slug
	 */
	public function setup_admin_bar( $wp_admin_nav = array() ) {

		// Prevent debug notices
		$wp_admin_nav = array();

		// Menus for logged in user
		if ( is_user_logged_in() ) {

			// Setup the logged in user variables
			$buddyplug_link = trailingslashit( bp_loggedin_user_domain() . $this->slug );

			// Add main BuddyPlug menu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-buddypress',
				'id'     => 'my-account-' . $this->slug,
				'title'  => $this->name,
				'href'   => trailingslashit( $buddyplug_link )
			);
			
			// Add BuddyPlug primary submenu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-' . $this->slug,
				'id'     => 'my-account-' . $this->slug . '-' . buddyplug_get_component_primary_subnav_slug(),
				'title'  => buddyplug_get_component_primary_subnav_name(),
				'href'   => trailingslashit( $buddyplug_link )
			);
			
			
			// Add BuddyPlug secondary submenu
			$wp_admin_nav[] = array(
				'parent' => 'my-account-' . $this->slug,
				'id'     => 'my-account-' . $this->slug . '-' . buddyplug_get_component_secondary_subnav_slug(),
				'title'  => buddyplug_get_component_secondary_subnav_name(),
				'href'   => trailingslashit( $buddyplug_link . buddyplug_get_component_secondary_subnav_slug() )
			);

		}

		parent::setup_admin_bar( $wp_admin_nav );
	}

	/**
	 * Builds a new BuddyPress subnav for the settings component
	 * 
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 * 
	 * @uses  bp_core_new_subnav_item() to build a new subnav item
	 * @uses  buddyplug_get_component_user_settings_nav_name() to get the BuddyPlug settings nav name
	 * @uses  bp_get_settings_slug() to get the settings slug
	 * @uses  bp_is_my_profile() to be sure it shows to loggedin user only
	 */
	public function setup_settings_bp_nav() {
		bp_core_new_subnav_item( array(
			'name' 		      => buddyplug_get_component_user_settings_nav_name(),
			'slug' 		      => $this->slug,
			'parent_slug'     => bp_get_settings_slug(),
			'parent_url' 	  => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ),
			'screen_function' => 'buddyplug_screen_user_settings',
			'position' 	      => 40,
			'user_has_access' => bp_is_my_profile() // Only the logged in user can access this on his/her profile
		) );
	}

	/**
	 * Builds a new user sub menu for the settings component in WP Admin Bar
	 * 
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 * 
	 * @global $wp_admin_bar object
	 * @uses  buddyplug_get_component_user_settings_nav_name() to get the BuddyPlug settings nav name
	 * @uses  bp_loggedin_user_domain() to get the loggedin user profil url
	 * @uses  bp_get_settings_slug() to get the settings slug
	 */
	public function setup_settings_wp_nav() {
		global $wp_admin_bar;

		$settings_menu = array(
			'parent' => 'my-account-settings',
			'id'     => 'my-account-settings-' . $this->slug,
			'title'  => buddyplug_get_component_user_settings_nav_name(),
			'href'   => trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() . '/' . $this->slug ),
		);
			
		$wp_admin_bar->add_menu( $settings_menu );
	}

	/**
	 * Creates a custom post type for the component
	 * 
	 * @package BuddyPlug
	 * @subpackage Component
	 * @since 1.0.0
	 * 
	 * @uses  register_post_type() to register the post type
	 */
	public function register_post_types() {

		// Set up some labels for the post type
		$labels= array(
			'name'	             => '',
			'singular'           => '',
			'menu_name'          => '',
			'all_items'          => '',
			'singular_name'      => '',
			'add_new'            => '',
			'add_new_item'       => '',
			'edit_item'          => '',
			'new_item'           => '',
			'view_item'          => '',
			'search_items'       => '',
			'not_found'          => '',
			'not_found_in_trash' => ''
		);
		
		$args = array(
			'label'	            => '',
			'labels'            => $labels,
			'public'            => false,
			'rewrite'           => false,
			'show_ui'           => false,
			'show_in_admin_bar' => false,
			'supports'          => array( 'title', 'editor', 'author' )
		);

		// Uncoment to register the post type for files.
		//register_post_type( 'your_post_type', $args );

		//parent::register_post_types();
	}

}

/**
 * Finally Loads the component into the main BuddyPress instance
 *
 * @uses buddypress()
 */
function buddyplug_component() {
	buddypress()->{buddyplug_get_component_id()} = new BuddyPlug_Component;
}
add_action( 'bp_loaded', 'buddyplug_component', 11 );

endif;