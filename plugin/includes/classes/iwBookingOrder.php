<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Aug 4, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingOrder
 *
 * @developer duongca
 */
class iwBookingOrder {

    public $id;
    public $booking_code;
    public $member;
    public $discount;
    public $rooms;
    public $time_start;
    public $time_end;
    public $time_created;
    public $last_update;
    public $adults;
    public $childrens;
    public $paid;
    public $deposit;
    public $sum_price;
    public $discount_price;
    public $price;
    public $currency;
    public $note;
    public $guests;
    public $status;
    public $payment_method;
    public $readed;

    function __construct($order = null)
    {
        $this->getOrder($order);
    }

    /**
     * @return mixed
     */
    function getPrice() {
        return $this->price;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function getBooking_code() {
        return $this->booking_code;
    }

    function setBooking_code($booking_code) {
        $this->booking_code = $booking_code;
    }

    public function getReaded() {
        return $this->readed;
    }

    function getDeposit() {
        return $this->deposit;
    }

    function setDeposit($deposit) {
        $this->deposit = $deposit;
    }

    /**
     * @param mixed $readed
     */
    public function setReaded($readed) {
        $this->readed = $readed;
    }

    function getId() {
        return $this->id;
    }

    function getMember($force = false) {
        if($force || (!$this->member && $this->customer_id)){
            $this->member = new iwBookingCustomer($this->customer_id);
            //$this->member->getCustomer();
        }

        return $this->member;
    }

    function getDiscount() {
        if(!$this->discount && $this->discount_id){
            $this->discount = new iwBookingDiscount();
            $this->discount->getDiscount($this->discount_id);
        }

        return $this->discount;
    }

    function getTime_start() {
        return $this->time_start;
    }

    function getTime_end() {
        return $this->time_end;
    }

    function getTime_created() {
        return $this->time_created;
    }

    function getLast_update() {
        return $this->last_update;
    }

    function getAdults() {
        return $this->adults;
    }

    function getChildrens() {
        return $this->childrens;
    }

    function getPaid() {
        return $this->paid;
    }

    function getSum_price() {
        return $this->sum_price;
    }

    function getDiscount_price() {
        return $this->discount_price;
    }

    function getCurrency() {
        return $this->currency;
    }

    function getNote() {
        return $this->note;
    }

    function getGuests() {
        if($this->guests){
            return unserialize($this->guests);
        }
        else{
            return array();
        }
    }

    function getStatus() {
        return $this->status;
    }

    function getPayment_method() {
        return $this->payment_method;
    }

    function getPayment_method_text() {

        switch ($this->payment_method){
            case 0:
                $returnString = __('Bank transfer', 'inwavethemes');
                break;
            case 1:
                $returnString = __('Paypal', 'inwavethemes');
                break;
        }

        return $returnString;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setMember($member) {
        $this->member = $member;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }

    function setServices($services) {
        $this->services = $services;
    }

    function setRooms($rooms) {
        $this->rooms = $rooms;
    }

    function setTime_start($time_start) {
        $this->time_start = $time_start;
    }

    function setTime_end($time_end) {
        $this->time_end = $time_end;
    }

    function setTime_created($time_created) {
        $this->time_created = $time_created;
    }

    function setLast_update($last_update) {
        $this->last_update = $last_update;
    }

    function setAdults($adults) {
        $this->adults = $adults;
    }

    function setChildrens($childrens) {
        $this->childrens = $childrens;
    }

    function setPaid($paid) {
        $this->paid = $paid;
    }

    function setSum_price($sum_price) {
        $this->sum_price = $sum_price;
    }

    function setDiscount_price($discount_price) {
        $this->discount_price = $discount_price;
    }

    function setCurrency($currency) {
        $this->currency = $currency;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setGuests($guests) {
        $this->guests = $guests;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setPayment_method($payment_method) {
        $this->payment_method = $payment_method;
    }

    function getOrder($data) {
        if(is_numeric($data)){
            global $wpdb;
            $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_bookings WHERE id=%d', $data));
            if($row){
                foreach ($row as $key => $value){
                    $this->$key = $value;
                }
            }
        }
        elseif(is_object($data) && isset($data->id) && $data->id){
            foreach ($data as $key => $value){
                $this->$key = $value;
            }
        }

        return $this;
    }


    function getOrderByOnlyCode($data) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_bookings WHERE booking_code=%d', $data));
        if($row){
            foreach ($row as $key => $value){
                $this->$key = $value;
            }
        }

        return $this;
    }

    function getOrders($atts) {
        global $wpdb;
        $default_attr = array(
            'start' => 0,
            'limit' => 20,
            'ordering' => 'time_created',
            'ordering_dir' => 'DESC',
            'keyword' => '',
            'status' => '',
            'bookingcode' => '',
        );

        extract(shortcode_atts($default_attr, $atts));
        $filter = '';

        if ($bookingcode) {
            if ($filter) {
                $filter .= ' AND o.booking_code LIKE \'' . $bookingcode .'\'';
            } else {
                $filter .= ' o.booking_code LIKE \'' . $bookingcode . '\'';
            }
        }

        if ($status) {
            if ($filter) {
                $filter .= ' AND o.status=' . $status;
            } else {
                $filter .= ' o.status=' . $status;
            }
        }
        if ($keyword) {
            if ($filter) {
                $filter .= ' AND (m.first_name LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.last_name LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.email LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.phone LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.address LIKE \'%' . htmlspecialchars($keyword) . '%\')';
            } else {
                $filter .= ' (m.first_name LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.last_name LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.email LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.phone LIKE \'%' . htmlspecialchars($keyword) . '%\' OR m.address LIKE \'%' . htmlspecialchars($keyword) . '%\')';
            }
        }

        $rows = $wpdb->get_results('SELECT o.* FROM ' . $wpdb->prefix . 'iwb_bookings AS o LEFT JOIN ' . $wpdb->prefix . 'iwb_customer as m ON o.customer_id = m.id '.($filter ? ' WHERE ' . $filter : '').' ORDER BY o.' . $ordering . ' ' . $ordering_dir . ' LIMIT '.$start.','. $limit);

        return $rows;
    }

    function getOrderCode() {
        return $this->booking_code;
    }

    public function addOrder($data) {
        global $wpdb;
        $rooms = $data['rooms'];
        unset($data['rooms']);
        $insert = $wpdb->insert($wpdb->prefix . "iwb_bookings", $data);
        if ($insert) {
            $this->id = $wpdb->insert_id;
            $this->addRooms($rooms);
            return $this->id;
        }

        return false;
    }

    public function getLink($id, $text = '') {
        return admin_url('edit.php?post_type=iw_booking&page=bookings/view&id=' . $id) . ($text ? ('|' . $text) : '');
    }

    public function clearExpiredOrder($time_to_clear) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT id from ' . $wpdb->prefix . 'iwb_bookings WHERE status=1 AND time_created < %d', time() - $time_to_clear));
        if($rows){
            foreach ($rows as $row){
                $order = new iwBookingOrder($row->id);
                $order->updateStatus(3);
                iwBookingUtility::sendEmail($order, '3');
            }
        }
        return $rows;
    }

    /**
     * Function to delete single sponsor
     *
     * @global type $wpdb
     * @param type $id sponsor id
     * @return string serialize data of result
     */
    public function deleteOrder($id) {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'iwb_bookings', array('id' => $id));
        $wpdb->delete($wpdb->prefix . 'iwb_booking_room_rf', array('booking_id' => $id));
        $wpdb->delete($wpdb->prefix . 'iwb_booking_service_rf', array('booking_id' => $id));
    }

    /**
     * Function to delete multiple sponsor
     *
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteOrders($ids) {
        global $wpdb;
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_bookings WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_booking_room_rf WHERE booking_id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_booking_service_rf WHERE booking_id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
        }
    }

    public function getCountOrder($atts) {
        global $wpdb;
        $default_attr = array(
            'start' => 0,
            'limit' => 20,
            'ordering' => 'time_created',
            'ordering_dir' => 'DESC',
            'keyword' => '',
            'status' => '',
        );

        extract(shortcode_atts($default_attr, $atts));
        $count = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'iwb_bookings WHERE (note LIKE \'%' . htmlspecialchars($keyword) . '%\') '. ($status? 'AND status='.$status:''));

        return $count;
    }

    public function addRooms($rooms) {
        global $wpdb;
        $rfids = array();
        foreach ($rooms as $room) {
            $room_data = (array)$room;
            $room_data['booking_id'] = $this->id;
            $room_data['services'] = $room['services'] ? serialize($room['services']) : '';
            $room_data['amount'] = 1;
            $rfid = $wpdb->insert($wpdb->prefix . "iwb_booking_room_rf", $room_data);
            if($rfid){
                $rfids[] = $rfid;
            }
        }

        return $rfids;
    }

    public function getRooms() {
        if(!$this->rooms){
            global $wpdb;
            $rs = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_booking_room_rf WHERE booking_id=%d', $this->id), ARRAY_A);
            if (!empty($rs)) {
                foreach ($rs as $key => $value) {
                    if($rs[$key]['services']){
                        $rs[$key]['services'] = unserialize($rs[$key]['services']);
                    }
                    else{
                        $rs[$key]['services'] = array();
                    }
                }
            }
            $this->rooms = $rs;
        }

        return $this->rooms;
    }

    public function getNewOrders() {
        global $wpdb;
        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_bookings WHERE readed = 0');
        return $rows;
    }

    public function changeReaded($readed, $id) {
        global $wpdb;
        $wpdb->update($wpdb->prefix . 'iwb_bookings', array('readed' => $readed), array('id' => $id));
    }

    public function removeDraftBookings($time) {
        global $wpdb;
        $drafts = $wpdb->get_col($wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'iwb_bookings WHERE status=5 AND time_created < %d', time() - $time));
        if (!empty($drafts)) {
            foreach ($drafts as $d_id) {
                $this->deleteOrder($d_id);
            }
        }
    }

    public function getRoomServices($room_id, $booking_id) {
        global $wpdb;
        $services = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT service_id FROM ' . $wpdb->prefix . 'iwb_room_service_rf WHERE room_id=%d AND booking_id=%d', $room_id, $booking_id));
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $service = new iwBookingService();
                $services[] = $service->getService($row->service_id);
            }
        }
        return $services;
    }

    public function getCountExpiredTime($order) {
        global $iwb_settings;
        $str = '';
        if (is_numeric($order)) {
            $order = $this->getOrder($order);
        }
        if ($order->getDeposit() > 0 && $order->getPaid() == 0) {
            $expire_time = ($iwb_settings['general']['schedule_time'] * 3600) + $order->getTime_created();
            $times = $expire_time - time();
            $hours = $times / 3600;

            if ($hours > 24) {
                $str = sprintf(_n('Your order has not paid yet! Your booking will be automatically canceled in %s day!', 'Your order has not paid yet! Your booking will be automatically canceled in %s days!', floor($hours/24), 'inwavethemes'), floor($hours/24));
            } else {
                $str = sprintf(_n('Your order has not paid yet! Your booking will be automatically canceled in %s hour!', 'Your order has not paid yet! Your booking will be automatically canceled in %s hours!', ceil($hours), 'inwavethemes'), ceil($hours));
            }
        } else {
            $str = esc_attr__('Your order has not paid yet! You can payment now or cancel your booking here!', 'inwavethemes');
        }
        return $str;
    }

    public function cancelOrder($order_id) {
        global $wpdb;
        $wpdb->update($wpdb->prefix . "iwb_bookings", array('status' => 3), array('id' => $order_id));
    }

    public function updateStatus($status){
        global $wpdb;
        return $wpdb->update($wpdb->prefix . "iwb_bookings", array('status' => $status), array('id' => $this->id));
    }
    public function updatePaid($paid){
        global $wpdb;
        return $wpdb->update($wpdb->prefix . "iwb_bookings", array('paid' => $paid), array('id' => $this->id));
    }

    public function getPaypalUrl($booking, $method = 'deposit') {
        global $iwb_settings;
        $paypal = $iwb_settings['iwb_payment']['paypal'];
        $general = $iwb_settings['general'];
        if ($paypal['test_mode']) {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $url = 'https://www.paypal.com/cgi-bin/webscr';
        }

        $return_url = get_permalink($general['booking_page']);
        $return_url = add_query_arg('state', '4', $return_url);
        $return_url = add_query_arg('invoice', $booking->getId(), $return_url);
        $params = array(
            'cmd' => '_xclick',
            'business' => $paypal['email'],
            'item_name' => __('Booking Villa-Rini', 'inwavethemes'),
            'currency_code' => $iwb_settings['general']['currency'],
            'quantity' => '1',
            'notify_url' => admin_url('admin-ajax.php?action=iwbPaymentNotice'),
            'return' => $return_url,
            'cancel_return' => site_url(),
            'amount' => $method == 'deposit' ? $booking->getDeposit() : $booking->getPrice(),
            'item_number' => $this->getBooking_code(),
            'custom' => $method
        );
        $query_string = http_build_query($params);
        $url .= '?' . $query_string;
        return $url;
    }

    function checkBookingCode($bookingCode) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_bookings WHERE booking_code=%s', $bookingCode));
        if (!empty($rows)) {
            return false;
        } else {
            return true;
        }
    }

    public function createBookingCode($prefix = 'B') {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomStringArr = [];
        for ($i = 0; $i < 2; $i++) {
            $randomString = '';
            for ($j = 0; $j < 3; $j++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $randomStringArr[] = $randomString;
        }

        $code = implode('-', $randomStringArr);

        if ($this->checkBookingCode($code)) {
            return $code;
        } else {
            $this->createBookingCode($prefix);
        }
    }

    public function getOrderByCode($ord_code, $email) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT o.*, m.id AS mid, m.user_id AS muid, m.field_value AS mfield_value FROM ' . $wpdb->prefix . 'iwb_bookings AS o INNER JOIN ' . $wpdb->prefix . 'iwb_customer as m ON o.customer_id = m.id  WHERE o.booking_code=%s AND m.email = %s', $ord_code, $email));
        if ($row) {
            foreach ($row as $key => $value){
                $this->$key = $value;
            }
        }

        return $this;
    }


    public function updateOrder($data) {
        global $wpdb;
        $update = $wpdb->update($wpdb->prefix . "iwb_bookings", $data, array('id' => $this->id));
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
}
