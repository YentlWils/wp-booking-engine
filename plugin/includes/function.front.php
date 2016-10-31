<?php

/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created Mar 2, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in front page
 *
 * @developer duongca
 */
require_once 'utility.php';


function iwbPaymentNotice() {
    //file_put_contents(WP_PLUGIN_DIR .'/iw_booking/paypal.text', 'aaa');
    global $iwb_settings;
    $iwblog = new iwBookingLog();

    $test_mode = $iwb_settings['iwb_payment']['paypal']['test_mode'];
    // Get received values from post data
    $validate_ipn = array( 'cmd' => '_notify-validate' );
    $validate_ipn += wp_unslash( $_POST );

    // Send back post vars to paypal
    $params = array(
        'body'        => $validate_ipn,
        'timeout'     => 60,
        'httpversion' => '1.1',
        'compress'    => false,
        'decompress'  => false,
        'user-agent'  => 'IWBooking/1.0'
    );

    // Post back to get a response.

    $response = wp_safe_remote_post( $test_mode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $params );

    if (! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' )) {
        //file_put_contents(WP_PLUGIN_DIR .'/iw_booking/paypal.text', 'aaa');
        $order_id = $_POST['item_number'];
        $custom = $_POST['custom'];
        $payment_status = $_POST['payment_status'];
        $paid = $_POST['payment_gross'];
        $order = new iwBookingOrder();
        $orderObj = $order->getOrder($order_id);
        if($orderObj->id){
            //order status: 1-pendding, 2-paid, 3-cancel, 4-onhold
            //Paypal status: CANCELED, CREATED, COMPLETED, INCOMPLETE, ERROR, REVERSALERROR, PROCESSING, PENDING
            switch ($payment_status) {
                case 'Completed':
                    if($custom == 'deposit'){
                        if ($orderObj->updateStatus(4)) {
                            $orderObj->updatePaid($paid);
                            iwBookingUtility::sendEmail($orderObj, '4');
                            $iwblog->addLog(new iwBookingLog(NULL, 'success', time(), 'booking', __('Order ' . $order_id . ' has been change status to Hold', 'inwavethemes')));
                        }
                    }else{
                        if ($orderObj->updateStatus(2)) {
                            $orderObj->updatePaid($paid);
                            iwBookingUtility::sendEmail($orderObj, '2');
                            $iwblog->addLog(new iwBookingLog(NULL, 'success', time(), 'booking', __('Order ' . $order_id . ' has been change status to Completed', 'inwavethemes')));
                        }
                    }

                    break;
                case 'Pendding':
                    if ($orderObj->updateStatus(1)) {
                        $iwblog->addLog(new iwBookingLog(NULL, 'notice', time(), 'booking', __('Order ' . $order_id . ' has been change status to Pendding', 'inwavethemes')));
                    }
                    break;
                case 'Refunded':
                    if ($orderObj->updateStatus(3)) {
                        $iwblog->addLog(new iwBookingLog(NULL, 'notice', time(), 'booking', __('Order ' . $order_id . ' was refunded status change to cancel', 'inwavethemes')));
                    }
                    break;

                default:
                    if ($orderObj->updateStatus(3)) {
                        $iwblog->addLog(new iwBookingLog(NULL, 'notice', time(), 'booking', __('Order ' . $order_id . ' has been change status to Onhold', 'inwavethemes')));
                    }
                    break;
            }
        }
    }
    else if (strstr( $response['body'], 'INVALID' )) {
        $msg = "Invalid IPN request";
        $iwblog->addLog(new iwBookingLog(NULL, 'error', time(), 'booking', $msg . '<br/>' . serialize($params)));
    }
}

