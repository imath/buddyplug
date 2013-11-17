<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'BP_Group_Extension' ) ) :
/**
 * The BuddyPlug group class
 *
 * @package BuddyPlug
 * @since 1.0.0
 * @see http://codex.buddypress.org/developer/group-extension-api/
 * 
 */
class BuddyPlug_Group extends BP_Group_Extension {	
	

	/**
	 * construct method to add some settings and hooks
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 *
	 * @uses buddyplug_get_component_slug() to get the plugin name
	 * @uses buddyplug_get_component_name() to get the plugin slug
	 */
	public function __construct() {

		$this->includes();

		$args = array(
        	'slug'              => buddyplug_get_component_slug(),
       		'name'              => buddyplug_get_component_name(),
       		'visibility'        => 'private',
       		'nav_item_position' => 31,
       		'enable_nav_item'   => $this->enable_nav_item(),
       		'screens'           => array( 
       								'create' => array(
       									'enabled' => true,
       								),
       								'admin' => array( 
       											'metabox_context'  => 'side',
       											'metabox_priority' => 'core'
       								),
       								'edit' => array(
       									'enabled' => true,
       								)
       							)
    	);
    
    	parent::init( $args );
		
	}

	/**
	 * Includes the needed files
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 *
	 * @uses buddyplug_get_groups_includes_dir() to get the groups includes dir
	 */
	public function includes() {
		require_once( buddyplug_get_groups_includes_dir() . 'functions.php' );
	}

	/**
	 * Create step for a group
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @param integer $item_id the group id
	 */
	public function create_screen( $group_id = null ) {
		$this->edit_screen( $group_id );
	}

	/**
	 * Handling the create step for a group
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @param integer $item_id the group id
	 */
	public function create_screen_save( $group_id = null ) {
		$this->edit_screen_save( $group_id );
	}

	/**
	 * Displays settings in front/backend group admin / create step
	 *
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 *
	 * @param integer $item_id the group id
	 * @uses bp_get_current_group_id() to get the group id
	 * @uses groups_get_groupmeta() to get the BuddyPlug option
	 * @uses checked() to activate/deactivate the checkbox
	 * @uses is_admin() to check if we're in WP backend
	 * @uses bp_is_group_create() to check if we're on the create step of the group
	 * @uses wp_nonce_field() for security reason
	 * @return string html output
	 */
	public function edit_screen( $group_id = null ) {
		$group_id   = empty( $group_id ) ? bp_get_current_group_id() : $group_id;
		$checked = groups_get_groupmeta( $group_id, '_buddyplug_enabled' );
		?>

		<h4><?php echo esc_attr( $this->name ) ?> <?php _e( 'settings', 'buddyplug' );?></h4>
		
		<fieldset>
			<legend class="screen-reader-text"><?php echo esc_attr( $this->name ) ?> <?php _e( 'settings', 'buddyplug' );?></legend>

			<div class="field-group">
				<div class="checkbox">
					<label><input type="checkbox" name="_group_buddyplug_activate" value="1" <?php checked( $checked )?>> <?php printf( __( 'Activate %s', 'buddyplug' ), $this->name );?></label>
				</div>
			</div>
			<div id="buddyplug-content"></div>
			<!-- this is the div our script.js will populate -->
		
			<?php if ( !is_admin() && !bp_is_group_create() ) : ?>
				<input type="submit" name="save" value="<?php _e( 'Save', 'buddyplug' );?>" />
			<?php endif; ?>

		</fieldset>

		<?php
		wp_nonce_field( 'groups_edit_save_' . $this->slug, 'buddyplug_group_admin' );
	}


	/**
	 * Save the settings of the group
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @param integer $item_id the group id
	 * @uses check_admin_referer() for security reasons
	 * @uses bp_get_current_group_id() to get the group id
	 * @uses groups_update_groupmeta() to set the BuddyPlug option if needed
	 * @uses groups_delete_groupmeta() to delete the BuddyPlug option if needed
	 * @uses is_admin() to check if we're in WP backend
	 * @uses bp_is_group_create() to check if we're on the create step of the group
	 * @uses bp_core_add_message() to inform about success / error
	 * @uses bp_core_redirect() to avoid some refreshing stuff
	 * @uses bp_get_group_permalink() to redirect to
	 */
	public function edit_screen_save( $group_id = null ) {

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
			return false;

		check_admin_referer( 'groups_edit_save_' . $this->slug, 'buddyplug_group_admin' );
		
		$group_id   = !empty( $group_id ) ? $group_id : bp_get_current_group_id();

		/* Insert your edit screen save code here */
		$buddyplug_ok = !empty( $_POST['_group_buddyplug_activate'] ) ? $_POST['_group_buddyplug_activate'] : false ;
		
		if( !empty( $buddyplug_ok ) ){
			$success = groups_update_groupmeta( $group_id, '_buddyplug_enabled', $buddyplug_ok );
		} else { 
			$success = groups_delete_groupmeta( $group_id, '_buddyplug_enabled' );
		}
		
		if ( !is_admin() && !bp_is_group_create() ) {
			/* To post an error/success message to the screen, use the following */
			if ( !$success )
				bp_core_add_message( __( 'There was an error saving, please try again', 'buddyplug' ), 'error' );
			else
				bp_core_add_message( __( 'Settings saved successfully', 'buddyplug' ) );

			bp_core_redirect( bp_get_group_permalink( buddypress()->groups->current_group ) . 'admin/' . $this->slug );
		}
		
	}

	/**
	 * Displays the form into the Group Admin Meta Box
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @param  integer $item_id group id
	 */
	public function admin_screen( $group_id = null ) {
		$this->edit_screen( $group_id );
	}

	/**
	 * Saves the settings from the Group Admin Meta Box
	 *
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @param integer $item_id the group id
	 */
	public function admin_screen_save( $group_id = null ) {
		$this->edit_screen_save( $group_id );
	}

	/**
	 * Displays the BuddyPlug content of the group
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 *
	 * @return string html output
	 */
	public function display() {
		?>
		
		<h3><?php _e( 'BuddyPlug Group', 'buddyplug' ); ?></h3>	
		<div id="buddyplug-content"></div>
		<!-- this is the div our script.js will populate -->
		
		<?php
	}


	/**
	 * We do not use widgets
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 * 
	 * @return boolean false
	 */
	function widget_display() {
		return false;
	}
	

	/**
	 * Loads the BuddyPlug navigation if group admin activated it
	 * 
	 * @package BuddyPlug
	 * @subpackage Group
	 * @since 1.0.0
	 *
	 * @uses bp_get_current_group_id() to get the group id
	 * @uses groups_get_groupmeta() to get the BuddyPlug option
	 * @return boolean true or false
	 */
	function enable_nav_item() {
		
		$group_id = bp_get_current_group_id();
		
		if( empty( $group_id ) )
			return false;
		
		if ( groups_get_groupmeta( $group_id, '_buddyplug_enabled' ) )
			return true;
		else
			return false;
	}
}

/**
 * Waits for bp_init hook before loading the group extension
 *
 * Let's make sure the group id is defined before loading our stuff
 * 
 * @package BuddyPlug
 * @subpackage Group
 * @since 1.0.0
 * 
 * @uses bp_register_group_extension() to register the group extension
 */
function buddyplug_register_group_extension() {
	bp_register_group_extension( 'BuddyPlug_Group' );
}

add_action( 'bp_init', 'buddyplug_register_group_extension' );

endif;