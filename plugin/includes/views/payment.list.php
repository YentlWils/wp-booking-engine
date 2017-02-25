<?php
/*
 * @package Inwave Event
 * @version 1.0.0
 * @created May 15, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of payment
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}

?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Bookings', 'inwavethemes'); ?>
            <!--<a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=bookings/addnew'); ?>"><?php echo __("Add New", IW_TEXT_DOMAIN); ?></a>-->
        <a class="bt-button add-new-h2" href="javascript:void(0);" onclick="document.getElementById('payment-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
        <!--		<a class="bt-button add-new-h2"-->
        <!--		   href="--><?php //echo admin_url('admin-post.php?action=iwBookingClearOrderExpired');    ?><!--">-->
        <?php //echo __("Kill Bookings Expired", IW_TEXT_DOMAIN); ?><!--</a>-->
    </h2>
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" name="filter">
        <div class="iwe-filter tablenav top">
            <div class="alignleft">
                <label><?php _e('Filter', 'inwavethemes'); ?></label>
                <input type="text" name="bookingcode" value="<?php echo filter_input(INPUT_GET, 'bookingcode'); ?>" placeholder="<?php echo __('Booking code', 'inwavethemes'); ?>"/>
                <input type="text" name="keyword" value="<?php echo filter_input(INPUT_GET, 'keyword'); ?>" placeholder="<?php echo __('Keyword', 'inwavethemes'); ?>"/>
            </div>
            <div class="alignleft"><?php
                ?>
            </div>
            <div class="alignleft">
                <?php
                $status_select_data = array(
                    array('value' => '', 'text' => __('Status', 'inwavethemes')),
                    array('value' => '1', 'text' => __('Pending', 'inwavethemes')),
                    array('value' => '2', 'text' => __('Completed', 'inwavethemes')),
                    array('value' => '3', 'text' => __('Cancel', 'inwavethemes')),
                    array('value' => '4', 'text' => __('Onhold', 'inwavethemes'))
                );
                echo iwBookingUtility::selectFieldRender('', 'status', filter_input(INPUT_GET, 'status'), $status_select_data, null, '', false);
                ?>
            </div>
            <div class="alignleft">
                <input type="hidden" value="iwBookingFilter" name="action"/>
                <input class="button" type="submit" value="<?php _e('Search', 'inwavethemes'); ?>"/>
            </div>
        </div>
    </form>

    <form id="payment-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="iwBookingDeleteOrders"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column">
                        <label class="screen-reader-text"
                               for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th class="column-primary <?php echo $sorted == 'booking_code' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo $order_link . '&orderby=booking_code&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Order #', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th><?php echo __('Customer Name', 'inwavethemes'); ?></th>
                    <th><?php echo __('Customer Email', 'inwavethemes'); ?></th>
                    <th class="column-primary <?php echo $sorted == 'price' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo $order_link . '&orderby=price&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Price', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th><?php echo __('Note', 'inwavethemes'); ?></th>
                    <th><?php echo __('Payment Method', 'inwavethemes'); ?></th>
                    <th class="column-primary <?php echo $sorted == 'time_created' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo $order_link . '&orderby=time_created&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Time Created', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th class="column-primary <?php echo $sorted == 'status' ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo $order_link . '&orderby=status&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Status', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($orders)) {
                    $ultility = new iwBookingUtility();
                    foreach ($orders as $order_data) {
                        $order = new iwBookingOrder();
                        $order->getOrder($order_data);
                        ?>
                        <tr class="<?php echo $order->getReaded() == '0' ? 'booking-unreaded' : ''; ?>">
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]"
                                       value="<?php echo $order->getId(); ?>"/>

                                <div class="locked-indicator"></div>
                            </th>
                            <td>
                                <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=bookings/view&id=' . $order->getId()); ?>"
                                   title="<?php echo __('View order', 'inwavethemes'); ?>">
                                       <?php echo $order->getBooking_code(); ?>
                                </a>

                                <div class="row-actions">
                                    <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=bookings/edit&id=' . $order->getId()); ?>"
                                       title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a>
                                    |
                                    <a class="submitdelete" title="<?php echo __('Delete this order', 'inwavethemes'); ?>"
                                       href="<?php echo admin_url("admin-post.php?action=deleteBookingOrder&id=" . $order->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                                    |
                                    <a class="submitdelete" title="<?php echo __('View order', 'inwavethemes'); ?>"
                                       href="<?php echo admin_url("edit.php?post_type=iw_booking&page=bookings/view&id=" . $order->getId()); ?>"><?php echo __('View', 'inwavethemes'); ?></a>
                                </div>
                            </td>
                            <td>
                                <?php
                                $member = $order->getMember();
                                if($member){
                                    echo $member->first_name.' '.$member->last_name;
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($member){
                                    echo $member->email;
                                }
                                ?>
                            </td>
                            <td><?php echo $ultility->getMoneyFormated($order->getPrice()); ?></td>
                            <td><?php echo $order->getNote(); ?></td>
                            <td><?php echo $order->getPayment_method_text(); ?></td>
                            <td><?php echo date('m/d/Y', $order->getTime_created()); ?></td>
                            <td><?php
                                switch ($order->getStatus()) {
                                    case 1:
                                        _e('Pending', 'inwavethemes');
                                        break;
                                    case 2:
                                        _e('Completed', 'inwavethemes');
                                        break;
                                    case 3:
                                        _e('Cancelled', 'inwavethemes');
                                        break;
                                    case 4:
                                        _e('Onhold', 'inwavethemes');
                                        break;
                                    case 5:
                                        _e('Waiting bank transfer', 'inwavethemes');
                                        break;
                                    default:
                                        break;
                                }
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $page_list = $paging->pageList($_GET['pagenum'], $pages);
                            echo $page_list;
                            ?>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo __('No result', 'inwavethemes'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>