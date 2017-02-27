<?php

/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Aug 11, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingRooms
 *
 * @developer duongca
 */
class iwBookingRooms {

    public function __construct() {
        
    }

    //Get Available rooms by room number
//    public function getAvailableRooms($time_start, $time_end, $number_rooms) {
//        global $wpdb;
//        $rooms = array();
//        if ($time_start && $time_end && $number_rooms) {
//            $room_useds = $wpdb->get_results(
//                    $wpdb->prepare('SELECT br.room_id, SUM(br.amount) as room_used FROM ' . $wpdb->prefix . 'iwb_booking_room_rf as br INNER JOIN ' . $wpdb->prefix . 'iwb_bookings as b ON br.booking_id = b.id WHERE b.time_start > %d OR b.time_end > %d GROUP BY br.room_id', $time_start, $time_end)
//            );
//            $room_all = $wpdb->get_results('SELECT a.ID, b.meta_value as room_amount FROM ' . $wpdb->prefix . 'posts as a INNER JOIN ' . $wpdb->prefix . 'postmeta as b ON a.ID = b.post_id WHERE b.meta_key ="iw_booking_room_amount"');
//            $room_useds_key = array();
//            foreach ($room_useds as $room_use) {
//                $room_useds_key[$room_use->room_id] = $room_use->room_used;
//            }
//            foreach ($room_all as $room) {
//                $room->room_empty = $room->room_amount;
//                if (isset($room_useds_key[$room->ID])) {
//                    $room->room_empty -= $room_useds_key[$room->ID];
//                }
//
//                if ($room->room_empty >= $number_rooms && get_post_meta($room->ID, 'iw_booking_room_status', true)) {
//                    $room_data = $this->getRoomInfo($room->ID, $room->room_empty);
//                    if ($room_data) {
//                        $rooms[] = $room_data;
//                    }
//                }
//            }
//        }
//        return $rooms;
//    }
    //Get Available rooms by guest
    public function getAvailableRooms($time_start, $time_end, $adult, $children = null, $room_selected = array(), $page = 1, $filter_room = 0, $ignore = array()) {
        global $wpdb, $iwb_settings, $iw_rooms_available;
        $rooms = $iw_rooms_available = array();
        if ($time_start && $time_end && ($time_start < $time_end) && $adult) {

            // Check if there are any off days planned for the villa
            $periodOffDays = $wpdb->get_results($wpdb->prepare('SELECT o.note FROM ' . $wpdb->prefix . 'iwb_off_days as o WHERE %d BETWEEN o.time_start AND o.time_end OR %d BETWEEN o.time_start AND o.time_end', $time_start, $time_end));

            if(count($periodOffDays) == 0) {
                //Get all room used
                $room_useds = $wpdb->get_results(
                    $wpdb->prepare('SELECT br.room_id, COUNT(br.room_id) as room_used FROM ' . $wpdb->prefix . 'iwb_booking_room_rf as br INNER JOIN ' . $wpdb->prefix . 'iwb_bookings as b ON br.booking_id = b.id WHERE ((b.time_start >= %d AND b.time_start < %d) OR (b.time_end > %d AND b.time_end <= %d)) AND (b.status=1 OR b.status=2 OR b.status=4 OR b.status=5) GROUP BY br.room_id', $time_start, $time_end, $time_start, $time_end)
                );
                if ($children) {
                    $sql = "SELECT *,pmeta3.meta_value as room_amount FROM $wpdb->posts  AS post 
                    LEFT JOIN $wpdb->postmeta AS pmeta1 ON (pmeta1.post_id = post.ID AND pmeta1.meta_key = 'iw_booking_room_adult_amount')
                    LEFT JOIN $wpdb->postmeta AS pmeta2 ON (pmeta2.post_id = post.ID AND pmeta2.meta_key = 'iw_booking_room_child_amount')
                    LEFT JOIN $wpdb->postmeta AS pmeta3 ON (pmeta3.post_id = post.ID AND pmeta3.meta_key = 'iw_booking_room_amount')
                    WHERE post.`post_type` = 'iw_booking'
                    AND post.`post_status` = 'publish'
                    AND (CAST(pmeta1.`meta_value` AS UNSIGNED) >= %d OR pmeta1.`meta_value` = '' OR pmeta1.`meta_value` IS NULL)
                    AND (CAST(pmeta2.`meta_value` AS UNSIGNED) >= %d OR pmeta2.`meta_value` = '' OR pmeta2.`meta_value` IS NULL)";

                    if ($filter_room) {
                        $sql .= " AND post.ID = %d";
                        $room_all = $wpdb->get_results($wpdb->prepare($sql, $adult, $children, $filter_room));
                    } else {
                        if ($ignore) {
                            $sql .= " AND post.ID NOT IN (" . implode(',', $ignore) . ")";
                        }
                        $room_all = $wpdb->get_results($wpdb->prepare($sql, $adult, $children));
                    }
                } else {
                    $sql = "SELECT *, pmeta3.meta_value as room_amount FROM $wpdb->posts  AS post 
                    LEFT JOIN $wpdb->postmeta AS pmeta1 ON (pmeta1.post_id = post.ID AND pmeta1.meta_key = 'iw_booking_room_adult_amount')
                    LEFT JOIN $wpdb->postmeta AS pmeta3 ON (pmeta3.post_id = post.ID AND pmeta3.meta_key = 'iw_booking_room_amount')
                    WHERE post.`post_type` = 'iw_booking'
                    AND post.`post_status` = 'publish'
                    AND (CAST(pmeta1.`meta_value` AS UNSIGNED) >= %d OR pmeta1.`meta_value` = '' OR pmeta1.`meta_value` IS NULL)";
                    if ($filter_room) {
                        $sql .= " AND post.ID = %d";
                        $room_all = $wpdb->get_results($wpdb->prepare($sql, $adult, $filter_room));
                    } else {
                        if ($ignore) {
                            $sql .= " AND post.ID NOT IN (" . implode(',', $ignore) . ")";
                        }
                        $room_all = $wpdb->get_results($wpdb->prepare($sql, $adult));
                    }
                }

                $room_useds_key = array();
                foreach ($room_useds as $room_use) {
                    $room_useds_key[$room_use->room_id] = $room_use->room_used;
                }
                if ($room_selected) {
                    foreach ($room_selected as $room_id) {
                        if (isset($room_useds_key[$room_id])) {
                            $room_useds_key[$room_id] = $room_useds_key[$room_id] + 1;
                        } else {
                            $room_useds_key[$room_id] = 1;
                        }
                    }
                }

                foreach ($room_all as $room) {
                    $room->room_empty = $room->room_amount;
                    if ($room->room_empty) {
                        $room_used = isset($room_useds_key[$room->ID]) ? $room_useds_key[$room->ID] : 0;
                        if ($room->room_empty > $room_used) {
                            $rooms[] = $room->ID;
                            $iw_rooms_available[$room->ID] = (int)$room->room_empty - $room_used;
                        }
                    } else {
                        $rooms[] = $room->ID;
                        $iw_rooms_available[$room->ID] = true;
                    }
                }
            }
        }

        $room_per_page = (isset($iwb_settings['general']['reservation_room_perpage']) && $iwb_settings['general']['reservation_room_perpage']) ? $iwb_settings['general']['reservation_room_perpage'] : 5;

        if(!$rooms){
            $rooms = false;
        }
        else{
            $rooms = implode(',',$rooms);
        }
        return $this->getRoomList(null, $rooms, 'ID', 'DESC', $room_per_page, $page);
    }

