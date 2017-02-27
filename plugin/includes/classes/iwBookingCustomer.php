<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created May 19, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingCustomer
 *
 * @developer duongca
 */
class iwBookingCustomer {

    public $id;
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $field_value;

    function __construct($id = null)
    {
        $this->getCustomer($id);
    }

    public function addCustomer($data) {
        global $wpdb;
        $ins = $wpdb->insert($wpdb->prefix . "iwb_customer", $data);
        if($ins){
            return $wpdb->insert_id;
        }
        return false;
    }

    public function updateCustomer($data) {
        global $wpdb;

        $update = $wpdb->update($wpdb->prefix . "iwb_customer", $data, array('id' => $this->id));
        return $update;
    }

    public function addOrUpdateCustomer($data) {
        global $wpdb;
        $member_id = 0;
//        if(isset($data['user_id']) && $data['user_id']){
//            $member_id = $wpdb->get_var($wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'iwb_customer WHERE user_id = %d', $data['user_id']));
//        }
//        elseif(isset($data['email'])){
//            $member_id = $wpdb->get_var($wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'iwb_customer WHERE email = %s', $data['email']));
//        }

        if($member_id){
            $this->getCustomer($member_id);
            $this->updateCustomer($data);
        }
        else{
            $member_id = $this->addCustomer($data);
        }

        return $member_id;
    }

    /*public function getCustomerByUser($curent_user) {
        global $wpdb;
        $member = new iwBookingCustomer();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_customer WHERE user_id=%d', $curent_user));
        if ($row) {
            $member->setId($row->id);
            $member->setUser_id($row->user_id);
            $member->setField_value(unserialize($row->field_value));
        }
        return $member;
    }*/

    public function getCustomers($start, $limit = 20, $keyword = '') {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_customer WHERE field_value LIKE %s LIMIT %d,%d', '%' . $keyword . '%', $start, $limit));
        return $rows;
    }
    /*public function getCustomer($user) {
            global $wpdb;
            $member = new iwBookingCustomer();
            $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_customer WHERE user_id=%d', $curent_user));
            if ($row) {
                $member->setId($row->id);
                $member->setUser_id($row->user_id);
                $member->setField_value(unserialize($row->field_value));
            }
            return $member;
    }*/
    public function getCustomer($id = null) {
        global $wpdb;
        $row = '';
        if($id && is_numeric($id)){
            $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_customer WHERE id=%d', $id));
        }elseif($id){
            $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_customer WHERE email=%s', $id));
        }

        if ($row) {
            foreach ($row as $key => $value){
                if($key == 'field_value'){
                    $this->field_value = unserialize($value);
                    foreach ($this->field_value as $fkey => $fvalue){
                        $this->$fkey = $fvalue;
                    }
                }
                else{
                    $this->$key = $value;
                }
            }
        }
        elseif(is_user_logged_in())
        {
            $user_info = get_userdata($id);
            if($user_info){
                $this->first_name = $user_info->first_name;
                $this->last_name = $user_info->last_name;
                $this->email = $user_info->user_email;
                $this->user_id = $id;
            }
        }

        return $this;
    }

    public static function getCurrentCustomer(){
        if(isset($_SESSION['iwb_current_customer'])){
            return $_SESSION['iwb_current_customer'];
        }
        else{
            $customer = new iwBookingCustomer();
            $_SESSION['iwb_current_customer'] = $customer->getCustomer();
            return $_SESSION['iwb_current_customer'];
        }
    }

    public static function setCurrentCustomer($member){
        $_SESSION['iwb_current_customer'] = $member;
        return false;
    }

    /**
     * Function to delete single member
     * @global type $wpdb
     * @param type $id member id
     * @return string serialize data of result
     */
    public function deleteCustomer($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_orders WHERE member_id = %d', $id)
        );
        $msg = '';
        if (!empty($check)) {
            $msg = sprintf(__('Can\'t remove Customer with id: <strong>%s</strong>. Because:', 'inwavethemes'), $id);
            if (!empty($check)) {
                $msg .= '<br/> - ' . __('It used in some orders', 'inwavethemes');
            }
        }
        if ($msg) {
            $return['msg'] = $msg;
        } else {
            $del = $wpdb->delete($wpdb->prefix . 'iwb_customer', array('id' => $id));
            if ($del) {
                $return['success'] = true;
                $return['msg'] = __('Customer has been deleted', 'inwavethemes');
            } else {
                $return['msg'] = $wpdb->last_error;
            }
        }
        return serialize($return);
    }

    /**
     * Function to delete multiple sponsor
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteCustomers($ids) {
        global $wpdb;

        $msg = array();
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_customer WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = __('Delete customers successfully', 'inwavethemes');
        }
        return $msg;
    }

    public function getCountCustomer() {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'iwb_customer');
    }
}
