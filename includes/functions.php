<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* global functions */

/**
 * Returns plugin version
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_plugin_version() {
	return buddyplug()->version;
}

/**
 * Returns plugin's dir
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_plugin_dir() {
	return buddyplug()->plugin_dir;
}

/**
 * Returns plugin's includes dir
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_includes_dir() {
	return buddyplug()->includes_dir;
}

/**
 * Returns plugin's includes url
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_includes_url() {
	return buddyplug()->includes_url;
}

/**
 * Returns plugin's js url
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_js_url() {
	return buddyplug()->plugin_js;
}

/**
 * Returns plugin's js url
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_css_url() {
	return buddyplug()->plugin_css;
}

/**
 * Returns plugin's component includes dir
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_includes_dir() {
	return buddyplug()->component_includes_dir;
}

/**
 * Returns plugin's groups includes dir
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_groups_includes_dir() {
	return buddyplug()->groups_includes_dir;
}

/**
 * Returns plugin's component id
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_id() {
	return buddyplug()->component_id;
}

/**
 * Returns plugin's component slug
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddypress() BuddyPress main instance
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_slug() {
	$slug = !empty( buddypress()->{buddyplug_get_component_id()}->slug ) ? buddypress()->{buddyplug_get_component_id()}->slug : buddyplug()->component_slug;
	return $slug;
}

/**
 * Returns plugin's component primary nav slug
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_primary_subnav_slug() {
	return buddyplug()->component_primary_subnav_slug;
}

/**
 * Returns plugin's component secondary nav slug
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_secondary_subnav_slug() {
	return buddyplug()->component_secondary_subnav_slug;
}

/**
 * Returns plugin's component name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_name() {
	return buddyplug()->component_name;
}

/**
 * Displays plugin's component user settings nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug_get_component_user_settings_nav_name() to get it
 */
function buddyplug_component_user_settings_nav_name() {
	echo buddyplug_get_component_user_settings_nav_name();
}

/**
 * Returns plugin's component user settings nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_user_settings_nav_name() {
	return sanitize_text_field( bp_get_option( 'buddyplug_user_settings_nav_name', buddyplug()->component_user_settings_nav_name ) );
}

/**
 * Displays plugin's component primary nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug_get_component_primary_subnav_name() to get it
 */
function buddyplug_component_primary_subnav_name() {
	echo buddyplug_get_component_primary_subnav_name();
}

/**
 * Returns plugin's component primary nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_primary_subnav_name() {
	return sanitize_text_field( bp_get_option( 'buddyplug_primary_subnav_name', buddyplug()->component_primary_subnav_name ) );
}

/**
 * Displays plugin's component secondary nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug_get_component_secondary_subnav_name() to get it
 */
function buddyplug_component_secondary_subnav_name() {
	echo buddyplug_get_component_secondary_subnav_name();
}

/**
 * Returns plugin's component secondary nav name
 * 
 * @package BuddyPlug
 * @since 1.0.0
 * 
 * @uses buddyplug() plugin's main instance
 */
function buddyplug_get_component_secondary_subnav_name() {
	return sanitize_text_field( bp_get_option( 'buddyplug_secondary_subnav_name', buddyplug()->component_secondary_subnav_name ) );
}
