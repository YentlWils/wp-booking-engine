<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created May 11, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in admin page
 *
 * @developer duongca
 */
require_once 'utility.php';

//Add plugin menu to admin sidebar
function iwBookingAddAdminMenu() {
    //Room Extra menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Room Extrafield', 'inwavethemes'), __('Extrafields', 'inwavethemes'), 'manage_options', 'room-extra', 'iwBookingRoomExtraRenderPage');
    add_submenu_page(null, __('Add Room Extrafield', 'inwavethemes'), null, 'manage_options', 'room-extra/addnew', 'iwBookingAddnewRoomExtraRenderPage');
    add_submenu_page(null, __('Edit Room Extrafield', 'inwavethemes'), null, 'manage_options', 'room-extra/edit', 'iwBookingAddnewRoomExtraRenderPage');
    //Services menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Services', 'inwavethemes'), __('Services', 'inwavethemes'), 'manage_options', 'services', 'iwBookingServicesRenderPage');
    add_submenu_page(null, __('Add New Services', 'inwavethemes'), null, 'manage_options', 'service/addnew', 'iwAddBookingServicesRenderPage');
    add_submenu_page(null, __('Edit Services', 'inwavethemes'), null, 'manage_options', 'service/edit', 'iwAddBookingServicesRenderPage');
    //Discount menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Discount', 'inwavethemes'), __('Discounts', 'inwavethemes'), 'manage_options', 'discount', 'iwBookingDiscountRenderPage');
    add_submenu_page(null, __('Add Discount', 'inwavethemes'), null, 'manage_options', 'discount/addnew', 'iwAddBookingDiscountRenderPage');
    add_submenu_page(null, __('Edit Discount', 'inwavethemes'), null, 'manage_options', 'discount/edit', 'iwAddBookingDiscountRenderPage');
    //Customer menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Customer', 'inwavethemes'), __('Customers', 'inwavethemes'), 'manage_options', 'customer', 'iwBookingCustomerRenderPage');
    add_submenu_page(null, __('Add Customer', 'inwavethemes'), null, 'manage_options', 'customer/addnew', 'iwAddBookingCustomerRenderPage');
    add_submenu_page(null, __('Edit Customer', 'inwavethemes'), null, 'manage_options', 'customer/edit', 'iwAddBookingCustomerRenderPage');
    //Bookings menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Bookings', 'inwavethemes'), __('Bookings', 'inwavethemes'), 'manage_options', 'bookings', 'iwBookingBookingsRenderPage');
    add_submenu_page(null, __('Add Bookings', 'inwavethemes'), null, 'manage_options', 'bookings/edit', 'iwAddBookingPaymentRenderPage');
    add_submenu_page(null, __('Bookings detail', 'inwavethemes'), null, 'manage_options', 'bookings/view', 'iwViewBookingPaymentRenderPage');
    //Log menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Booking Logs', 'inwavethemes'), __('Booking Logs', 'inwavethemes'), 'manage_options', 'logs', 'iwBookingLogRenderPage');
    add_submenu_page(null, __('Log view', 'inwavethemes'), null, 'manage_options', 'log/view', 'iwViewBookingLogRenderPage');
    //Report menu
//    add_submenu_page('edit.php?post_type=iw_booking', __('Report', 'inwavethemes'), __('Report', 'inwavethemes'), 'manage_options', 'report', 'iwBookingReportRenderPage');
    //Settings menu
    add_submenu_page('edit.php?post_type=iw_booking', __('Settings', 'inwavethemes'), __('Settings', 'inwavethemes'), 'manage_options', 'settings', 'iwBookingSettingsRenderPage');
}

function add_booking_menu_bubble() {
    $order = new iwBookingOrder();
    $newOrders = $order->getNewOrders();
    if (!empty($newOrders)) {
        global $menu;
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == 'edit.php?post_type=iw_booking') {
                $menu[$key][0] .= ' <span class="update-plugins count-' . count($newOrders) . '"><span class="plugin-count">' . count($newOrders) . '</span></span>';
                return;
            }
        }
    }
}

