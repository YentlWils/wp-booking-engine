<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Jul 29, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingService
 *
 * @developer duongca
 */
class iwBookingService {

    private $id;
    private $name;
    private $price;
    private $type;
    private $description;
    private $status;

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPrice() {
        return $this->price;
    }

    function getDescription() {
        return $this->description;
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

    function setPrice($price) {
        $this->price = $price;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
    public function __construct() {
        
    }

    public function addService($service) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($service);
        $ins = $wpdb->insert($wpdb->prefix . "iwb_service", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editService($service) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($service);
        unset($data['id']);
        $update = $wpdb->update($wpdb->prefix . "iwb_service", $data, array('id' => $service->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getServices($start, $limit = 20, $status = 0, $type=null, $keyword='') {
        global $wpdb;
        $rs = array();
        $where = ' where 1=1';
        if ($status) {
            $where .= ' AND status=1';
        }
        if($type){
            $where .= ' AND type="'.$type.'"';
        }
        if($keyword){
            $where .= ' AND name LIKE \'%'.$keyword.'%\'';
        }
        if (!$start && !$limit) {
            $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_service' . $where);
        } else {
            $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_service' . $where . ' LIMIT '. $start.','. $limit);
        }
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $service = new iwBookingService();
                $service->setId($value->id);
                $service->setName($value->name);
                $service->setType($value->type);
                $service->setPrice($value->price);
                $service->setDescription($value->description);
                $service->setStatus($value->status);
                $rs[] = $service;
            }
        }
        return $rs;
    }

    public function getService($id) {
        global $wpdb;
        $service = new iwBookingService();
        $value = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_service WHERE id=%d', $id));
        if ($value) {
            $service->setId($value->id);
            $service->setName($value->name);
            $service->setPrice($value->price);
            $service->setType($value->type);
            $service->setDescription($value->description);
            $service->setStatus($value->status);
        }
        return $service;
    }

    /**
     * Function to delete single Service
     * @global type $wpdb
     * @param type $id service id
     * @return string serialize data of result
     */
    public function deleteService($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_booking_service_rf WHERE service_id = %d', $id)
        );
        $check2 = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_service_room_rf WHERE service_id = %d', $id)
        );
        $msg = '';
        if (!empty($check) || !empty($check2)) {
            $msg = sprintf(__('Can\'t remove Service with id: <strong>%s</strong>. Because:', 'inwavethemes'), $id);
            if (!empty($check)) {
                $msg .= '<br/> - ' . __('It used in some bookings', 'inwavethemes');
            }
            if (!empty($check2)) {
                $msg .= '<br/> - ' . __('It used in some room', 'inwavethemes');
            }
        }
        if ($msg) {
            $return['msg'] = $msg;
        } else {
            $del = $wpdb->delete($wpdb->prefix . 'iwb_service', array('id' => $id));
            if ($del) {
                $return['success'] = true;
                $return['msg'] = __('Service has been deleted', 'inwavethemes');
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
    public function deleteServices($ids) {
        global $wpdb;
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_booking_service_rf WHERE service_id IN(' . implode(',', wp_parse_id_list($ids)) . ') GROUP BY service_id');
        $result2 = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_service_room_rf WHERE service_id IN(' . implode(',', wp_parse_id_list($ids)) . ') GROUP BY service_id');
        $ar = array();
        foreach ($result as $value) {
            $ar[] = $value->service_id;
        }
        foreach ($result2 as $value) {
            if (!in_array($value->service_id, $ar)) {
                $ar[] = $value->service_id;
            }
        }
        foreach ($ids as $key => $id) {
            if (in_array($id, $ar)) {
                unset($ids[$key]);
            }
        }
        $msg = array();
        if (!empty($ar)) {
            $msg['error'] = sprintf(__('Can\'t delete Service with id: <strong>%s</strong> because it used by some bookings or rooms.', 'inwavethemes'), implode(', ', $ar)) . '<br/>';
        }
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_service WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete services with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getCountService($status=0, $type=null, $keyword='') {
        $where = ' where 1=1';
        if ($status) {
            $where .= ' AND status=1';
        }
        if($type){
            $where .= ' AND type="'.$type.'"';
        }
        if($keyword){
            $where .= ' AND name LIKE \'%'.$keyword.'%\'';
        }
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'iwb_service '.$where);
    }

}
