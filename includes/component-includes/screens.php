<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* screen functions */

/**
 * Adds an action to the template hooks and loads the default member's template
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      bp_core_load_template() to ask BuddyPress to load the template
 */
function buddyplug_screen_main_nav() {
	add_action( 'bp_template_title',   'buddyplug_main_page_title' );
    add_action( 'bp_template_content', 'buddyplug_main_page_content' );
	
	bp_core_load_template( apply_filters( 'buddyplug_screen_main_nav', 'members/single/plugins' ) );
}

/**
 * Adds an action to the template hooks and loads the default member's template
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      bp_core_load_template() to ask BuddyPress to load the template
 */
function buddyplug_screen_secondary_subnav() {
	add_action( 'bp_template_title',   'buddyplug_secondary_page_title' );
    add_action( 'bp_template_content', 'buddyplug_secondary_page_content' );
	
	bp_core_load_template( apply_filters( 'buddyplug_screen_secondary_subnav', 'members/single/plugins' ) );
}

/**
 * Adds an action to the template hooks and loads the default member's template
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      bp_core_load_template() to ask BuddyPress to load the template
 */
function buddyplug_screen_user_settings() {
	add_action( 'bp_template_title', 'buddyplug_screen_user_settings_title' );
	add_action( 'bp_template_content', 'buddyplug_screen_user_settings_content' );

	bp_core_load_template( apply_filters( 'buddyplug_screen_user_settings', 'members/single/plugins' ) );
}

/**
 * Displays the title of main nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_primary_subnav_name() as an example
 */
function buddyplug_main_page_title() {
	buddyplug_component_primary_subnav_name();
}

/**
 * Displays the title of secondary nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_secondary_subnav_name() as an example
 */
function buddyplug_secondary_page_title() {
	buddyplug_component_secondary_subnav_name();
}

/**
 * Displays the content of main nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_primary_subnav_name() as an example
 */
function buddyplug_main_page_content() {
	buddyplug_component_primary_subnav_name();
	?>
	<div id="buddyplug-content"></div>
	<!-- this is the div our script.js will populate -->
	<?php
}

/**
 * Displays the content of secondary nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_secondary_subnav_name() as an example
 */
function buddyplug_secondary_page_content() {
	buddyplug_component_secondary_subnav_name();
	?>
	<div id="buddyplug-content"></div>
	<!-- this is the div our script.js will populate -->
	<?php
}

/**
 * Displays the title of user settings BuddyPlug nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_user_settings_nav_name() as an example
 */
function buddyplug_screen_user_settings_title() {
	buddyplug_component_user_settings_nav_name();
}

/**
 * Displays the content of user settings BuddyPlug nav
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses      buddyplug_component_user_settings_nav_name() as an example
 */
function buddyplug_screen_user_settings_content() {
	buddyplug_component_user_settings_nav_name();
	?>
	<div id="buddyplug-content"></div>
	<!-- this is the div our script.js will populate -->
	<?php
}

/**
 * Adds plugin's template dir to BuddyPress stack of templates location
 * by filtering bp_get_template_stack
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses bp_is_current_component() to check the current component is BuddyPlug
 * @uses buddyplug_get_component_id() to get BuddyPlug component id
 * @uses buddyplug_get_includes_dir() to get the plugin includes dir
 */
function buddyplug_get_template_stack( $templates ) {
	
	if ( bp_is_current_component( buddyplug_get_component_id() ) ) {
		
		$templates[] = trailingslashit( buddyplug_get_includes_dir() . 'templates' );
		
	}
	
	return $templates;
}

add_filter( 'bp_get_template_stack', 'buddyplug_get_template_stack', 10, 1 );

/**
 * Loads the right template for the plugin's directory
 * by adding an action to bp_screens
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 * 
 * @uses bp_displayed_user_id() to check if we're not a member's page
 * @uses bp_is_current_component() to check the current component is BuddyPlug
 * @uses buddyplug_get_component_id() to get BuddyPlug component's id
 * @uses bp_update_is_directory() to define we're on BuddyPlug directory
 * @uses bp_core_load_template() to load the template for the directory
 */
function buddyplug_screen_index() {
	
	if ( !bp_displayed_user_id() && bp_is_current_component( buddyplug_get_component_id() ) ) {
		bp_update_is_directory( true, buddyplug_get_component_id() );

		do_action( 'buddyplug_screen_index' );

		bp_core_load_template( apply_filters( 'buddyplug_screen_index', 'buddyplug-dir' ) );
	}
}

add_action( 'bp_screens', 'buddyplug_screen_index' );

/**
 * Theme compatibility class for the component
 *
 * @package BuddyPlug
 * @subpackage BuddyPlug_Component
 * @since 1.0.0
 */
class BuddyPlug_Theme_Compat {

	/**
	 * The constructor
	 *
	 * @package BuddyPlug
	 * @subpackage BuddyPlug_Component
	 * @since 1.0.0
	 */
	public function __construct() {
		
		add_action( 'bp_setup_theme_compat', array( $this, 'is_buddyplug' ) );
	}

	/**
	 * Are we looking at something that needs BuddyPlug theme compatability?
	 *
	 * @package BuddyPlug
	 * @subpackage BuddyPlug_Component
	 * @since 1.0.0
	 * 
	 * @uses bp_is_current_component() to check the current component is BuddyPlug
	 * @uses buddyplug_get_component_id() to get BuddyPlug component's id
	 */
	public function is_buddyplug() {
		
		if ( !bp_displayed_user_id() && bp_is_current_component( buddyplug_get_component_id() ) ) {

			add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'directory_dummy_post' ) );
			add_filter( 'bp_replace_the_content',                    array( $this, 'directory_content'    ) );

		}
		
	}

	/** Directory *************************************************************/

	/**
	 * Update the global $post with directory data
	 *
	 * @package BuddyPlug
	 * @subpackage BuddyPlug_Component
	 * @since 1.0.0
	 *
	 * @uses bp_theme_compat_reset_post() to reset the post data
	 * @uses buddyplug_get_component_name() to set the title of the page to plugin's component name
	 * @uses buddyplug_get_component_id() to set the post type
	 */
	public function directory_dummy_post() {

		bp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => buddyplug_get_component_name(),
			'post_author'    => 0,
			'post_date'      => 0,
			'post_content'   => '',
			'post_type'      => buddyplug_get_component_id(),
			'post_status'    => 'publish',
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );
	}

	/**
	 * Filter the_content with the BuddyPlug dir template part
	 *
	 * @package BuddyPlug
	 * @subpackage BuddyPlug_Component
	 * @since 1.0.0
	 *
	 * @uses bp_buffer_template_part()
	 */
	public function directory_content() {		
		bp_buffer_template_part( apply_filters( 'buddyplug_screen_index', 'buddyplug-dir' ) );
	}
	
}

new BuddyPlug_Theme_Compat();