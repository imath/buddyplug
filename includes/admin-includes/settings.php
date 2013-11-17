<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Main settings section
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 *
 * @return array
 */
function buddyplug_admin_get_settings_sections() {
	return (array) apply_filters( 'buddyplug_admin_get_settings_sections', array(
		'buddyplug_main' => array(
			'title'    => __( 'Main Settings', 'buddyplug' ),
			'callback' => 'buddyplug_admin_setting_callback_main',
			'page'     => 'buddyplug',
		)
	) );
}

/**
 * The different fields for the main settings
 * 
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 *
 * @uses bp_is_active() to check if the settings component is active
 * @return array
 */
function buddyplug_admin_get_settings_fields() {
	$settings_fields = array(

		/** Main Section ******************************************************/

		'buddyplug_main' => array(
			// Primary subnav name
			'buddyplug_primary_subnav_name' => array(
				'title'             => __( 'Name for primary subnav', 'buddyplug' ),
				'callback'          => 'buddyplug_setting_callback_names',
				'sanitize_callback' => 'buddyplug_sanitize_custom_names',
				'args'              => array( 
					'option' => 'buddyplug_primary_subnav_name', 
					'default' => buddyplug()->component_primary_subnav_name
				)
			),
			// Secondary subnav name
			'buddyplug_secondary_subnav_name' => array(
				'title'             => __( 'Name for secondary subnav', 'buddyplug' ),
				'callback'          => 'buddyplug_setting_callback_names',
				'sanitize_callback' => 'buddyplug_sanitize_custom_names',
				'args'              => array( 
					'option' => 'buddyplug_secondary_subnav_name', 
					'default' => buddyplug()->component_secondary_subnav_name
				)
			),
		)
	);

	if( bp_is_active( 'settings' ) ) {
		$settings_fields[ 'buddyplug_main' ]['buddyplug_user_settings_nav_name'] = array(
			'title'             => __( 'Name for User settings nav', 'buddyplug' ),
			'callback'          => 'buddyplug_setting_callback_names',
			'sanitize_callback' => 'buddyplug_sanitize_custom_names',
			'args'              => array( 
				'option' => 'buddyplug_user_settings_nav_name', 
				'default' => buddyplug()->component_user_settings_nav_name
			)
		);
	}

	return (array) apply_filters( 'buddydrive_admin_get_settings_fields', $settings_fields );

}


/**
 * Gives the setting fields for section
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 * 
 * @param  string $section_id 
 * @uses  buddyplug_admin_get_settings_fields() to get the setting fields
 * @return array  the fields
 */
function buddyplug_admin_get_settings_fields_for_section( $section_id = '' ) {

	// Bail if section is empty
	if ( empty( $section_id ) )
		return false;

	$fields = buddyplug_admin_get_settings_fields();
	$retval = isset( $fields[$section_id] ) ? $fields[$section_id] : false;

	return (array) apply_filters( 'buddyplug_admin_get_settings_fields_for_section', $retval, $section_id );
}

/**
 * No text for the settings section
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 * 
 */
function buddyplug_admin_setting_callback_main() {}


/**
 * Let the admin customize the name of the main user's subnav
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 *
 * @param $args array
 * @uses bp_get_option() to get the user's subnav
 * @uses sanitize_title() to sanitize user's subnav name
 * @return string html
 */
function buddyplug_setting_callback_names( $args = array() ) {
	$name = bp_get_option( $args['option'], $args['default'] );
	$name = sanitize_text_field( $name );
	?>

	<input name="<?php echo esc_attr( $args['option'] );?>" type="text" id="<?php echo esc_attr( $args['option'] );?>" value="<?php echo $name;?>" class="regular-text code" />

	<?php
}

/**
 * Sanitizes the names
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 *
 * @param string $option 
 * @uses sanitize_text_field() to sanitize the name
 * @return string the slug
 */
function buddyplug_sanitize_custom_names( $option ) {
	$option = sanitize_text_field( $option );
	
	return $option;
}

/**
 * Displays the settings page
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 * 
 * @uses bp_core_do_network_admin() to check if BuddyPress is activated on the network
 * @uses add_query_arg() to add arguments to query in case of multisite
 * @uses bp_get_admin_url to build the settings url in case of multisite
 * @uses screen_icon() to show options icon
 * @uses settings_fields()
 * @uses do_settings_sections()
 * @uses wp_nonce_field() for security reason in case of multisite
 */
function buddyplug_admin_settings() {
	$form_action = 'options.php';
	
	if( bp_core_do_network_admin() ) {
		do_action( 'buddyplug_network_options' );
		
		$form_action = add_query_arg( 'page', 'buddyplug', bp_get_admin_url( 'settings.php' ) );
	}
?>

	<div class="wrap">

		<?php screen_icon('options-general'); ?>

		<h2><?php _e( 'BuddyPlug Settings', 'buddyplug' ) ?></h2>

		<form action="<?php echo $form_action;?>" method="post" id="buddyplug-form-settings">

			<?php settings_fields( 'buddyplug' ); ?>

			<?php do_settings_sections( 'buddyplug' ); ?>

			<p class="submit">
				<?php if( bp_core_do_network_admin() ) :?>
					<?php wp_nonce_field( 'buddyplug_settings', '_wpnonce_buddyplug_setting' ); ?>
				<?php endif;?>
				<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', 'buddyplug' ); ?>" />
			</p>
		</form>
	</div>

<?php
}


/**
 * Save settings in case of a multisite config
 *
 * @package BuddyPlug
 * @subpackage Admin
 * @since 1.0.0
 *
 * @uses check_admin_referer() to check the nonce
 * @uses buddyplug_sanitize_custom_names() to sanitize names
 * @uses bp_update_option() to save the options in root blog
 */
function buddyplug_handle_network_settings() {
	
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	check_admin_referer( 'buddyplug_settings', '_wpnonce_buddyplug_setting' );

	foreach( $_POST as $key => $val ) {
		if( strpos( $key, 'buddyplug' ) === 0 ) {
			$name = buddyplug_sanitize_custom_names( $val );

			if( ! empty( $name ) )
				bp_update_option( $key, $name );

		}
			
	}
	?>
	<div id="message" class="updated"><p><?php _e( 'Settings saved', 'buddyplug' );?></p></div>
	<?php
	
}

add_action( 'buddyplug_network_options', 'buddyplug_handle_network_settings', 0 );