    public function getRoomEmpty($room_id, $time_start, $time_end) {
        global $wpdb;
        $room_useds = $wpdb->get_var(
                $wpdb->prepare('SELECT SUM(br.amount) FROM ' . $wpdb->prefix . 'iwb_booking_room_rf as br INNER JOIN ' . $wpdb->prefix . 'iwb_bookings as b ON br.booking_id = b.id WHERE (((b.time_start >= %d AND b.time_start < %d) OR (b.time_end > %d AND b.time_end <= %d)) AND (b.status=1 OR b.status=4 OR b.status=5)) AND br.room_id=%d GROUP BY br.room_id', $time_start, $time_end, $time_start, $time_end, $room_id)
        );
        $room_amount = get_post_meta($room_id, 'iw_booking_room_amount', true);
        return $room_amount - $room_useds;
    }

    public function getRoomInfo($room_id, $check = true) {
        global $wpdb, $iwb_settings, $iw_rooms_available;
        $room = null;
        $room_data = get_post($room_id);
        if (($check && $room_data->post_status == 'publish') || !$check) {
            $feature_img = wp_get_attachment_image_src(get_post_thumbnail_id($room_id), 'single-post-thumbnail');
            $extrafiels_data = $wpdb->get_results($wpdb->prepare("SELECT b.name, a.value, b.type FROM " . $wpdb->prefix . "iwb_extrafield_value as a INNER JOIN " . $wpdb->prefix . "iwb_extrafield as b ON a.extrafield_id = b.id WHERE a.room_id=%d", $room_id));
            $images = unserialize(get_post_meta($room_id, 'iw_booking_image_gallery', true));
            $images_data = array();
            foreach ($images as $img) {
                $image = wp_get_attachment_image_src($img, 'large');
                $images_data[] = $image[0];
            }
            $room_data->room_available = isset($iw_rooms_available[$room_data->ID]) ? $iw_rooms_available[$room_data->ID] : false;
            $room_data->room_amount = get_post_meta($room_id, 'iw_booking_room_amount', true);
            $room_data->extrafield = $extrafiels_data;
            $room_data->images = $images_data;
            $room_data->price = get_post_meta($room_id, 'iw_booking_room_price', true);
            $room_data->adult_amount = get_post_meta($room_id, 'iw_booking_room_adult_amount', true);
            $room_data->children_amount = get_post_meta($room_id, 'iw_booking_room_child_amount', true);
            $room_data->people_amount = ($room_data->adult_amount + $room_data->children_amount);
            $room_data->deposit = htmlspecialchars(get_post_meta($room_id, 'iw_booking_room_deposit', true));
            $room_data->currency = $iwb_settings['general']['currency'];
            $room_data->beds = htmlspecialchars(get_post_meta($room_id, 'iw_booking_room_beds', true));
            $room_data->category = get_term_by('slug', get_post_meta($room_id, 'iw_booking_category', true), 'booking_category');
            $room_data->image_feature = $feature_img[0];
            $room_data->basic_services = array();
            $room_data->basic_services = array();
            $basic_services = unserialize(get_post_meta($room_id, 'iw_booking_basic_services', true));
            foreach ($basic_services as $service) {
                $s = new iwBookingService();
                $room_data->basic_services[] = $s->getService($service);
            }
            $room_data->premium_services = array();
            $premium_services = unserialize(get_post_meta($room_id, 'iw_booking_premium_services', true));
            foreach ($premium_services as $service) {
                $s = new iwBookingService();
                $room_data->premium_services[$service] = $s->getService($service);
            }
            $room_data->average_rating = self::get_average_rating($room_id);
            //$room_data->rating_count = self::get_rating_count($room_id);
            $room_data->review_count = self::get_review_count($room_id);

            $room = $room_data;
        }
        return $room;
    }

