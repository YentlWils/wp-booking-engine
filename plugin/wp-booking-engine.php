<?php

/*
  Plugin Name: Wordpress Booking Engine
  Plugin URI: YW.com
  Description:
  Version: 1.0.0
  Author: Yentl Wils
  Author URI: http://www.yw.com
  License: GNU General Public License v2 or later
  Text Domain: yw
 */

/**
 * Description of booking
 *
 * @developer duongca
 */
if (session_id() == '') {
    session_start();
}
if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

defined('ABSPATH') or die();

if (!defined('IWBOOKINGVERSION')) {
    define('IWBOOKINGVERSION', '2.0.3');
}

// translate plugin
load_plugin_textdomain('inwavethemes', false, dirname(plugin_basename(__FILE__)) . '/languages/');

global $iwb_settings, $iwb_order;
$iwb_order = new stdClass();
$iwb_settings = unserialize(get_option('iwb_settings'));

include_once 'includes/function.admin.php';
include_once 'includes/function.front.php';

if (!defined('IW_LIMIT_ITEMS')) {
    define('IW_LIMIT_ITEMS', 10);
}
if (!defined('IW_TEXT_DOMAIN')) {
    define('IW_TEXT_DOMAIN', 'inwavethemes');
}
if (!defined('IWBOOKING_THEME_PATH')) {
    define('IWBOOKING_THEME_PATH', WP_PLUGIN_DIR . '/wp-booking-engine/themes/');
}
$utility = new iwBookingUtility();


register_activation_hook(__FILE__, 'iwBookingInstall');
register_deactivation_hook(__FILE__, 'iwBookingDeactive');
register_uninstall_hook(__FILE__, 'iwBookingUninstall');

//Check plugin update
add_action('plugins_loaded', 'iwBooingCheckUpdate');

add_action('wp', 'iwBookingActive');

//Add filter action
//add_filter('cron_schedules', 'iwBookingAddSchedules');
add_action('clear_invalid_booking_order_cronjob', 'iwBookingClearExpiredBookingOrder');

//Add parralax menu
add_action('admin_menu', 'iwBookingAddAdminMenu');
add_action( 'admin_menu', 'add_booking_menu_bubble' );

// Hook into the 'init' action
add_action('init', 'iwBookingCreatePostType', 0);
add_action('init', 'iwBookingAddCategoryTaxonomy', 0);

// Add scripts
add_action('admin_enqueue_scripts', 'iwBookingAdminAddScript');

//init plugin theme
add_action('after_switch_theme', array($utility, 'initPluginThemes'));
add_action('admin_post_iwBookingFilter', 'iwBookingFilter');
//Add metabox
if (is_admin()) {
    add_action('load-post.php', 'addIWBookingMetaBox');
    add_action('load-post-new.php', 'addIWBookingMetaBox');
    addIWBookingCategoryMetaBox();
    add_action('admin_notices', 'iwBookingAdminNotice');
}

//export data
add_action('admin_post_iwBookingExport', 'iwBookingExport');
//import data
add_action('iw_booking_import_data', 'iwBookingImportData');

//Add action to process extrafield
add_action('admin_post_iwBookingSaveExtrafield', 'saveBookingExtrafield');
add_action('admin_post_iwBookingDeleteExtra', 'deleteBookingExtrafield');
add_action('admin_post_iwBookingDeleteExtras', 'deleteBookingExtrafields');

//Add action to process Service
add_action('admin_post_iwBookingSaveService', 'iwBookingSaveService');
add_action('admin_post_iwBookingDeleteService', 'iwBookingDeleteService');
add_action('admin_post_iwBookingDeleteServices', 'iwBookingDeleteServices');
//
///Add action to process Off days
add_action('admin_post_iwBookingSaveOffDay', 'iwBookingSaveOffDay');
add_action('admin_post_iwBookingDeleteOffDay', 'iwBookingDeleteOffDay');
add_action('admin_post_iwBookingDeleteOffDays', 'iwBookingDeleteOffDays');

//Add action to process Discount
add_action('admin_post_iwBookingSaveDiscount', 'iwBookingSaveDiscount');
add_action('admin_post_iwBookingDeleteDiscount', 'iwBookingDeleteDiscount');
add_action('admin_post_iwBookingDeleteDiscounts', 'iwBookingDeleteDiscounts');

//Add action to process member
add_action('admin_post_saveBookingMember', 'saveBookingMember');
add_action('admin_post_deleteBookingMember', 'deleteBookingMember');
add_action('admin_post_deleteBookingMembers', 'deleteBookingMembers');

//Add action to process Payment
add_action('admin_post_iwbUpdateOrderInfo', 'iwBookingUpdateOrderInfo');
add_action('admin_post_orderResendEmail', 'orderResendEmail');
add_action('admin_post_iwBookingClearOrderExpired', 'iwBookingClearOrderExpired');
add_action('admin_post_deleteBookingOrder', 'deleteBookingOrder');
add_action('admin_post_iwBookingDeleteOrders', 'deleteBookingOrders');

//Add action to process Log
add_action('admin_post_iwBookingClearLog', 'iwBookingClearLog');
add_action('admin_post_iwBookingDeleteLogs', 'iwBookingDeleteLogs');
add_action('admin_post_iwBookingDeleteLog', 'iwBookingDeleteLog');

//Add action save settings
add_action('admin_post_iwBookingSaveSettings', 'iwBookingSaveSettings');

/* ----------------------------------------------------------------------------------
  FRONTEND FUNCTIONS
  ---------------------------------------------------------------------------------- */

/**
 * Register and enqueue scripts and styles for frontend.
 *
 * @since 1.0.0
 */
//Add site script
add_action('wp_enqueue_scripts', 'iwBookingAddSiteScript');

add_shortcode('iwb_block_filter_rooms', 'iwb_block_filter_rooms_outhtml');
add_shortcode('iwb_booking_page_filter', 'iwb_booking_page_filter_outhtml');
add_shortcode('iwb_list_rooms', 'iwb_booking_list_rooms');
add_shortcode('iwb_site_name', 'iwb_site_name');
add_shortcode('iwb_first_name', 'iwb_first_name');
add_shortcode('iwb_last_name', 'iwb_last_name');
add_shortcode('iwb_order_link', 'iwb_order_link');
add_shortcode('iwb_order_code', 'iwb_order_code');
add_shortcode('iwb_order_id', 'iwb_order_id');
add_shortcode('iwb_order_price', 'iwb_order_price');
add_shortcode('iwb_admin_email', 'iwb_admin_email');
add_shortcode('iwb_customer_email', 'iwb_customer_email');
add_shortcode('iwb_new_status', 'iwb_new_status');
add_shortcode('iwb_reason', 'iwb_reason');

//add filter
add_filter('the_content', 'iwb_page_content');

//Submit form
add_action('init', 'iwBookingSubmitForm');

//Ajax load iwbPaymentNotice
add_action('wp_ajax_nopriv_iwbPaymentNotice', 'iwbPaymentNotice');
add_action('wp_ajax_iwbPaymentNotice', 'iwbPaymentNotice');

//Ajax load available rooms
add_action('wp_ajax_nopriv_iwb_booking_rooms', 'iwb_booking_rooms');
add_action('wp_ajax_iwb_booking_rooms', 'iwb_booking_rooms');