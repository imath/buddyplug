<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'BuddyPlug_Admin' ) ) :
/**
 * Loads BuddyPlug plugin admin area
 * 
 * @package BuddyPlug
 * @subpackage Admin
 * @since version 1.0.0
 */
class BuddyPlug_Admin {
	
	/**
	 * @var the BuddyPlug settings page for admin or network admin
	 */
	public $settings_page ='';
	
	/**
	 * @var the notice hook depending on config (multisite or not)
	 */
	public $notice_hook = '';

	/**
	 * @var the BuddyPlug hook_suffixes to eventually load script
	 */
	public $hook_suffixes = array();

	/**
	 * The constructor
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
	}

	/**
	 * Admin globals
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 *
	 * @uses bp_core_do_network_admin() to define the best menu (network or not)
	 */
	private function setup_globals() {
		$this->settings_page       = bp_core_do_network_admin() ? 'settings.php' : 'options-general.php';
		$this->notice_hook         = bp_core_do_network_admin() ? 'network_admin_notices' : 'admin_notices' ;
		$this->includes_dir		   = trailingslashit( buddyplug_get_includes_dir() . 'admin-includes' );
	}

	/**
	 * Includes the needed files
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 */
	public function includes() {
		require( $this->includes_dir . 'settings.php' );
	}

	/**
	 * Setup the admin hooks, actions and filters
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 * 
	 * @uses bp_core_admin_hook() to hook the right menu (network or not)
	 * @uses buddyplug() to get plugin's main instance
	 */
	private function setup_actions() {
		$buddyplug = buddyplug();

		// Current blog is not the one where BuddyPress is activated so let's warn the administrator
		if( ! $buddyplug::buddypress_site_check() ) {
			add_action( 'admin_notices',              array( $this, 'warning_notice' )        );
		} else {
			add_action( $this->notice_hook,           array( $this, 'activation_notice' )        );
			add_action( bp_core_admin_hook(),         array( $this, 'admin_menus'       )        );
			add_action( 'bp_admin_enqueue_scripts',   array( $this, 'enqueue_scripts'   ), 10, 1 );
			add_action( 'bp_register_admin_settings', array( $this, 'register_settings' )        );
		}
		
	}

	/**
	 * Prints a warning notice if the Community administrator activated the plugin on the wrong site
	 *
	 * Since it's possible to activate BuddyPress on any site of the network by defining BP_ROOT_BLOG
	 * with the blog_id, we need to make sure BuddyPlug is activated on the same site than BuddyPress
	 * if it's not the case, this notice will be displayed to ask the administrator to activate the 
	 * plugin on the correct blog, or on the network if it's where BuddyPress is activated.
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 * 
	 * @uses is_plugin_active_for_network() to check if the plugin is activated on the network
	 * @uses buddyplug() to get plugin's main instance
	 * @uses bp_core_do_network_admin() to check if BuddyPress has been activated on the network
	 */
	public function warning_notice() {
		if( is_plugin_active_for_network( buddyplug()->basename ) )
			return;
		?>
		<div id="message" class="updated fade">
			<?php if( bp_core_do_network_admin() ) :?>
				<p><?php _e( 'BuddyPress is activated on the network, please deactivate BuddyPlug from this site and make sure to activate BuddyPlug on the network.', 'buddyplug' );?></p>
			<?php else:?>
				<p><?php _e( 'BuddyPlug has been activated on a site where BuddyPress is not, please deactivate BuddyPlug from this site and activate it on the same site where BuddyPress is activated.', 'buddyplug' );?></p>
			<?php endif;?>
		</div>
		<?php
	}

	/**
	 * Displays a warning if BuddyPress version is outdated for the plugin
	 * 
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 *
	 * @uses  buddyplug() to get plugin's main instance
	 */
	public function activation_notice() {
		$buddyplug = buddyplug();

		if( ! $buddyplug::buddypress_version_check() ) {
			?>
			<div id="message" class="updated fade">
				<p><?php printf( __( 'BuddyPlug requires at least <strong>BuddyPress %s</strong>, please upgrade', 'buddyplug' ), $buddyplug::$init_vars['bp_version_required'] );?></p>
			</div>
			<?php
		}

	}
	
