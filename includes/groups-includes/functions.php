<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Are we on the main BuddyPlug group page ?
 * 
 * @package BuddyPlug
 * @subpackage Group
 * @since 1.0.0
 *
 * @uses bp_is_single_item() to check we're on a single group
 * @uses bp_is_groups_component() to check we're in the group component
 * @uses bp_is_current_action() to check for current action
 */
function buddyplug_is_group_front() {
	if( bp_is_single_item() && bp_is_groups_component() && bp_is_current_action( buddyplug_get_component_slug() ) )
		return true;
	
	return false;
}

/**
 * Are we on the BuddyPlug create step ?
 * 
 * @package BuddyPlug
 * @subpackage Group
 * @since 1.0.0
 *
 * @uses bp_is_group_creation_step() to check for the BuddyPlug groups extension create step
 * @uses buddyplug_get_component_slug() to get the slug of the component
 */
function buddyplug_is_group_create() {
	if( bp_is_group_creation_step( buddyplug_get_component_slug() ) )
		return true;
	
	return false;
}

/**
 * Are we on the BuddyPlug group edit screen ?
 * 
 * @package BuddyPlug
 * @subpackage Group
 * @since 1.0.0
 *
 * @uses bp_is_group_admin_page() to check for the BuddyPlug groups extension edit screen
 * @uses bp_is_action_variable() to get the action variables
 * @uses buddyplug_get_component_slug() to get the slug of the component
 */
function buddyplug_is_group_edit() {
	if( bp_is_group_admin_page() && bp_is_action_variable( buddyplug_get_component_slug(), 0 ) )
		return true;

	return false;
}

/**
 * Are we in BuddyPlug group area ?
 * 
 * @package BuddyPlug
 * @subpackage Group
 * @since 1.0.0
 *
 * @uses buddyplug_is_group_front() Are we on the main BuddyPlug group page ?
 * @uses buddyplug_is_group_create() Are we on the BuddyPlug create step ?
 * @uses buddyplug_is_group_edit() Are we on the BuddyPlug group edit screen ?
 */
function buddyplug_is_group_area() {
	if( buddyplug_is_group_front() || buddyplug_is_group_create() || buddyplug_is_group_edit() )
		return true;

	return false;
}