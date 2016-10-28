<?php
/**
 * Intravel Uninstall
 *
 * Uninstalling Intravel deletes user roles, pages, tables, and options.
 *
 * @author      InwaveThemes
 * @category    Core
 * @package     Intravel/Uninstaller
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

wp_clear_scheduled_hook( 'clear_invalid_booking_order_cronjob' );

// Tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_service" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_bookings" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_discount" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_booking_room_rf" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_booking_service_rf" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_extrafield" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_extrafield_value" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_extrafield_category" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_customer" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}iwb_logs" );

// Delete options.
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'iwb\_%';");

// Delete posts + data.
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'iw_booking' );" );
$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
