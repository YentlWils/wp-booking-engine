<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Jul 30, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingExtra
 *
 * @developer duongca
 */
class iwBookingExtra {

    private $id;
    private $name;
    private $icon;
    private $type;
    private $categories;
    private $default_value;
    private $description;
    private $ordering;
    private $published;

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getCategories() {
        return $this->categories;
    }

    function getDefault_value() {
        return $this->default_value;
    }

    function getDescription() {
        return $this->description;
    }

    function getOrdering() {
        return $this->ordering;
    }

    function getPublished() {
        return $this->published;
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

    function setCategories($categories) {
        $this->categories = $categories;
    }

    function setDefault_value($default_value) {
        $this->default_value = $default_value;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setOrdering($ordering) {
        $this->ordering = $ordering;
    }

    function setPublished($published) {
        $this->published = $published;
    }

    function getIcon() {
        return $this->icon;
    }

    function setIcon($icon) {
        $this->icon = $icon;
    }

    public function __construct() {
        
    }

    function addBookingExtra($bookingExtra) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($bookingExtra);
        unset($data['categories']);
        $ins = $wpdb->insert($wpdb->prefix . "iwb_extrafield", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
            $this->addExtrafieldCategory($bookingExtra->getCategories(), $wpdb->insert_id);
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    function addExtrafieldCategory($categories, $extra_id) {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . "iwb_extrafield_category", array('extrafield_id' => $extra_id));
        if ($categories[0] == '0' || $categories[0] == '') {
            $wpdb->insert($wpdb->prefix . "iwb_extrafield_category", array('category_id' => 0, 'extrafield_id' => $extra_id));
        } else {
            foreach ($categories as $value) {
                $wpdb->insert($wpdb->prefix . "iwb_extrafield_category", array('category_id' => $value, 'extrafield_id' => $extra_id));
            }
        }
    }

    function deleteExtrafieldCategory($extra_id) {
        global $wpdb;
        if (!is_array($extra_id)) {
            $wpdb->delete($wpdb->prefix . "iwb_extrafield_category", array('extrafield_id' => $extra_id));
        } else {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_extrafield_category WHERE extrafield_id IN(' . implode(',', wp_parse_id_list($extra_id)) . ')');
        }
    }

    function editBookingExtra($bookingExtra) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($bookingExtra);
        unset($data['categories']);
        unset($data['id']);
        $update = $wpdb->update($wpdb->prefix . "iwb_extrafield", $data, array('id' => $bookingExtra->getId()));
        if ($update || $update == 0) {
            $this->addExtrafieldCategory($bookingExtra->getCategories(), $bookingExtra->getId());
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    function deleteBookingExtra($booking_id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield_value WHERE extrafield_id = %d', $booking_id)
        );
        $msg = '';
        if (!empty($check)) {
            $msg = sprintf(__('Can\'t remove extrafield with id: <strong>%s</strong>. Because:', 'inwavethemes'), $booking_id);
            if (!empty($check)) {
                $msg .= '<br/> - ' . __('It used in some room', 'inwavethemes');
            }
        }
        if ($msg) {
            $return['msg'] = $msg;
        } else {
            $del = $wpdb->delete($wpdb->prefix . 'iwb_extrafield', array('id' => $booking_id));
            if ($del) {
                $this->deleteExtrafieldCategory($booking_id);
                $return['success'] = true;
                $return['msg'] = __('Extrafield has been deleted', 'inwavethemes');
            } else {
                if ($wpdb->last_error) {
                    $return['msg'] = $wpdb->last_error;
                } else {
                    $return['msg'] = sprintf(__('No extrafield with id: %d'), $booking_id);
                }
            }
        }
        return serialize($return);
    }

    public function deleteBookingExtras($ids) {
        global $wpdb;
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield_value WHERE extrafield_id IN(' . implode(',', wp_parse_id_list($ids)) . ') GROUP BY room_id');
        $ar = array();
        foreach ($result as $value) {
            $ar[] = $value->room_id;
        }
        foreach ($ids as $key => $id) {
            if (in_array($id, $ar)) {
                unset($ids[$key]);
            }
        }
        $msg = array();
        if (!empty($ar)) {
            $msg['error'] = sprintf(__('Can\'t delete extrafield with id: <strong>%s</strong> because it used by some room.', 'inwavethemes'), implode(', ', $ar)) . '<br/>';
        }
        if (!empty($ids)) {
            $this->deleteExtrafieldCategory($ids);
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'iwb_extrafield WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete Room extrafield with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getBookingExtras($start, $limit = 20, $keyword='') {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield WHERE name LIKE %s LIMIT %d,%d', '%'.$keyword.'%',$start, $limit));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $cat = new iwBookingCategory();
                $extra = new iwBookingExtra();
                $extra->setId($value->id);
                $extra->setName($value->name);
                $extra->setIcon($value->icon);
                $extra->setType($value->type);
                $extra->setDefault_value($value->default_value);
                $extra->setDescription($value->description);
                $extra->setCategories($cat->getExtrafieldCategory($value->id));
                $extra->setOrdering($value->ordering);
                $extra->setPublished($value->published);
                $rs[] = $extra;
            }
        }
        return $rs;
    }

	/**
     * @param $id Extrafield id
     * @return iwBookingExtra Extrafield Object
     */
    public function getBookingExtra($id) {
        global $wpdb;
        $cat = new iwBookingCategory();
        $extra = new iwBookingExtra();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield WHERE id=%d', $id));
        if ($row) {
            $extra->setId($row->id);
            $extra->setName($row->name);
            $extra->setType($row->type);
            $extra->setIcon($row->icon);
            $extra->setDefault_value($row->default_value);
            $extra->setDescription($row->description);
            $extra->setCategories($cat->getExtrafieldCategory($row->id));
            $extra->setOrdering($row->ordering);
            $extra->setPublished($row->published);
        }
        return $extra;
    }

    public function getCountExtrafield($keywork='') {
        global $wpdb;
        return $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'iwb_extrafield WHERE name LIKE \'%'.$keywork.'%\'');
    }

}
