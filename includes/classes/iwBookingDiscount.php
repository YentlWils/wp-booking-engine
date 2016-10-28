<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Aug 3, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingDiscount
 *
 * @developer duongca
 */
class iwBookingDiscount {

    public $id;
    public $name;
    public $discount_code;
    public $type;
    public $value;
    public $time_start;
    public $time_end;
    public $amount;
    public $description;
    public $status;

    function __construct($id = null){
        if($id){
            if(is_numeric($id)){
                $this->getDiscount($id);
            }
            else{
                $this->getDiscountByCode($id);
            }
        }
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getValue() {
        return $this->value;
    }

    function getTime_start() {
        return $this->time_start;
    }

    function getTime_end() {
        return $this->time_end;
    }

    function getAmount() {
        return $this->amount;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setTime_start($time_start) {
        $this->time_start = $time_start;
    }

    function setTime_end($time_end) {
        $this->time_end = $time_end;
    }

    function setAmount($amount) {
        $this->amount = $amount;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getDiscount_code() {
        return $this->discount_code;
    }

    function setDiscount_code($discount_code) {
        $this->discount_code = $discount_code;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    public function addDiscount($discount) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($discount);
        $ins = $wpdb->insert($wpdb->prefix . "iwb_discount", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editDiscount($discount) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($discount);
        unset($data['id']);
        foreach ($data as $key => $value) {
            if (!$value) {
                unset($data[$key]);
            }
        }
        $update = $wpdb->update($wpdb->prefix . "iwb_discount", $data, array('id' => $discount->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getDiscounts($start, $limit = 20) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_discount LIMIT %d,%d', $start, $limit));
        return $rows;
    }

    public function getDiscount($id) {
        global $wpdb;
        $discount = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_discount WHERE id=%d', $id));
        foreach ($discount as $key => $value){
            $this->$key = $value;
        }

        return $this;
    }

    public function getDiscountByCode($code) {
        global $wpdb;
        $discount = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_discount WHERE discount_code=%s', $code));
        foreach ($discount as $key => $value){
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Function to delete single Service
     * @global type $wpdb
     * @param type $id service id
     * @return string serialize data of result
     */
    public function deleteDiscount($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_bookings WHERE discount_id = %d', $id)
        );
        $msg = '';
        if (!empty($check)) {
            $msg = sprintf(__('Can\'t remove Discount with id: <strong>%s</strong>. Because:', 'inwavethemes'), $id);
            if (!empty($check)) {
                $msg .= '<br/> - ' . __('It used in some bookings', 'inwavethemes');
            }
        }
        if ($msg) {
            $return['msg'] = $msg;
        } else {
            $del = $wpdb->delete($wpdb->prefix . 'iwb_discount', array('id' => $id));
            if ($del) {
                $return['success'] = true;
                $return['msg'] = __('Discount has been deleted', 'inwavethemes');
            } else {
                $return['msg'] = $wpdb->last_error;
            }
        }
        return serialize($return);
    }

    /**
     * Function to delete multiple Service
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteDiscounts($ids) {
        global $wpdb;
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_booking_service_rf WHERE service_id IN(' . implode(',', wp_parse_id_list($ids)) . ') GROUP BY service_id');
        $ar = array();
        foreach ($result as $value) {
            $ar[] = $value->service_id;
        }
        foreach ($ids as $key => $id) {
            if (in_array($id, $ar)) {
                unset($ids[$key]);
            }
        }
        $msg = array();
        if (!empty($ar)) {
            $msg['error'] = sprintf(__('Can\'t delete Discount with id: <strong>%s</strong> because it used by some bookings.', 'inwavethemes'), implode(', ', $ar)) . '<br/>';
        }
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_discount WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete discount with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getCountDiscount() {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'iwb_discount');
    }

    public function applyDiscountCode($code) {
        $result = array('success' => false, 'message' => '', 'id' => '');
        $check = $this->getDiscountByCode($code);
        if ($check->getId()) {
            $time = time();
            if ($time < $check->getTime_start()) {
                $result['message'] = sprintf(__('The coupon will be valid since %s. Please wait and use it later', IW_TEXT_DOMAIN), date('m/d/Y', $check->time_start));
            } elseif ($time > $check->getTime_end()) {
                $result['message'] = sprintf(__('Sorry. The coupon is no longer valid since %s', IW_TEXT_DOMAIN), date('m/d/Y', $check->time_end));
            } else {
                if ($check->getAmount() <= 0) {
                    $result['message'] = __('Sorry, the coupon is out of stock because of limited amount of coupons.', IW_TEXT_DOMAIN);
                } else {
                    $check->setAmount($check->getAmount() - 1);
                    $this->editDiscount($check);
                    $result['message'] = __('Apply coupon code success.', IW_TEXT_DOMAIN);
                    $result['success'] = true;
                    $result['id'] = $check->getId();
                }
            }
        } else {
            $result['message'] = __('Invalid coupon code', IW_TEXT_DOMAIN);
        }
        return serialize($result);
    }

    public function getDiscountPrice($price) {
        if ($this->getType() == 'percent') {
            $percent = abs($this->getValue());
            $percent = $percent > 100 ? 100 : $percent;

            return $price * $percent / 100;
        }
        else
        {
            if($price < $this->getValue()){
                return $price;
            }
            else{
                return $this->getValue();
            }
        }
    }

    public static function getDiscountString($discount_price, $discount) {
        if(!$discount){
            return '';
        }
        
        if ($discount['type'] == 'percent') {
            return sprintf(__('(Discount %s%%)', 'inhotel'), $discount['value']);
        }
        else{
            return sprintf(__('(Discount %s)', 'inhotel'), iwBookingUtility::getMoneyFormated($discount_price));
        }
    }

    public function getPriceAfterDiscount($price) {
        $discount_price = $this->getDiscountPrice($price);
        return $price - $discount_price;
    }

}