function iwBookingSubmitForm() {
    $action = filter_input(INPUT_POST, 'action');
    if ($action) {
        switch ($action) {
            case 'iwBookingSearchRooms':
                global $iwb_settings;
                $formPost = filter_input_array(INPUT_POST);
                $args = array();
                if (isset($formPost['checkin']) && $formPost['checkin']) {
                    $args['checkin'] = strtotime($formPost['checkin']);
                }
                if (isset($formPost['checkout']) && $formPost['checkout']) {
                    $args['checkout'] = strtotime($formPost['checkout']);
                }
                if (isset($formPost['adult']) && $formPost['adult']) {
                    $args['adult'] = $formPost['adult'];
                }
                if (isset($formPost['filter_room']) && $formPost['filter_room']) {
                    $args['filter_room'] = $formPost['filter_room'];
                }
                $args['state'] = 2;
                $url = get_permalink($iwb_settings['general']['booking_page']);
                if (!empty($args)) {
                    if (strpos($url, '?')) {
                        $url.='&' . http_build_query($args);
                    } else {
                        $url.='?' . http_build_query($args);
                    }
                }
                wp_redirect($url);
                exit();
                break;
            case 'iwbPaymentProcess':
                //iwbPaymentProcess();
                exit();
                break;
            case 'iwbCheckOrder':
                global $iwb_settings;
                $order_code = filter_input(INPUT_POST, 'ordercode');
                $email = filter_input(INPUT_POST, 'email');
                $url = get_permalink($iwb_settings['general']['check_order_page']);
                if (strpos($url, '?')) {
                    $url.='&ordercode=' . urlencode($order_code) . '&email=' . urlencode($email);
                } else {
                    $url.='?ordercode=' . urlencode($order_code) . '&email=' . urlencode($email);
                }
                wp_redirect($url);
                exit();
                break;
            case 'checkOrderAction':
                $formPost = filter_input_array(INPUT_POST);
                $order = new iwBookingOrder();
                $orderInfo = $order->getOrder($formPost['order']);
                if ($formPost['order_action'] == 'cancel_order') {
                    //$order->cancelOrder($formPost['order']);
                    iwBookingUtility::sendEmail($formPost['order'], '3');
                    $url = $_SERVER['HTTP_REFERER'];
                } else {
                    $url = $order->getPaypalUrl($orderInfo, 'full');
                }
                wp_redirect($url);
                exit();
                break;
            default:
                break;
        }
    }
}