    function getRoomHtml($room, $quantity = 0) {
        $html = '<div class="booking-item">';
        if ($quantity) {
            $html.='<a class="remove-room" href="#" data-price="' . $room->price . '" data-room="' . $room->ID . '"><span class="remove">remove</span></a>';
            $html.='<div class="room-title">' . $room->post_title . '</div>';
            $html.='<label>Quantity</label>';
            $html.='<input class="quantity" name="quantity[]" value="' . $quantity . '" type="text"/>';
        } else {
            $html.= '<div class="room-item">';
            $html.= $room->post_title . '(' . $room->room_empty . '/' . $room->room_amount . ') - ' . $room->price . '$<br/>';
            $html.= '<a class="add-bcart" data-empty-room="' . $room->room_empty . '" data-price="' . $room->price . '" data-item="' . $room->ID . '" href="#" >' . __('Add to booking', IW_TEXT_DOMAIN) . '</a><br/>';
            $html.= '</div>';
        }
        $html.='</div>';

        return $html;
    }

    public function getRoomList($cats, $ids, $order_by, $order_dir, $limit, $paged = 1) {
        if (!$paged) {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }
        $terms = filter_input(INPUT_GET, 'category');
        $keyword = filter_input(INPUT_GET, 'keyword');
        $order_byn = filter_input(INPUT_GET, 'order_by') ? filter_input(INPUT_GET, 'order_by') : $order_by;
        $order_dirn = filter_input(INPUT_GET, 'order_dir') ? filter_input(INPUT_GET, 'order_dir') : $order_dir;
        $args = array();
        if (!$ids && $ids !== false) {
            $cat_array = explode(',', $cats);
            $new_cats = array();
            if ($terms) {
                $new_cats[] = $terms;
            } else {
                if (in_array('0', $cat_array) || empty($cat_array) || in_array('', $cat_array)) {
                    $res = get_terms('booking_category');
                    foreach ($res as $value) {
                        $new_cats[] = $value->term_id;
                    }
                } else {
                    $new_cats = $cat_array;
                }
            }
            $args['tax_query'] = array();
            $args['tax_query'][] = array(
                'taxonomy' => 'booking_category',
                'terms' => $new_cats,
                'include_children' => false
            );

        } else {
            $args['post__in'] = explode(',', $ids);
            if ($terms) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'booking_category',
                        'terms' => array($terms),
                        'include_children' => false
                    ),
                );
            }
        }
        $args['post_type'] = 'iw_booking';
        $args['s'] = $keyword;
        $args['order'] = ($order_dirn) ? $order_dirn : 'desc';
        $args['orderby'] = ($order_byn) ? $order_byn : 'ID';
        $args['post_status'] = 'publish';
        $args['posts_per_page'] = $limit;
        $args['paged'] = $paged;
        $query = new WP_Query($args);
        return $query;
    }

    /**
     * Get the average rating of room. This is calculated once and stored in postmeta.
     * @return string
     */
    public static function get_average_rating($ID) {
        // No meta data? Do the calculation
        if (!metadata_exists('post', $ID, 'iwbooking_average_rating')) {
            self::sync_average_rating($ID);
        }

        return (string) floatval(get_post_meta($ID, 'iwbooking_average_rating', true));
    }

    /**
     * Get the total amount (COUNT) of ratings.
     * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
     * @return int
     */
    public static function get_rating_count($ID, $value = null) {
        // No meta data? Do the calculation
        if (!metadata_exists('post', $ID, 'iwbooking_rating_count')) {
            self::sync_rating_count($ID);
        }

        $counts = get_post_meta($ID, 'iwbooking_rating_count', true);

        if (is_null($value)) {
            return array_sum($counts);
        } else {
            return isset($counts[$value]) ? $counts[$value] : 0;
        }
    }

    /**
     * Sync product rating. Can be called statically.
     * @param  int $post_id
     */
    public static function sync_average_rating($post_id) {
        if (!metadata_exists('post', $post_id, 'iwbooking_rating_count')) {
            self::sync_rating_count($post_id);
        }

        $count = array_sum((array) get_post_meta($post_id, 'iwbooking_rating_count', true));

        if ($count) {
            global $wpdb;

            $ratings = $wpdb->get_var($wpdb->prepare("
				SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
			", $post_id));
            $average = number_format($ratings / $count, 2, '.', '');
        } else {
            $average = 0;
        }
        update_post_meta($post_id, 'iwbooking_average_rating', $average);
    }

    /**
     * Sync product rating count. Can be called statically.
     * @param  int $post_id
     */
    public static function sync_rating_count($post_id) {
        global $wpdb;

        $counts = array();
        $raw_counts = $wpdb->get_results($wpdb->prepare("
			SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
			GROUP BY meta_value
		", $post_id));

        foreach ($raw_counts as $count) {
            $counts[$count->meta_value] = $count->meta_value_count;
        }

        update_post_meta($post_id, 'iwbooking_rating_count', $counts);
    }

    /**
     * Get the total amount (COUNT) of reviews.
     *
     * @since 2.3.2
     * @return int The total numver of product reviews
     */
    public static function get_review_count($post_id) {
        global $wpdb;

        // No meta date? Do the calculation
        if (!metadata_exists('post', $post_id, 'iwbooking__review_count')) {
            $count = $wpdb->get_var($wpdb->prepare("
				SELECT COUNT(*) FROM $wpdb->comments
				WHERE comment_parent = 0
				AND comment_post_ID = %d
				AND comment_approved = '1'
			", $post_id));

            update_post_meta($post_id, 'iwbooking_review_count', $count);
        } else {
            $count = get_post_meta($post_id, 'iwbooking_review_count', true);
        }

        return apply_filters('iw_booking_room_count', $count, $post_id);
    }

    public function load_more_room($query = '', $page = 'page') {
        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }

        $option_link = array(
            'prev_next' => false,
            'show_all' => true,
            'total' => $query->max_num_pages
        );
        if ($page == 'page') {
            $option_link['format'] = '?paged=%#%';
            $option_link['current'] = max(1, get_query_var('paged'));
        } else {
            $option_link['format'] = '?page=%#%';
            $option_link['current'] = max(1, get_query_var('page'));
        }

        $paginate_links = paginate_links($option_link);

        // Display the pagination if more than one page is found
        //    $rs = '<button class="load-more load-posts all-loaded" id="load-more-class"><span class="ajax-loading-icon"><i class="fa fa-spinner fa-spin fa-2x"></i></span>' . __('All loaded', 'inwavethemes') . '</button>';
        $rs = '';
        if ($paginate_links) :
            $html = array();
            $html[] = '<div class="post-pagination clearfix" style="display: none;">';
            $html[] = $paginate_links;
            $html[] = '</div>';
            $rs = '<button class="load-more load-posts" id="load-more-class"><span class="ajax-loading-icon"><i class="fa fa-spinner fa-spin fa-2x"></i></span>' . __('Load more...', 'inwavethemes') . '</button>' . implode($html);
        endif;
        return $rs;
    }
}