if (!function_exists('iwBookingInstall')) {
    /**
     * Function run when plugin installing
     *
     * @global WP_Query $wpdb
     * @global string $iwBookingVersion
     */
    function iwBookingInstall() {
        global $wpdb;

        //IWBOOKINGVERSION

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            $collate = $wpdb->get_charset_collate();
        }

         $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_booking_room_rf (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          room_id int(11) NOT NULL,
          booking_id int(11) NOT NULL,
          amount tinyint(3) NOT NULL,
          adult tinyint(3) NOT NULL,
          children tinyint(3) DEFAULT NULL,
          price float(11,2) DEFAULT NULL,
          price_with_service float(11,2) DEFAULT NULL,
          services varchar(255) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_bookings (
          id int(11) NOT NULL AUTO_INCREMENT,
          customer_id int(11) NOT NULL,
          discount_id int(11) NOT NULL,
          time_start int(11) NOT NULL,
          time_end int(11) NOT NULL,
          time_created int(11) NOT NULL,
          last_update int(11) NOT NULL,
          paid float(11,2) NOT NULL,
          sum_price float(11,2) NOT NULL,
          discount_price float(11,2) NOT NULL,
          price float(11,2) NOT NULL,
          deposit float(11,2) NOT NULL,
          currency varchar(10) NOT NULL,
          note text NOT NULL,
          status tinyint(3) NOT NULL,
          readed tinyint(3) NOT NULL,
          booking_code varchar(50) DEFAULT NULL,
          tax tinyint(3) DEFAULT NULL,
          tax_price float(11,2) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_customer (
          id int(11) NOT NULL AUTO_INCREMENT,
          user_id int(11) DEFAULT NULL,
          email varchar(255) DEFAULT NULL,
          first_name varchar(255) DEFAULT NULL,
          last_name varchar(255) DEFAULT NULL,
          address varchar(255) DEFAULT NULL,
          phone varchar(255) DEFAULT NULL,
          field_value longtext,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_discount (
          id int(11) NOT NULL AUTO_INCREMENT,
          name varchar(255) NOT NULL,
          discount_code varchar(50) NOT NULL,
          type varchar(20) NOT NULL,
          value int(11) NOT NULL,
          time_start int(11) NOT NULL,
          time_end int(11) NOT NULL,
          amount int(11) NOT NULL,
          description text NOT NULL,
          status tinyint(4) NOT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_extrafield (
          id int(11) NOT NULL AUTO_INCREMENT,
          name varchar(255) DEFAULT NULL,
          type varchar(20) DEFAULT NULL,
          icon varchar(50) DEFAULT NULL,
          default_value text COLLATE utf8mb4_unicode_ci,
          description text COLLATE utf8mb4_unicode_ci,
          ordering int(11) DEFAULT NULL,
          published int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_extrafield_category (
          category_id int(11) NOT NULL,
          extrafield_id int(11) NOT NULL
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_extrafield_value (
          room_id int(11) NOT NULL,
          extrafield_id int(11) NOT NULL,
          value text NOT NULL
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_logs (
          id int(11) NOT NULL AUTO_INCREMENT,
          log_type varchar(30) DEFAULT NULL,
          scope varchar(30) DEFAULT NULL,
          timestamp int(11) DEFAULT NULL,
          message text COLLATE utf8mb4_unicode_ci,
          link varchar(255) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_room_service_rf (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          service_id int(11) NOT NULL,
          room_id int(11) NOT NULL,
          booking_id int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}iwb_service (
          id int(11) NOT NULL AUTO_INCREMENT,
          name varchar(255) NOT NULL,
          price int(11) DEFAULT NULL,
          description text NOT NULL,
          status tinyint(4) NOT NULL,
          rate tinyint(1) DEFAULT 1,
          type varchar(20) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $collate;";

        dbDelta($sql);

        //add iwBookingVersion table version
       update_option('iwBookingVersion', IWBOOKINGVERSION);

        //add settings
        if(!get_option('iwb_settings')){
            update_option('iwb_settings', 's:2883:"a:4:{s:7:"general";a:13:{s:8:"currency";s:3:"USD";s:12:"currency_pos";s:4:"left";s:3:"tax";s:2:"10";s:12:"booking_slug";s:10:"iw-booking";s:13:"category_slug";s:12:"iwb-category";s:12:"booking_page";s:3:"209";s:16:"check_order_page";s:3:"642";s:13:"schedule_time";s:1:"1";s:24:"reservation_room_perpage";s:1:"4";s:29:"reservation_datepicker_format";s:6:"d M yy";s:29:"reservation_completed_message";s:134:"Your reservation details have just been sent to your email. If you have any question, please do not hesitate to contact us. Thank you!";s:35:"reservation_completed_contact_phone";s:12:"+11-2233-442";s:35:"reservation_completed_contact_email";s:18:"sales@monalisa.com";}s:20:"register_form_fields";a:5:{i:0;a:7:{s:5:"label";s:10:"First Name";s:4:"name";s:10:"first_name";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:1;a:7:{s:5:"label";s:9:"Last Name";s:4:"name";s:9:"last_name";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:2;a:7:{s:5:"label";s:13:"Email Address";s:4:"name";s:5:"email";s:4:"type";s:5:"email";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:3;a:7:{s:5:"label";s:12:"Phone Number";s:4:"name";s:5:"phone";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:4;a:7:{s:5:"label";s:7:"Address";s:4:"name";s:7:"address";s:4:"type";s:8:"textarea";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"0";s:13:"require_field";s:1:"0";}}s:11:"iwb_payment";a:1:{s:6:"paypal";a:2:{s:5:"email";s:28:"hoak34-facilitator@gmail.com";s:9:"test_mode";s:1:"1";}}s:14:"email_template";a:4:{s:13:"order_created";a:4:{s:6:"enable";s:1:"1";s:5:"title";s:9:"New order";s:10:"recipients";s:20:"[iwb_customer_email]";s:7:"content";s:134:"Hi [iwb_first_name], you have created new order #[iwb_order_id] on [iwb_site_name]. You can check info via this link: [iwb_order_link]";}s:12:"order_onhold";a:4:{s:6:"enable";s:1:"1";s:5:"title";s:35:"Order #[iwb_order_id] has been held";s:10:"recipients";s:20:"[iwb_customer_email]";s:7:"content";s:92:"Your order #[iwb_order_id] has been held, you can check info via this link: [iwb_order_link]";}s:15:"order_cancelled";a:4:{s:6:"enable";s:1:"1";s:5:"title";s:40:"Order #[iwb_order_id] has been cancelled";s:10:"recipients";s:20:"[iwb_customer_email]";s:7:"content";s:69:"Your order #[iwb_order_id] has been cancelled. Thank you for reading!";}s:15:"order_completed";a:4:{s:6:"enable";s:1:"1";s:5:"title";s:40:"Order #[iwb_order_id] has been completed";s:10:"recipients";s:20:"[iwb_customer_email]";s:7:"content";s:97:"Your order #[iwb_order_id] has been completed, you can check info via this link: [iwb_order_link]";}}}";');
        }

        //Add themes
        iwBookingUtility::initPluginThemes();

        flush_rewrite_rules();
    }

}

if (!function_exists('iwBooingCheckUpdate')) {
    function iwBooingCheckUpdate()
    {
        $current_version = get_option('iwBookingVersion');
        if($current_version && version_compare($current_version, '2.0', '<')){
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $sql = "ALTER table {$wpdb->prefix}iwb_booking_room_rf 
                        DROP COLUMN currency;";
            $wpdb->query($sql);
            $sql = "ALTER table {$wpdb->prefix}iwb_booking_room_rf  
                        ADD adult TINYINT(3) NOT NULL,
                        ADD children TINYINT(3) NOT NULL,
                        ADD price_with_service float(11,2) NOT NULL,
                        ADD services TEXT NOT NULL;";
            $wpdb->query($sql);
            $sql = "ALTER table {$wpdb->prefix}iwb_bookings 
                        DROP COLUMN adults,
                        DROP COLUMN childrens;";
            $wpdb->query($sql);
            $sql = "ALTER table {$wpdb->prefix}iwb_bookings   
                        ADD tax TINYINT(3) NOT NULL,
                        ADD tax_price FLOAT(11,2) NOT NULL;";
            $wpdb->query($sql);
            $sql = "ALTER table {$wpdb->prefix}iwb_customer 
                        ADD email varchar(255) NULL,
                        ADD first_name varchar(255) NULL,
                        ADD last_name varchar(255) NULL,
                        ADD phone varchar(255) NULL,
                        ADD address varchar(255) NULL;";
            $wpdb->query($sql);

            //update customer
            $sql = "SELECT * from {$wpdb->prefix}iwb_customer";
            $customers = $wpdb->get_results($sql);
            if($customers){
                foreach ($customers as $customer){
                    $field_values = unserialize($customer->field_value);
                    $customer_data = array();
                    $customer_data['email'] = isset($field_values['email']['value']) ? $field_values['email']['value'] : '';
                    $customer_data['first_name'] = isset($field_values['first_name']['value']) ? $field_values['first_name']['value'] : '';
                    $customer_data['last_name'] = isset($field_values['last_name']['value']) ? $field_values['last_name']['value'] : '';
                    $customer_data['last_name'] = isset($field_values['last_name']['value']) ? $field_values['last_name']['value'] : '';
                    $customer_data['phone'] = isset($field_values['phone']['value']) ? $field_values['phone']['value'] : '';
                    $customer_data['address'] = isset($field_values['address']['value']) ? $field_values['address']['value'] : '';
                    if(isset($field_values['email'])){
                        unset($field_values['email']);
                    }
                    if(isset($field_values['first_name'])){
                        unset($field_values['first_name']);
                    }
                    if(isset($field_values['last_name'])){
                        unset($field_values['last_name']);
                    }
                    if(isset($field_values['phone'])){
                        unset($field_values['phone']);
                    }
                    if(isset($field_values['address'])){
                        unset($field_values['address']);
                    }
                    $new_field_value = array();
                    if($field_values){
                        foreach ($field_values as $key => $field_value){
                            $new_field_value[$key] = isset($field_value['value']) ? $field_value['value'] : '';
                        }
                    }

                    $customer_data['field_value'] = serialize($new_field_value);
                    $wpdb->update($wpdb->prefix . "iwb_customer", $customer_data, array('id' => $customer->id));
                }
            }

            //update plugin option
            global $iwb_settings;
            $iwb_settings['general']['tax'] = '';
            $iwb_settings['general']['reservation_room_perpage'] = '5';
            $iwb_settings['general']['reservation_completed_message'] = "Your reservation details have just been sent to your email. If you have any question, please don't hesitate to contact us. Thank you!";
            $iwb_settings['general']['reservation_completed_contact_phone'] = "+11-2233-442";
            $iwb_settings['general']['reservation_completed_contact_email'] = "sales@monalisa.com";
            update_option('iwb_settings', serialize($iwb_settings));

            update_option('iwBookingVersion', IWBOOKINGVERSION);
        }
        elseif(version_compare($current_version, '2.0', '>') && version_compare($current_version, '2.0.2', '<')){
            global $iwb_settings;
            $iwb_settings['email_template']['order_created']['title'] = 'New order';
            $iwb_settings['email_template']['order_created']['recipients'] = '[iwb_customer_email]';
            $iwb_settings['email_template']['order_created']['content'] = 'Hi [iwb_first_name], you have created new order #[iwb_order_id] on [iwb_site_name]. You can check info via this link: [iwb_order_link]';
            $iwb_settings['email_template']['order_onhold']['enable'] = '1';
            $iwb_settings['email_template']['order_onhold']['title'] = 'Order #[iwb_order_id] has been held';
            $iwb_settings['email_template']['order_onhold']['recipients'] = '[iwb_customer_email]';
            $iwb_settings['email_template']['order_onhold']['content'] = 'Your order #[iwb_order_id] has been held, you can check info via this link: [iwb_order_link]';
            $iwb_settings['email_template']['order_cancelled']['enable'] = '1';
            $iwb_settings['email_template']['order_cancelled']['title'] = 'Order #[iwb_order_id] has been cancelled';
            $iwb_settings['email_template']['order_cancelled']['recipients'] = '[iwb_customer_email]';
            $iwb_settings['email_template']['order_cancelled']['content'] = 'Your order #[iwb_order_id] has been cancelled. Thank you for reading!';
            $iwb_settings['email_template']['order_completed']['enable'] = '1';
            $iwb_settings['email_template']['order_completed']['title'] = 'Order #[iwb_order_id] has been completed';
            $iwb_settings['email_template']['order_completed']['recipients'] = '[iwb_customer_email]';
            $iwb_settings['email_template']['order_completed']['content'] = 'Your order #[iwb_order_id] has been completed, you can check info via this link: [iwb_order_link]';
            update_option('iwb_settings', serialize($iwb_settings));

            update_option('iwBookingVersion', IWBOOKINGVERSION);
        }
    }
}

if (!function_exists('iwBookingDeactive')) {

    function iwBookingDeactive() {
        // find out when the last event was scheduled
        $timestamp = wp_next_scheduled('clear_invalid_booking_order_cronjob');
        // unschedule previous event if any
        wp_unschedule_event($timestamp, 'clear_invalid_booking_order_cronjob');
    }
}

/**
 * Function to register Inwave Booking Category with Wordpress
 */
function iwBookingAddCategoryTaxonomy() {
    global $iwe_settings;
    if ($iwe_settings) {
        $general = $iwe_settings['general'];
    }
    $labels = array('name' => _x('Categories', 'Taxonomy General Name', 'inwavethemes'), 'singular_name' => _x('Category', 'Taxonomy Singular Name', 'inwavethemes'), 'menu_name' => __('Categories', 'inwavethemes'), 'all_items' => __('All Categories', 'inwavethemes'), 'parent_item' => __('Parent Category', 'inwavethemes'), 'parent_item_colon' => __('Parent Category:', 'inwavethemes'), 'new_item_name' => __('New Category Name', 'inwavethemes'), 'add_new_item' => __('Add New Category', 'inwavethemes'), 'edit_item' => __('Edit Category', 'inwavethemes'), 'update_item' => __('Update Category', 'inwavethemes'), 'separate_items_with_commas' => __('Separate categories with commas', 'inwavethemes'), 'search_items' => __('Search categories', 'inwavethemes'), 'add_or_remove_items' => __('Add or remove categories', 'inwavethemes'), 'choose_from_most_used' => __('Choose from the most used categories', 'inwavethemes'), 'not_found' => __('Not Found', 'inwavethemes'),);
    $rewrite = array('slug' => isset($general['category_slug']) ? $general['category_slug'] : 'iwb-category', 'with_front' => true, 'hierarchical' => true,);
    $args = array('labels' => $labels, 'hierarchical' => true, 'public' => true, 'show_ui' => true, 'show_admin_column' => true, 'show_in_nav_menus' => true, 'show_tagcloud' => true, 'rewrite' => $rewrite,);
    register_taxonomy('booking_category', array('iw_booking'), $args);
}

/**
 * Function to register Inwave Booking Post_type with Wordpress
 */
function iwBookingCreatePostType() {
    global $iwb_settings;
    if ($iwb_settings) {
        $general = $iwb_settings['general'];
    }
    $labels = array('name' => _x('IW Rooms', 'Post Type General Name', 'inwavethemes'), 'singular_name' => _x('IW Room', 'Post Type Singular Name', 'inwavethemes'), 'menu_name' => __('IW Booking', 'inwavethemes'), 'parent_item_colon' => __('Parent Room:', 'inwavethemes'), 'all_items' => __('All Rooms', 'inwavethemes'), 'view_item' => __('View Room', 'inwavethemes'), 'add_new_item' => __('Add New Room', 'inwavethemes'), 'add_new' => __('Add Room', 'inwavethemes'), 'edit_item' => __('Edit Item', 'inwavethemes'), 'update_item' => __('Update Item', 'inwavethemes'), 'search_items' => __('Search Item', 'inwavethemes'), 'not_found' => __('Not found', 'inwavethemes'), 'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),);
    $rewrite = array('slug' => isset($general['booking_slug']) ? $general['booking_slug'] : 'iw-booking', 'with_front' => true, 'pages' => true, 'feeds' => true,);
    $args = array('label' => __('booking', 'inwavethemes'), 'description' => __('Inwave Booking Description', 'inwavethemes'), 'labels' => $labels, 'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'comments'), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'show_in_admin_bar' => true, 'menu_position' => 5, 'menu_icon' => 'dashicons-calendar-alt', 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'rewrite' => $rewrite, 'capability_type' => 'page',);
    register_post_type('iw_booking', $args);
}

/**
 * Function to add script for admin page
 */
function iwBookingAdminAddScript() {
    global $wp_scripts;
    // get registered script object for jquery-ui
    $ui = $wp_scripts->query('jquery-ui-core');
    wp_enqueue_style('font-awesome', plugins_url('/wp-booking-engine/assets/css/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style('jquery-ui-smoothness', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $ui->ver . '/themes/smoothness/jquery-ui.min.css', false, null);
    wp_enqueue_style('select2', plugins_url('/wp-booking-engine/assets/css/select2.min.css'));
    wp_enqueue_style('iwbadmin-style', plugins_url('/wp-booking-engine/assets/css/booking_admin.css'));
    wp_enqueue_script('select2', plugins_url() . '/wp-booking-engine/assets/js/select2.min.js', array('jquery'), '1.0.0', true);
    wp_register_script('iwbadmin-script', plugins_url() . '/wp-booking-engine/assets/js/booking_admin.js', array('jquery'), '1.0.0', true);
    wp_localize_script('iwbadmin-script', 'iwBookingCfg', array('siteUrl' => site_url(), 'adminUrl' => admin_url(), 'ajaxUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('iwbadmin-script');
}

function iwBookingFilter() {
    $link = $_SERVER['HTTP_REFERER'];
    $link_param = parse_url($link);
    $q_vars = array();
    parse_str($link_param['query'], $q_vars);
    $post = filter_input_array(INPUT_POST);
    unset($post['action']);
    $query_vars = array_merge($q_vars, $post);
    $new_params = array();
    foreach ($query_vars as $key => $value) {
        if ($value) {
            $new_params[$key] = $value;
        }
    }

    $params = http_build_query($new_params);
    wp_redirect($link_param['scheme'] . '://' . $link_param['host'] . $link_param['path'] . '?' . $params);
}

/* * *********************************************************
 * ******** FUNCTION ADD METABOX FOR IW DIRECTORY POST ********
 * ******************************************************** */

if (is_admin()) {

    function addIWBookingMetaBox() {
        new iwBookingMetaBox();
    }

    function addIWBookingCategoryMetaBox() {
        new iwBookingCategoryMetaBox();
    }

}

/* * *********************************************************
 * ******** FUNCTION PROCESS BOOKING EXTRA ********
 * ******************************************************** */

/**
 * Function to render Room extrafield management page
 */
function iwBookingRoomExtraRenderPage() {
    $extrafield = new iwBookingExtra();
    $paging = new iwPaging();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $count = $extrafield->getCountExtrafield(filter_input(INPUT_GET, 'keyword'));
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $extrafields = $extrafield->getBookingExtras($start, IW_LIMIT_ITEMS,filter_input(INPUT_GET, 'keyword'));
    include_once 'views/extrafield.list.php';
}

/**
 * Function to render Add new or Edit Extrafield page
 */
function iwBookingAddnewRoomExtraRenderPage() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $extrafield = new iwBookingExtra();
    if ($id) {
        $extrafield = $extrafield->getBookingExtra($id);
        if (!$extrafield->getId()) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(sprintf(__('No extrafield found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice');
        }
    }
    include_once 'views/extrafield.edit.php';
}

/**
 * Function to save extrafield
 */
function saveBookingExtrafield() {
    $extra = new iwBookingExtra();
    if (isset($_POST)) {
        $msg = '';
        if ($_POST['name'] != '') {
            $title = $_POST['name'];
        } else {
            $msg .= __('- Please input name of field<br/>');
        }
        if ($_POST['type'] != '') {
            $field_type = $_POST['type'];
        } else {
            $msg .= __('- Please select a field type<br/>');
        }

        if ($field_type == 'textarea') {
            $default_value = stripslashes($_POST['text_value']);
        }
        if ($field_type == 'link') {
            $default_value = serialize(array('link_value_link' => stripslashes($_POST['link_value_link']), 'link_value_text' => stripslashes($_POST['link_value_text']), 'link_value_target' => stripslashes($_POST['link_value_target'])));
        }
        if ($field_type == 'image') {
            $default_value = $_POST['image'];
        }
        if ($field_type == 'text') {
            $default_value = stripslashes($_POST['string_value']);
        }
        if ($field_type == 'dropdown_list') {
            $default_value = serialize(array(stripslashes($_POST['drop_value']), $_POST['drop_multiselect']));
        }
        if ($field_type == 'date') {
            $default_value = $_POST['date_value'];
        }
        if ($field_type == 'measurement') {
            $default_value = serialize(array('measurement_value' => stripslashes($_POST['measurement_value']), 'measurement_unit' => stripslashes($_POST['measurement_unit'])));
        }
        if ($msg == '') {
            $extra->setId(isset($_POST['id']) ? $_POST['id'] : null);
            $extra->setName($title);
            $extra->setIcon($_POST['icon']);
            $extra->setDescription($_POST['description']);
            $extra->setPublished($_POST['published']);
            $extra->setOrdering(isset($_POST['ordering']) ? $_POST['ordering'] : null);
            $extra->setCategories($_POST['categories']);
            $extra->setType($_POST['type']);
            $extra->setDefault_value($default_value);
            if ($extra->getId()) {
                $update = unserialize($extra->editBookingExtra($extra));
                if (!$update['success']) {
                    $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'error');
                } else {
                    $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'success');
                }
            } else {
                $insert = unserialize($extra->addBookingExtra($extra));
                if (!$insert['success']) {
                    $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg'], 'error');
                } else {
                    $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg']);
                    $extra->setId($insert['data']);
                }
            }
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($msg, 'error');
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No data send'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=room-extra/' . ($extra->getId() ? 'edit&id=' . $extra->getId() : 'addnew')));
}

/**
 * Delete single extrafield on list
 */
function deleteBookingExtrafield() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $extra = new iwBookingExtra();
    if ($id && is_numeric($id)) {
        $del = unserialize($extra->deleteBookingExtra($id));
        if (!$del['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($del['msg'], 'error');
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Booking extrafield has been removed', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=room-extra'));
}

/**
 * Delete multiple Extrafield on list
 */
function deleteBookingExtrafields() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $extra = new iwBookingExtra();
        $ids = $_POST['fields'];
        $msg = $extra->deleteBookingExtras($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', IW_TEXT_DOMAIN), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=room-extra'));
}

/* * **************************************************
 * ******* CODE PROCESS SERVICES ****************
 * ************************************************** */

/**
 * Function to render service manage page
 */
function iwBookingServicesRenderPage() {
    $service = new iwBookingService();
    $paging = new iwPaging();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $count = $service->getCountService(0, null, filter_input(INPUT_GET, 'keyword'));
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $services = $service->getServices($start, IW_LIMIT_ITEMS, 0, null, filter_input(INPUT_GET, 'keyword'));
    include_once 'views/service.list.php';
}

/**
 * Function to render Add new or Edit Service page
 */
function iwAddBookingServicesRenderPage() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $service = new iwBookingService();
    if ($id) {
        $service = $service->getService($id);
        if (!$service->getId()) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(sprintf(__('No service found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice');
        }
    }
    include_once 'views/service.edit.php';
}

/**
 * Function to save or update service
 */
function iwBookingSaveService() {
    $post = $_POST;
    $msg = '';
    if (!$post['name']) {
        $msg = __('Please input name of Service', 'inwavethemes');
    }
    if ($msg) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage($msg, 'error');
        wp_redirect(admin_url('edit.php?post_type=iw_booking&page=service/' . ($post['id'] ? 'edit&id=' . $post['id'] : 'addnew')));
    } else {
        $service = new iwBookingService();
        $service->setId($post['id']);
        $service->setName($post['name']);
        $service->setDescription($post['description']);
        $service->setPrice($post['price']);
        $service->setType($post['type']);
        $service->setRate($post['rate']);
        $service->setStatus($post['status']);

        if ($service->getId()) {
            $update = unserialize($service->editService($service));
            if (!$update['success']) {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'error');
            } else {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'success');
            }
        } else {
            $insert = unserialize($service->addService($service));
            if (!$insert['success']) {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg'], 'error');
            } else {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg']);
                $service->setId($insert['data']);
            }
        }
        wp_redirect(admin_url('edit.php?post_type=iw_booking&page=service/' . ($service->getId() ? 'edit&id=' . $service->getId() : 'addnew')));
    }
}

/**
 * Delete single Service on list
 */
function iwBookingDeleteService() {
    $id = $_GET['id'];
    $service = new iwBookingService();
    if ($id && is_numeric($id)) {
        $del = unserialize($service->deleteService($id));
        if (!$del['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($del['msg'], 'error');
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Service has been removed', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('ID not set or invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=services'));
}

/**
 * Delete multiple Services (selected Services) on list
 */
function iwBookingDeleteServices() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $service = new iwBookingService();
        $ids = $_POST['fields'];
        $msg = $service->deleteServices($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=services'));
}

/* * **************************************************
 * ******* CODE PROCESS DISCOUNT ****************
 * ************************************************** */

/**
 * Function to render discount manage page
 */
function iwBookingDiscountRenderPage() {
    $discount = new iwBookingDiscount();
    $paging = new iwPaging();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $count = $discount->getCountDiscount();
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $discounts = $discount->getDiscounts($start, IW_LIMIT_ITEMS);
    include_once 'views/discount.list.php';
}

/**
 * Function to render Addnew or Edit Service page
 */
function iwAddBookingDiscountRenderPage() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $discount = new iwBookingDiscount();
    if ($id) {
        $discount = $discount->getDiscount($id);
        if (!$discount->getId()) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(sprintf(__('No Discount found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice');
        }
    }
    include_once 'views/discount.edit.php';
}

/**
 * Function to save or update service
 */
function iwBookingSaveDiscount() {
    $post = $_POST;
    $msg = '';
    if (!$post['name']) {
        $msg .= __('- Please input name of Discount', IW_TEXT_DOMAIN);
    }
    if (!$post['discount_code']) {
        if ($msg) {
            $msg .= '<br/>';
        }
        $msg .= __('- Please input Discount Code', IW_TEXT_DOMAIN);
    }
    if (!$post['time_start']) {
        if ($msg) {
            $msg .= '<br/>';
        }
        $msg .= __('- Please input Time start', IW_TEXT_DOMAIN);
    }
    if (!$post['time_end']) {
        if ($msg) {
            $msg .= '<br/>';
        }
        $msg .= __('- Please input Time end', IW_TEXT_DOMAIN);
    }
    if (!$post['value']) {
        if ($msg) {
            $msg .= '<br/>';
        }
        $msg .= __('- Please input Value of discount', IW_TEXT_DOMAIN);
    }
    if ($msg) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage($msg, 'error');
        wp_redirect(admin_url('edit.php?post_type=iw_booking&page=discount/' . ($post['id'] ? 'edit&id=' . $post['id'] : 'addnew')));
    } else {
        $discount = new iwBookingDiscount();
        $discount->setId(isset($post['id']) ? $post['id'] : null);
        $discount->setName($post['name']);
        $discount->setDescription($post['description']);
        $discount->setStatus($post['status']);
        $discount->setAmount($post['amount']);
        $discount->setDiscount_code($post['discount_code']);
        $discount->setTime_start(strtotime($post['time_start']));
        $discount->setTime_end(strtotime($post['time_end']));
        $discount->setType($post['type']);
        $discount->setValue($post['value']);

        if ($discount->getId()) {
            $update = unserialize($discount->editDiscount($discount));
            if (!$update['success']) {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'error');
            } else {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($update['msg'], 'success');
            }
        } else {
            $insert = unserialize($discount->addDiscount($discount));
            if (!$insert['success']) {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg'], 'error');
            } else {
                $_SESSION['bt_message'] = iwBookingUtility::getMessage($insert['msg']);
                $discount->setId($insert['data']);
            }
        }
        wp_redirect(admin_url('edit.php?post_type=iw_booking&page=discount/' . ($discount->getId() ? 'edit&id=' . $discount->getId() : 'addnew')));
    }
}

/**
 * Delete single Service on list
 */
function iwBookingDeleteDiscount() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $discount = new iwBookingDiscount();
    if ($id && is_numeric($id)) {
        $del = unserialize($discount->deleteDiscount($id));
        if (!$del['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($del['msg'], 'error');
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Discount has been removed', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('ID not set or invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=discount'));
}

/**
 * Delete multiple Services (selected Services) on list
 */
function iwBookingDeleteDiscounts() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $discount = new iwBookingDiscount();
        $ids = $_POST['fields'];
        $msg = $discount->deleteDiscounts($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=discount'));
}

/* * **************************************************
 * ************ CODE SETTINGS PAGE ****************
 * ************************************************** */

function iwBookingSettingsRenderPage() {
    include_once 'views/settings.php';
}

function iwBookingSaveSettings() {
    $data = $_POST;
    $form_field = $data['iwb_settings']['register_form_fields'];
    $fields = array();
    if (!empty($form_field['label'])) {
        foreach ($form_field['label'] as $key => $value) {
            $field = array();
            $field['label'] = $value;
            $field['name'] = $form_field['name'][$key];
            //$field['group'] = $form_field['group'][$key];
            $field['type'] = $form_field['type'][$key];
            if ($field['type'] == 'select') {
                $options = explode("\n", $form_field['values'][$key]);
                $dataf = array();
                foreach ($options as $option) {
                    $op = explode('|', $option);
                    $dataf[] = array('value' => $op[0], 'text' => $op[1]);
                }
                $field['values'] = $dataf;
            } else {
                $field['values'] = isset($form_field['values'][$key]) ? $form_field['values'][$key] : '';
            }
            $field['default_value'] = isset($form_field['default_value'][$key]) ? $form_field['default_value'][$key] : '';
            $field['show_on_list'] = $form_field['show_on_list'][$key];
            $field['require_field'] = $form_field['require_field'][$key];
            $fields[] = $field;
        }
    }

    $data['iwb_settings']['register_form_fields'] = $fields;

    update_option('iwb_settings', serialize($data['iwb_settings']));
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=settings'));
}

/* * **************************************************
 * ************ CODE CUSTOMER PAGE ****************
 * ************************************************** */

function iwBookingCustomerRenderPage() {
    global $iwb_settings;
    $member = new iwBookingCustomer();
    $paging = new iwPaging();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $count = $member->getCountCustomer();
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $members = $member->getCustomers($start, IW_LIMIT_ITEMS, filter_input(INPUT_GET, 'keyword'));

    $form_fields = $iwb_settings['register_form_fields'];
    $field_to_show = array();

    foreach ($form_fields as $field_show) {
        if ($field_show['show_on_list'] == 1) {
            $field_to_show[] = array($field_show['name'], $field_show['label']);
        }
    }

    include_once 'views/member.list.php';
}

function iwAddBookingCustomerRenderPage() {
    global $iwb_settings;
    $id = $_GET['id'];
    $member = new iwBookingCustomer($id);
    if ($member->id) {
            include_once 'views/member.edit.php';
    } else {
        echo iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
}

function saveBookingMember() {
    $member = new iwBookingCustomer($_REQUEST['id']);
    $message = '';
    $custom_data = iwBookingUtility::getMemberFieldValue($_REQUEST['member'], $message);
    $updateMember = $member->updateCustomer($custom_data);
    if ($updateMember) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Update member success', 'inwavethemes'));
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Can\'t update member: ', 'inwavethemes'));
    }

    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=customer/edit&id=' . $_REQUEST['id']));
}

/**
 * Delete single type on list
 */
function deleteBookingMember() {
    $id = $_GET['id'];
    $member = new iwBookingCustomer();
    if ($id && is_numeric($id)) {
        $del = unserialize($member->deleteCustomer($id));
        if (!$del['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($del['msg'], 'error');
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Member has been remove', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=customer'));
}

/**
 * Delete multiple Location type (selected Location types) on list
 */
function deleteBookingMembers() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $member = new iwBookingCustomer();
        $ids = $_POST['fields'];
        $msg = $member->deleteCustomers($ids);
        if ($msg['error'] && $msg['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
        } elseif ($msg['error'] && !$msg['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
        } elseif (!$msg['error'] && $msg['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=customer'));
}

/* * **************************************************
 * ************ CODE BOOKINGS PAGE ****************
 * ************************************************** */

function iwBookingBookingsRenderPage() {
    $filter = '';
    $orderby = '';
    $request = filter_input_array(INPUT_GET);
    $server_data = $_SERVER;
    parse_str($server_data['QUERY_STRING'], $server_data['query']);
    unset($server_data['query']['dir']);
    unset($server_data['query']['orderby']);
    $order_link = admin_url('edit.php'). '?' . http_build_query($server_data['query']);
    $order = new iwBookingOrder();
    $paging = new iwPaging();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $attrs = array(
        'start' => $start,
        'limit' => IW_LIMIT_ITEMS,
        'ordering' => isset($request['orderby']) ? $request['orderby'] : 'time_created',
        'ordering_dir' => isset($request['dir']) ? $request['dir'] : 'desc',
        'keyword' => isset($request['keyword']) ? $request['keyword'] : '',
        'status' => isset($request['status']) ? $request['status'] : ''
    );
    $count = $order->getCountOrder($attrs);
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $orders = $order->getOrders($attrs);
    $order_dir = (filter_input(INPUT_GET, 'dir') == 'asc');
    $sorted = filter_input(INPUT_GET, 'orderby');
    include_once 'views/payment.list.php';
}

function iwViewBookingPaymentRenderPage() {
    $id = $_GET['id'];
    if ($id && is_numeric($id)) {
        $order = new iwBookingOrder();
        $order = $order->getOrder($id);
        include_once 'views/payment.view.php';
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
        wp_redirect(admin_url('edit.php?post_type=iw_booking&page=bookings'));
    }
}

/**
 * Function to render Addnew or Edit Payment page
 */
function iwAddBookingPaymentRenderPage() {
    global $iwb_settings;
    $id = $_GET['id'];
    $order = new iwBookingOrder();
    if ($id) {
        $order = $order->getOrder($id);
        if (!$order->getId()) {
            echo iwBookingUtility::getMessage(sprintf(__('No Order found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice');
        } else {
            include_once 'views/payment.edit.php';
        }
    } else {
        echo iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
}

function iwBookingUpdateOrderInfo() {
    global $iwb_order;
    $data = $_REQUEST;
    $order = new iwBookingOrder($data['order_id']);
    $member = new iwBookingCustomer($data['order_member']);
    if($member->id){
        $message = '';
        $data['member'] = iwBookingUtility::getMemberFieldValue($data['member'], $message);
        $updateMember = $member->updateCustomer($data['member']);
    }
    $data_update = array();
    $data_update['paid'] = $data['paid'];
    $data_update['note'] = $data['order_note'];
    $data_update['status'] = $data['new_order_status'];
    $data_update['time_start'] = strtotime($data['time_start']);
    $data_update['time_end'] = strtotime($data['time_end']);
    $data_update['last_update'] = time();
    $update = $order->updateOrder($data_update);

    if ($update && $data['new_order_status'] != $data['order_status']) {
        $iwb_order->order = $order->getOrder($data['order_id']);
        iwBookingUtility::sendEmail($order, $data['new_order_status']);
    }

    if ($update) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(sprintf(__('Update order %s successfully', 'inwavethemes'), $data['order_id'] ), 'success');
    } else{
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(sprintf(__('Can not update order %s', 'inwavethemes'), $data['order_id'] ), 'error');
    }

    wp_redirect($order->getLink($data['order_id']));
}

function orderResendEmail() {
    $order = new iwBookingOrder();
    $order = $order->getOrder($_REQUEST['id']);
    $sendmail = iwBookingUtility::sendEmail($order, 'order_info');
    if ($sendmail) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Send email successfully', 'inwavethemes'));
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Can not send email successfully', 'error'));
    }
    wp_redirect($order->getLink($order->getId()));
}

function iwBookingClearOrderExpired() {
    $iwe_settings = unserialize(get_option('iwe_settings'));
    $timeKill = $iwe_settings['general']['order_time_expired'];
    if (!$timeKill) {
        $timeKill = 2;
    }

    $order_time_kill = time() - $timeKill * 3600;
    $order = new iwBookingOrder();
    $kills = $order->killOrderExpired($order_time_kill);
    if ($kills > 0) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage('Have ' . $kills . ' orders killed');
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No order math to kill', 'inwavethemes'));
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=bookings'));
}

/**
 * Delete single order on list
 */
function deleteBookingOrder() {
    $id = $_GET['id'];
    $order = new iwBookingOrder();
    if ($id && is_numeric($id)) {
        $order->deleteOrder($id);
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Order has been removed', 'inwavethemes'));
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=bookings'));
}

/**
 * Delete multiple orders (selected order) on list
 */
function deleteBookingOrders() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $order = new iwBookingOrder();
        $ids = $_POST['fields'];
        $order->deleteOrders($ids);
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('All selected order(s) has been deleted', 'inwavethemes'));
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=bookings'));
}

/* * **************************************************
 * ************ CODE LOG PAGE ****************
 * ************************************************** */

function iwBookingLogRenderPage() {
    $paging = new iwPaging();
    $logs = new iwBookingLog();
    $start = $paging->findStart(IW_LIMIT_ITEMS);
    $count = count($logs->getAllLogs());
    $pages = $paging->findPages($count, IW_LIMIT_ITEMS);
    $logOnPage = $logs->getLogsPerPage($start, IW_LIMIT_ITEMS);
    include_once 'views/logs.list.php';
}

function iwViewBookingLogRenderPage() {
    $id = $_GET['id'];
    if ($id && is_numeric($id)) {
        $log = new iwBookingLog();
        $log = $log->getLog($id);
        include_once 'views/logs.view.php';
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
        wp_redirect(admin_url('edit.php?post_type=booking&page=logs'));
    }
}

function iwBookingDeleteLog() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $log = new iwBookingLog();
    if ($id && is_numeric($id)) {
        $del = unserialize($log->deleteLog($id));
        if (!$del['success']) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage($del['msg'], 'error');
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Log has been removed', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('No id set or id invalid', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=logs'));
}

function iwBookingDeleteLogs() {
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $log = new iwBookingLog();
        $ids = $_POST['fields'];
        $msg = $log->deleteLogs($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
        } else {
            $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
        }
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error');
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=logs'));
}

function iwBookingClearLog() {
    $log = new iwBookingLog();
    $msg = $log->emptyLog();
    if (isset($msg['error']) && isset($msg['success'])) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error'] . $msg['success']), 'notice');
    } elseif (isset($msg['error']) && !isset($msg['success'])) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['error']), 'error');
    } elseif (!isset($msg['error']) && isset($msg['success'])) {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__($msg['success']));
    } else {
        $_SESSION['bt_message'] = iwBookingUtility::getMessage(__('Unknown error', 'inwavethemes'));
    }
    wp_redirect(admin_url('edit.php?post_type=iw_booking&page=logs'));
}

/* * *************************************************
 * ************** CODE REPORT PAGE ****************
 * ************************************************ */

function iwBookingReportRenderPage() {
    include_once('views/booking-statistic.php');
}

/* * **********************************************
 * ********* OTHER FUNCTION **********************
 * ******************************************** */

function iwBookingAdminNotice() {
    global $iwb_settings;
    $general = $iwb_settings['general'];
    if (!isset($general['booking_page']) || !$general['booking_page']) {
        echo iwBookingUtility::getMessage(sprintf(__('Please select booking page in <a href="%s">plugin setting</a>', IW_TEXT_DOMAIN), admin_url() . 'edit.php?post_type=iw_booking&page=settings'), 'notice');
    }
    if (!isset($general['check_order_page']) || !$general['check_order_page']) {
        echo iwBookingUtility::getMessage(sprintf(__('Please select payment page in <a href="%s">plugin setting</a>', IW_TEXT_DOMAIN), admin_url() . 'edit.php?post_type=iw_booking&page=settings'), 'notice');
    }
}

function iwBookingExport() {
    WP_Filesystem();
    global $wpdb, $wp_filesystem;
    //Export rooms
    //Export Services
    //Export Extrafield

    $theme_dir = get_theme_root();
    $data_dir = $theme_dir . '/monalisa/framework/importer/data/';

    //Create portfolios data file
    $iwc_file = $data_dir . 'iw_booking.json';
    $iwcdatas = array();

    // safe query: no input data
    $iwcextrafield = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield');
    $iwb_service = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_service');

    // safe query: no input data
    $iwcextrafieldcat = $wpdb->get_results('SELECT a.slug as category_alias, b.extrafield_id FROM ' . $wpdb->prefix . 'iwb_extrafield_category as b LEFT JOIN ' . $wpdb->prefix . 'terms as a ON a.term_id = b.category_id');
    $iwcextrafieldval = $wpdb->get_results('SELECT a.post_name as room_slug, b.extrafield_id, b.value FROM ' . $wpdb->prefix . 'iwb_extrafield_value as b LEFT JOIN ' . $wpdb->prefix . 'posts as a ON a.ID = b.room_id');

    if ($iwcextrafield) {
        $iwcdatas['iwb_extrafield'] = $iwcextrafield;
    }
    if ($iwcextrafieldcat) {
        $iwcdatas['iwb_extrafield_category'] = $iwcextrafieldcat;
    }
    if ($iwcextrafieldval) {
        $iwcdatas['iwb_extrafield_value'] = $iwcextrafieldval;
    }
    if ($iwb_service) {
        $iwcdatas['iwb_service'] = $iwb_service;
    }
    $iwcontent = json_encode($iwcdatas);
    if ($iwcontent) {
        if (file_exists($iwc_file)) {
            unlink($iwc_file);
        }
        $wp_filesystem->put_contents($iwc_file, $iwcontent);
    }

    echo 'Data exported success<br/>';
    echo '<a href="' . admin_url() . '">CLICK HERE</a> to back admin home';
    //Export Settings
}

function iwBookingImportData() {
    WP_Filesystem();
    global $wpdb, $wp_filesystem;

    $theme_dir = get_theme_root();
    $data_dir = $theme_dir . '/monalisa/framework/importer/data/';
    $file = $data_dir . 'iw_booking.json';
    $content = $wp_filesystem->get_contents($file);
    $data = json_decode($iw_data, true);
    foreach ($data as $table => $list) {
        foreach ($list as $item) {
            switch ($table) {

                case 'iwb_extrafield_category':
                    if ($item['category_alias']) {
                        $category = get_term_by('slug', $item['category_alias'], 'booking_category');
                        $catid = $category->term_id;
                    } else {
                        $catid = 0;
                    }
                    unset($item['category_alias']);
                    $item['category_id'] = $catid;
                    $wpdb->insert($wpdb->prefix . $table, $item);
                    break;
                case 'iwb_extrafield_value':
                    $room_id = '';
                    if ($item['room_slug']) {
                        $room = $posts = get_posts(array(
                            'name' => $item['room_slug'],
                            'posts_per_page' => 1,
                            'post_type' => 'iw_booking'
                        ));
                        $room_id = $room[0]->ID;
                    }
                    if ($room_id) {
                        unset($item['room_slug']);
                        $item['room_id'] = $room_id;
                        $wpdb->insert($wpdb->prefix . $table, $item);
                    }
                    break;
                default:
                    $wpdb->insert($wpdb->prefix . $table, $item);
                    break;
            }
        }
    }
    var_dump(json_decode($content));
}

function iwBookingAddSchedules($schedules) {
    global $iwb_settings;
    if (isset($iwb_settings['general']['schedule_time'])) {
        $schedules['iwb_schedule_time'] = array(
            'interval' => intval($iwb_settings['general']['schedule_time'] * 3600),
            'display' => __('Booking CronJob Time', 'inwavethemes')
        );
    }

    return $schedules;
}

// here's the function we'd like to call with our cron job
function iwBookingClearExpiredBookingOrder() {
    global $iwb_settings;
    if(intval($iwb_settings['general']['schedule_time']) == 0){
        return;
    }
    $iwBookingOrder = new iwBookingOrder();
    $held_duration = intval($iwb_settings['general']['schedule_time']) * 3600;
    $iwBookingOrder->clearExpiredOrder($held_duration);
    //$iwBookingOrder->removeDraftBookings($schedule_time);

    wp_clear_scheduled_hook( 'clear_invalid_booking_order_cronjob' );
    wp_schedule_single_event( time() + ( absint( $held_duration ) ), 'clear_invalid_booking_order_cronjob' );
}

function iwBookingActive() {
    wp_clear_scheduled_hook( 'clear_invalid_booking_order_cronjob' );
    global $iwb_settings;
    $held_duration = intval($iwb_settings['general']['schedule_time']) * 3600;
    wp_schedule_single_event( time() + $held_duration , 'clear_invalid_booking_order_cronjob' );
}

function iwb_site_name($atts) {
    return get_option('blogname');
}

function iwb_first_name($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['first_name'])){
        return $iwb_email_data['first_name'];
    }
    return '';
}

function iwb_last_name($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['last_name'])){
        return $iwb_email_data['last_name'];
    }
    return '';
}