	/**
	 * Builds BuddyPlug admin menus
	 * 
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 *
	 * @uses  buddyplug() to get plugin's main instance
	 * @uses  bp_current_user_can() to check for user's capability
	 * @uses  add_submenu_page() to add the settings page
	 * @uses  buddyplug_get_plugin_version() to get plugin's version
	 * @uses  bp_get_option() to get plugin's db version
	 * @uses  bp_update_option() to update plugin's db version
	 */
	public function admin_menus() {
		$buddyplug = buddyplug();

		// Bail if user cannot manage options
		if ( ! bp_current_user_can( 'manage_options' ) )
			return;

		$this->hook_suffixes[] = add_submenu_page(
			$this->settings_page,
			__( 'BuddyPlug Options',  'buddyplug' ),
			__( 'BuddyPlug Options',  'buddyplug' ),
			'manage_options',
			'buddyplug',
			'buddyplug_admin_settings'
		);

		if( $buddyplug::buddypress_version_check() && buddyplug_get_plugin_version() != bp_get_option( 'buddyplug_db_version' ) )
			bp_update_option( 'buddyplug_db_version', buddyplug_get_plugin_version() );
	}

	/**
	 * Eqnueues scripts and styles if needed
	 * 
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 * 
	 * @param  string $hook the WordPress admin page
	 * @uses wp_enqueue_script() to enqueue the script
	 * @uses buddyplug_get_js_url() to get the js url for the plugin
	 * @uses buddyplug_get_plugin_version() to get plugin's version
	 */
	public function enqueue_scripts( $hook = '' ) {
		/* 
		Taking care of BuddyPress < 1.9-beta1 
		Many thanks to @boone for https://buddypress.trac.wordpress.org/changeset/7587 (adding $hook_suffix in bp_admin_enqueue_scripts)
		*/
		if( empty( $hook ) )
			$hook = bp_core_do_network_admin() ? str_replace( '-network', '', get_current_screen()->id ) : get_current_screen()->id;

		if( in_array( $hook, $this->hook_suffixes ) ) {
			wp_enqueue_script( 'buddyplug-admin-js', buddyplug_get_js_url() .'admin.js', array( 'jquery' ), buddyplug_get_plugin_version(), 1 );
			wp_localize_script( 'buddyplug-admin-js', 'buddyplug_admin', array( 'message' => __( 'Please fill the empty field(s):', 'buddyplug' ) ) );
		}
			
	}

	/**
	 * Builds the settings fields for the plugin
	 *
	 * @package BuddyPlug
	 * @subpackage Admin
	 * @since 1.0.0
	 * 
	 * @uses buddyplug_admin_get_settings_sections() to get plugin's settings sections
	 * @uses bp_current_user_can() to check for user's capacity
	 * @uses buddyplug_admin_get_settings_fields_for_section() to get plugin's fields for the section
	 * @uses add_settings_section() to add the settings section
	 * @uses add_settings_field() to add the fields
	 * @uses register_setting() to fianlly register the settings
	 */
	public function register_settings() {
		$sections = buddyplug_admin_get_settings_sections();

		// Bail if no sections available
		if ( empty( $sections ) )
			return false;

		// Loop through sections
		foreach ( (array) $sections as $section_id => $section ) {

			// Only proceed if current user can see this section
			if ( ! bp_current_user_can( 'manage_options' ) )
				continue;

			// Only add section and fields if section has fields
			$fields = buddyplug_admin_get_settings_fields_for_section( $section_id );
			if ( empty( $fields ) )
				continue;

			// Add the section
			add_settings_section( $section_id, $section['title'], $section['callback'], $section['page'] );

			// Loop through fields for this section
			foreach ( (array) $fields as $field_id => $field ) {

				// Add the field
				add_settings_field( $field_id, $field['title'], $field['callback'], $section['page'], $section_id, $field['args'] );

				// Register the setting
				register_setting( $section['page'], $field_id, $field['sanitize_callback'] );
			}
		}
	}
	
}

/**
 * Launches the admin
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug()
 */
function buddyplug_admin() {
	buddyplug()->admin = new BuddyPlug_Admin();
}

add_action( 'bp_loaded', 'buddyplug_admin' );

endif;