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
 * Description of iwBookingCategory
 *
 * @developer duongca
 */
class iwBookingCategory {

    private $id;
    private $name;
    private $slug;

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getSlug() {
        return $this->slug;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSlug($slug) {
        $this->slug = $slug;
    }

    public function __construct() {
        
    }

    public function getExtrafieldCategory($extrafield_id) {
        global $wpdb;
        $rs = array();
        $result = $wpdb->get_results($wpdb->prepare('SELECT a.name, a.slug, a.term_id FROM ' . $wpdb->prefix . 'terms as a INNER JOIN ' . $wpdb->prefix . 'iwb_extrafield_category as b ON a.term_id = b.category_id  WHERE extrafield_id = %d', $extrafield_id));
        if (!empty($result)) {
            foreach ($result as $value) {
                $cat = new iwBookingCategory();
                $cat->setId($value->term_id);
                $cat->setName($value->name);
                $cat->setSlug($value->slug);
                $rs[] = $cat;
            }
        }
        return $rs;
    }

}