function iwb_order_link($atts) {
    global $iwb_email_data, $iwb_settings;
    $order_link = get_permalink($iwb_settings['general']['check_order_page']);
    $code = isset($iwb_email_data['order_code']) ? $iwb_email_data['order_code'] : '';
    $email = isset($iwb_email_data['email']) ? $iwb_email_data['email'] : '';
    $order_link = add_query_arg('ordercode', $code, $order_link);
    $order_link = add_query_arg('email', $email, $order_link);

    return '<a href="' . $order_link . '">' . __('View booking info', 'inwavethemes') . '</a>';
}

function iwb_order_code($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['booking_code'])){
        return $iwb_email_data['booking_code'];
    }

    return '';
}
function iwb_order_id($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['order_id'])){
        return $iwb_email_data['order_id'];
    }

    return '';
}
function iwb_admin_email($atts) {
    return get_option('admin_email');
}
function iwb_customer_email($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['email'])){
        return $iwb_email_data['email'];
    }

    return '';
}

function iwb_reason($atts) {
    global $iwb_email_data;
    if(isset($iwb_email_data['reason'])){
        return $iwb_email_data['reason'];
    }

    return '';
}

function iwb_new_status($atts) {
    global $iwb_order;
    $stext = '';
    switch ($iwb_order->order->getStatus()) {
        case 1:
            $stext = __('Pending', 'inwavethemes');
            break;
        case 2:
            $stext = __('Completed', 'inwavethemes');
            break;
        case 3:
            $stext = __('Cancelled', 'inwavethemes');
            break;
        case 4:
            $stext = __('Onhold', 'inwavethemes');
            break;
        default:
            break;
    }
    return $stext;
}