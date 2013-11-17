<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* conditional functions */

/**
 * Is BuddyPlug the current BuddyPress component ?
 * 
 * @package BuddyPlug
 * @subpackage Component
 * @since 1.0.0
 *
 * @uses bp_is_current_component() to get current component
 * @uses buddyplug_get_component_id() to get BuddyPlug component id
 */
function buddyplug_is_current_component() {
	return bp_is_current_component( buddyplug_get_component_id() );
}

/**
 * Are we on BuddyPlug directory page ?
 * 
 * @package BuddyPlug
 * @subpackage Component
 * @since 1.0.0
 *
 * @uses buddyplug_is_current_component() Is BuddyPlug the current BuddyPress component ?
 * @uses bp_is_directory() is it a BuddyPress directory page ?
 */
function buddyplug_is_directory() {
	if( buddyplug_is_current_component() && bp_is_directory() )
		return true;

	return false;
}

/**
 * Are we on BuddyPlug user settings page ?
 * 
 * @package BuddyPlug
 * @subpackage Component
 * @since 1.0.0
 *
 * @uses bp_is_settings_component() are we in the settings component ?
 * @uses bp_is_current_action() is BuddyPlug the current action ?
 */
function buddyplug_is_user_settings() {
	if( bp_is_settings_component() && bp_is_current_action( buddyplug_get_component_slug() ) )
		return true;

	return false;
}

/**
 * Are we on BuddyPlug's component area ?
 * 
 * @package BuddyPlug
 * @subpackage Component
 * @since 1.0.0
 *
 * @uses buddyplug_is_current_component() Is BuddyPlug the current BuddyPress component ?
 * @uses buddyplug_is_user_settings() Are we on BuddyPlug user settings page ?
 */
function buddyplug_is_component_area() {
	if( buddyplug_is_current_component() || buddyplug_is_user_settings() )
		return true;

	return false;
}

/* Other functions */
function buddyplug_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	/* 
	formatting string notifications
	see BuddyPress bp_activity_format_notifications() function for an example..
	*/
}