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
 * Description of iwBookingOffDays
 *
 * @developer duongca
 */
class iwBookingOffDay{

    public $id;
    public $time_start;
    public $time_end;
    public $note;

    public function __construct() {

    }
    
    function getId() {
        return $this->id;
    }
    

    function getTime_start() {
        return $this->time_start;
    }

    function getTime_end() {
        return $this->time_end;
    }

    function getNote() {
        return $this->note;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function setTime_start($time_start) {
        $this->time_start = $time_start;
    }

    function setTime_end($time_end) {
        $this->time_end = $time_end;
    }

    function setNote($note) {
        $this->note = $note;
    }

    public function addOffDay($offDay) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($offDay);
        $ins = $wpdb->insert($wpdb->prefix . "iwb_off_days", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editOffDay($offDay) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($offDay);
        unset($data['id']);
        $update = $wpdb->update($wpdb->prefix . "iwb_off_days", $data, array('id' => $offDay->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getOffDays($start = 0, $end = 0) {
        global $wpdb;
        $rs = array();

        if($start > 0 && $end > 0){
            $where = 'where (time_start >= ' .$start. ' AND time_start < '.$end.') OR (time_end > '.$start.' AND time_end <='.$end.')';
        }else{
            $where = '';
        }

        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_off_days' . $where);

        if (!empty($rows)) {
            foreach ($rows as $value) {
                $offDay = new iwBookingOffDay();
                $offDay->setId($value->id);
                $offDay->setTime_start($value->time_start);
                $offDay->setTime_end($value->time_end);
                $offDay->setNote($value->note);
                $rs[] = $offDay;
            }
        }
        return $rs;
    }

    public function getOffDay($id) {
        global $wpdb;
        $offDay = new iwBookingOffDay();
        $value = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_off_days WHERE id=%d', $id));
        if ($value) {
            $offDay->setId($value->id);
            $offDay->setTime_start($value->time_start);
            $offDay->setTime_end($value->time_end);
            $offDay->setNote($value->note);
        }
        return $offDay;
    }

    /**
     * Function to delete single Off Day
     * @global type $wpdb
     * @param type $id offday id
     * @return string serialize data of result
     */
    public function deleteOffDay($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);

        $del = $wpdb->delete($wpdb->prefix . 'iwb_off_days', array('id' => $id));
        if ($del) {
            $return['success'] = true;
            $return['msg'] = __('Off day has been deleted', 'inwavethemes');
        } else {
            $return['msg'] = $wpdb->last_error;
        }

        return serialize($return);
    }

    /**
     * Function to delete multiple Service
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteOffDays($ids) {
        global $wpdb;

        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_off_days WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete Off days with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getCountOffDays() {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'iwb_off_days ');
    }

}