function iwBookingAddSiteScript() {
    //wp_enqueue_style('font-awesome', plugins_url('/wp-booking-engine/assets/css/font-awesome/css/font-awesome.min.css'));
    //wp_enqueue_style('jquery-ui-custom', plugins_url('/wp-booking-engine/assets/css/jquery-ui-1.10.4.custom.min.css'));
    wp_enqueue_style('iwesite-style', plugins_url('/wp-booking-engine/assets/css/booking_style.css'));
    //wp_enqueue_style('owl-carousel', plugins_url('/wp-booking-engine/assets/css/owl.carousel.css'));
    //wp_enqueue_style('owl-theme', plugins_url('/wp-booking-engine/assets/css/owl.theme.css'));
    //wp_enqueue_style('owl-transitions', plugins_url('/wp-booking-engine/assets/css/owl.transitions.css'));
    global $iwb_settings;
    wp_register_script('iwbsite-script', plugins_url('/wp-booking-engine/assets/js/booking_script.js'), array('jquery'), '1.0.0', true);
    $iwb_objectL10n = array(
        'date_format' => isset($iwb_settings['general']['reservation_datepicker_format']) && $iwb_settings['general']['reservation_datepicker_format'] ?  $iwb_settings['general']['reservation_datepicker_format'] : 'd M yy',
        'closeText' => __('Done', 'inwavethemes'),
        'currentText' => __('Today', 'inwavethemes'),
        'nextText' => __('Next', 'inwavethemes'),
        'prevText' => __('Prev', 'inwavethemes'),
        'monthNames' => array(
            __('January', 'inwavethemes'),
            __('February', 'inwavethemes'),
            __('March', 'inwavethemes'),
            __('April', 'inwavethemes'),
            __('May', 'inwavethemes'),
            __('June', 'inwavethemes'),
            __('July', 'inwavethemes'),
            __('August', 'inwavethemes'),
            __('September', 'inwavethemes'),
            __('October', 'inwavethemes'),
            __('November', 'inwavethemes'),
            __('December', 'inwavethemes'),
        ),
        'monthNamesShort' => array(
            __('Jan', 'inwavethemes'),
            __('Feb', 'inwavethemes'),
            __('Mar', 'inwavethemes'),
            __('Apr', 'inwavethemes'),
            __('May', 'inwavethemes'),
            __('Jun', 'inwavethemes'),
            __('Jul', 'inwavethemes'),
            __('Aug', 'inwavethemes'),
            __('Sep', 'inwavethemes'),
            __('Oct', 'inwavethemes'),
            __('Nov', 'inwavethemes'),
            __('Dec', 'inwavethemes'),
        ),
        'monthStatus' => __('Show a different month', 'inwavethemes'),
        'dayNames' => array(
            __('Sunday', 'inwavethemes'),
            __('Monday', 'inwavethemes'),
            __('Tuesday', 'inwavethemes'),
            __('Wednesday', 'inwavethemes'),
            __('Thursday', 'inwavethemes'),
            __('Friday', 'inwavethemes'),
            __('Saturday', 'inwavethemes'),
        ),
        'dayNamesShort' => array(
            __('Sun', 'inwavethemes'),
            __('Mon', 'inwavethemes'),
            __('Tue', 'inwavethemes'),
            __('Wed', 'inwavethemes'),
            __('Thu', 'inwavethemes'),
            __('Fri', 'inwavethemes'),
            __('Sat', 'inwavethemes'),
        ),
        'dayNamesMin' => array(
            __('S', 'inwavethemes'),
            __('M', 'inwavethemes'),
            __('T', 'inwavethemes'),
            __('W', 'inwavethemes'),
            __('T', 'inwavethemes'),
            __('F', 'inwavethemes'),
            __('S', 'inwavethemes'),
        ),
    );
    wp_localize_script('iwbsite-script', 'iwb_objectL10n', $iwb_objectL10n);

    //wp_register_script('custombox', plugins_url('/iw_booking/assets/js/custombox.min.js'), array('jquery'), true);
    wp_localize_script('iwbsite-script', 'iwbCfg', array('siteUrl' => admin_url(), 'baseUrl' => site_url(), 'ajaxUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('iwbsite-script');
}

function iwb_block_filter_rooms_outhtml($atts) {
    extract(shortcode_atts(array(
        'guest' => '01 02 03 04',
        'text_action' => '',
        'title' => '',
        'class' => '',
                    ), $atts));

    $guests = explode(' ', $guest);

    $checkin = isset($_REQUEST['checkin']) ? intval($_REQUEST['checkin']) : time();
    $checkout = isset($_REQUEST['checkout']) ? intval($_REQUEST['checkout']) : (time() + 86400);
    $adult = isset($_REQUEST['adult']) ? $_REQUEST['adult'] : '01';

    ob_start();
    $path = includeTemplateFile('wp-booking-engine/check_availability_block', IWBOOKING_THEME_PATH);
    if ($path) {
        include $path;
    } else {
        _e('No template found', 'inwavethemes');
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function iwb_booking_page_filter_outhtml($atts) {
    global $iwb_settings;

    extract(shortcode_atts(array(
        "limit" => "",
        "class" => ""
                    ), $atts));
    $checkin = isset($_REQUEST['checkin']) ? intval($_REQUEST['checkin']) : time();
    $checkout = isset($_REQUEST['checkout']) ? intval($_REQUEST['checkout']) : (time() + 86400);
    $adult = isset($_REQUEST['adult']) ? intval($_REQUEST['adult']) : 1;
    $children = isset($_REQUEST['children']) ? intval($_REQUEST['children']) : 0;
    $filter_room = isset($_REQUEST['filter_room']) ? intval($_REQUEST['filter_room']) : 0;
    ob_start();
    //$path = includeTemplateFile('iw_booking/booking_page', IWBOOKING_THEME_PATH);
    $path = includeTemplateFile('wp-booking-engine/reservation_page', IWBOOKING_THEME_PATH);
    if ($path) {
        include $path;
    } else {
        _e('No template found', 'inwavethemes');
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function iwb_booking_rooms(){
    parse_str($_POST['data'], $query_params);
    $return = array();
    $return['state'] = (int)$_POST['state'];
    $filter_room = isset($query_params['filter-room']) ? $query_params['filter-room'] : '';
    $query_params['room-id'] = isset($query_params['room-id']) ? $query_params['room-id'] : array();
    $query_params['room-service'] = isset($query_params['room-service']) ? $query_params['room-service'] : array();
    $total_room = $query_params['room'];
    $total_room_selected = count(array_filter($query_params['room-id']));
    $current_room_id = $total_room_selected == 0 ? 1 : ($total_room_selected < $total_room ? ($total_room_selected + 1) : 0);
    $checkin = $query_params['checkin'];
    $checkout = $query_params['checkout'];
    $night = $query_params['night'];
    $adult = isset($query_params['room-id']) ? $query_params['adult-number'][$total_room_selected] : $query_params['adult-number'][0];
    $children = isset($query_params['room-id']) ? $query_params['children-number'][$total_room_selected] : $query_params['children-number'][0];
    $room_class = new IwBookingRooms();
    $paged = (isset($_POST['paged']) ? $_POST['paged'] : 1);

    if($return['state'] == 2){
        if($total_room_selected < $total_room){
            $path = includeTemplateFile('wp-booking-engine/reservation_page_rooms', IWBOOKING_THEME_PATH);
            ob_start();
            if($filter_room){
                $rooms = $room_class->getAvailableRooms(strtotime($checkin), strtotime($checkout), $adult, $children, $query_params['room-id'], 1, $filter_room);
                include $path;
                $rooms = $room_class->getAvailableRooms(strtotime($checkin), strtotime($checkout), $adult, $children, $query_params['room-id'], $paged, 0, array($filter_room));
                if($rooms->have_posts()){
                    echo '<h4>'.__('Can you want', 'monalisa').'</h4>';
                    include $path;
                }
            }
            else{
                $rooms = $room_class->getAvailableRooms(strtotime($checkin), strtotime($checkout), $adult, $children, $query_params['room-id'], $paged, $filter_room);
                include $path;
            }
            $return['content'] = ob_get_contents();
            ob_end_clean();

        }
        else
        {
            $path = includeTemplateFile('wp-booking-engine/reservation_page_coupon', IWBOOKING_THEME_PATH);
            ob_start();
            include $path;
            $return['content'] = ob_get_contents();
            ob_end_clean();
        }
        $path = includeTemplateFile('wp-booking-engine/reservation_page_bar', IWBOOKING_THEME_PATH);
        ob_start();
        include $path;
        $return['current_room_id'] = $current_room_id;
        $return['room_form'] = ob_get_contents();
        ob_end_clean();
    }
    elseif($return['state'] == 3){
        global $iwb_settings;

        $total_price = 0;
        $deposit_price = 0;
        $coupon_code = (isset($_POST['coupon_code']) && $_POST['coupon_code']) ? $_POST['coupon_code'] : '';
        $discount_id = '';
        $discount_price = 0;
        $tax = 0;
        $tax_price = 0;

        $room_class = new IwBookingRooms();
        $_room_data = array();
        foreach ($query_params['room-id'] as $i => $room_id){
            $room_info = $room_class->getRoomInfo($room_id);
            if($room_info){
                $_room_data[$i]['room_id'] = $room_id;
                $_room_data[$i]['adult'] = $query_params['adult-number'][$i];
                $_room_data[$i]['children'] = $query_params['children-number'][$i];
                $room_price = $room_info->price * $night;
                $_room_data[$i]['price'] = $room_price;
                if($query_params['room-service'][$i]){
                    $services = explode(',', $query_params['room-service'][$i]);
                    foreach ($services as $service) {
                        if (isset($room_info->premium_services[$service])) {
                            $service_price = $room_info->premium_services[$service]->getRate() ? $room_info->premium_services[$service]->getPrice() : ($room_info->premium_services[$service]->getPrice() * $night);

                            $room_price = $room_price + $service_price;
                            $room_service = array('name'=> $service, 'title' => $room_info->premium_services[$service]->getName(), 'price' => $service_price);
                            $_room_data[$i]['services'][] = $room_service;
                        }
                    }
                }

                $room_deposit_price = $room_info->deposit ? ($room_info->deposit * $room_price / 100 * $night) : 0;
                $_room_data[$i]['price_with_service'] = $room_price;

                $total_price = $total_price + $room_price;
                $deposit_price = $deposit_price + $room_deposit_price;
            }
        }
        $price = $total_price;

        if($total_price && $coupon_code){
            $discount = new iwBookingDiscount();
            $apply = unserialize($discount->applyDiscountCode($coupon_code));
            if ($apply['success']) {
                $discount_id = (int)$apply['id'];
                $discount_price = $discount->getDiscountPrice($total_price);
                $price = $discount->getPriceAfterDiscount($price);
                $deposit_price = $discount->getPriceAfterDiscount($deposit_price);
            }
            else{
                $return['error_message'] = $apply['message'];
            }
        }

        if($return['error_message']){
            $return['state'] = 2;

            $path = includeTemplateFile('wp-booking-engine/reservation_page_counpon', IWBOOKING_THEME_PATH);
            ob_start();
            include $path;
            $return['content'] = ob_get_contents();
            ob_end_clean();

            $path = includeTemplateFile('wp-booking-engine/reservation_page_bar', IWBOOKING_THEME_PATH);
            ob_start();
            include $path;
            $return['room_form'] = ob_get_contents();
            ob_end_clean();

            echo json_encode($return);exit;
        }

        if(isset($iwb_settings['general']['tax']) && $iwb_settings['general']['tax']){
            $tax = abs($iwb_settings['general']['tax']);
            $tax_price = $price * $tax /100;
            $price = $price + $tax_price;
            $deposit_price = $deposit_price + ($deposit_price * $tax / 100);
        }

        $booking_info_data = array();
        $booking_info_data['room-id'] = $query_params['room-id'];
        $booking_info_data['room-service'] = $query_params['room-service'];
        $booking_info_data['adult-number'] = $query_params['adult-number'];
        $booking_info_data['child-number'] = $query_params['adult-number'];
        $booking_info_data['total_price'] = round($total_price, 2);
        $booking_info_data['price'] = round($price, 2);
        $booking_info_data['discount_price'] = round($discount_price, 2);
        $booking_info_data['discount_id'] = $discount_id;
        $booking_info_data['deposit_price'] = round($deposit_price, 2);
        $booking_info_data['tax'] = $tax;
        $booking_info_data['tax_price'] = round($tax_price, 2);
        $booking_info_data['currency'] = $iwb_settings['general']['currency'];
        $booking_info_data['time_start'] = strtotime($checkin);
        $booking_info_data['time_end'] = strtotime($checkout);

        $get_content = false;
        $get_sidebar = false;
        if(isset($_POST['contact'])){
            parse_str($_POST['contact'], $contact_data);
            $message = '';
            $member_data = iwBookingUtility::getMemberFieldValue($contact_data, $message);

	        iwBookingUtility::getGuestNames($contact_data['guests'], array_sum($booking_info_data['adult-number']));
			// TODO guest names
	        //$message = (count($contact_data['guests']) + 1) . ' ' .  array_sum($booking_info_data['adult-number']);
            if($message){
                $return['error_message'] = $message;
            }else{
                $customer = new iwBookingCustomer();
                $customer_id = $customer->addOrUpdateCustomer($member_data);
                $customer->setCurrentCustomer($customer->getCustomer($customer_id));
                if($booking_info_data) {
                    $booking = new iwBookingOrder();
                    $booking_data = array();
                    $booking_data['price'] = $booking_info_data['price'];
                    $booking_data['sum_price'] = $booking_info_data['total_price'];
                    $booking_data['discount_price'] = $booking_info_data['discount_price'];
                    $booking_data['discount_id'] = $booking_info_data['discount_id'];
                    $booking_data['deposit'] = $booking_info_data['deposit_price'];
                    $booking_data['currency'] = $booking_info_data['currency'];
                    $booking_data['booking_code'] =  $booking->createBookingCode();
                    $booking_data['time_start'] = $booking_info_data['time_start'];
                    $booking_data['time_end'] = $booking_info_data['time_end'];
                    $booking_data['tax'] = $booking_info_data['tax'];
                    $booking_data['tax_price'] = $booking_info_data['tax_price'];
                    $booking_data['time_created'] = time();
                    $booking_data['last_update'] = '';
                    $booking_data['status'] = '1';
                    $booking_data['customer_id'] = $customer_id;
                    $booking_data['note'] = $contact_data['note'];
                    $booking_data['guests'] = $contact_data['guests'] ? serialize($contact_data['guests']) : '';

                    $booking_data['rooms'] = $_room_data;
                    $booking_id = $booking->addOrder($booking_data);
                    if ($booking_id) {
                        //var_dump($contact_data['payment_method']);exit;
                        $email_data = array_merge($member_data, $booking_data);
                        $email_data['booking_id'] = $booking_id;
                        iwBookingUtility::sendEmail($booking_id, 'order_created');

                        if ($contact_data['payment_method'] == 'deposit' || $contact_data['payment_method'] == 'full') {
                            //create paypal request
                            $booking->getOrder($booking_id);
                            $payment_url = $booking->getPaypalUrl($booking, $contact_data['payment_method']);
                            $return['paypal_url'] = $payment_url;
                            $get_content = true;
                        }
                        else{
                            $get_content = true;
                            $return['state'] = 4;
                            $path = includeTemplateFile('wp-booking-engine/reservation_page_completed', IWBOOKING_THEME_PATH);
                            ob_start();
                            include $path;
                            $return['content'] = ob_get_contents();
                            ob_end_clean();

                            $page_price_data = $booking_info_data;
                            $page_price_data['rooms'] = $_room_data;
                            $path = includeTemplateFile('wp-booking-engine/reservation_page_bar_summary', IWBOOKING_THEME_PATH);
                            ob_start();
                            include $path;
                            $return['summary_form'] = ob_get_contents();
                            $get_sidebar = true;
                        }
                    }
                }
                else
                {
                    $return['error_message'] = __('Your order has expired please choose the room', 'inwavethemes');
                }
            }
        }

        if(!$get_content){
            if(!isset($contact_data)){
                $contact_data = iwBookingUtility::getContactDataDefault();
            }
            $path = includeTemplateFile('wp-booking-engine/reservation_page_contact_form', IWBOOKING_THEME_PATH);
            ob_start();
            include $path;
            $return['content'] = ob_get_contents();
            ob_end_clean();
        }

        if(!$get_sidebar){
            $page_price_data = $booking_info_data;
            $page_price_data['rooms'] = $_room_data;
            $path = includeTemplateFile('wp-booking-engine/reservation_page_bar_summary', IWBOOKING_THEME_PATH);
            ob_start();
            include $path;
            $return['summary_form'] = ob_get_contents();
            $get_sidebar = true;
        }

        ob_end_clean();
    }

    echo json_encode($return);
    exit;
}

if (!class_exists('includeTemplateFile')) {

    function includeTemplateFile($name, $default_path) {
        $parent_path = get_template_directory();
        $path = $parent_path . '/' . $name . '.php';
        if (get_stylesheet_directory() != get_template_directory()) {
            //Theme child active
            $child_path = get_stylesheet_directory();
            $file_path = $child_path . '/' . $name . '.php';
            if (file_exists($file_path)) {
                $path = $file_path;
            }
        }

        if (!file_exists($path)) {
            $file_arr = explode('/', $name);
            $file_name = end($file_arr);
            $inf_theme = $default_path . $file_name . '.php';
            if (file_exists($inf_theme)) {
                $path = $inf_theme;
            } else {
                $path = false;
            }
        }
        return $path;
    }

}

function iwb_booking_list_rooms($atts) {
    extract(shortcode_atts(array(
        "category" => "0",
        "ids" => '',
        "order_by" => "date",
        "order_dir" => "desc",
        "time_start" => time(),
        "time_end" => time() + 259200,
        "limit" => 12,
        "style" => 'slider',
        "title" => '',
        "sub_title" => '',
        "class" => ""
                    ), $atts));

    ob_start();
    $iw_room = new iwBookingRooms();

    $query = $iw_room->getRoomList($category, $ids, $order_by, $order_dir, $limit);

    $path = includeTemplateFile('wp-booking-engine/iw_booking_rooms_' . $style, IWBOOKING_THEME_PATH);
    if ($path) {
        include $path;
    } else {
        echo __('No theme found', 'inwavethemes');
    }
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

function iwb_page_content($content) {
    global $post, $iwb_settings;
    ob_start();
    if ($post) {
        if (isset($iwb_settings['general']['check_order_page']) && $post->ID == $iwb_settings['general']['check_order_page']) {
            $path = includeTemplateFile('wp-booking-engine/check_order_page', IWBOOKING_THEME_PATH);
            if ($path) {
                include $path;
            } else {
                echo __('No theme found', 'inwavethemes');
            }
        }
        if (isset($iwb_settings['general']['booking_page']) &&  $post->ID == $iwb_settings['general']['booking_page']) {
            //echo do_shortcode('[iwb_payment_page]');
        }
    }
    $html = ob_get_contents();
    ob_end_clean();
    if ($html) {
        $content = $html;
    }
    return $content;
}
