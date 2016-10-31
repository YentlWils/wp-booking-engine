<?php
/*
 * @package Inwave Event
 * @version 1.0.0
 * @created May 27, 2015
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
$order->changeReaded(1, $order->getId());
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Order Detail', 'inwavethemes'); ?></h2>
</div>
<div class="iwe-wrap wrap">
    <table class="list-table">
        <tbody class="the-list">
            <tr class="alternate">
                <td>
                    <label><?php echo __('Order', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo $order->getBooking_code(); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Sum Price', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo iwBookingUtility::getMoneyFormated($order->getSum_price(), $order->getCurrency()); ?></span>
                </td>
            </tr>
            <?php if($order->discount_price > 0){ ?>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Discount', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <?php $discount = $order->getDiscount(); ?>
                    <span>
                        <?php
                            echo iwBookingUtility::getMoneyFormated($order->discount_price, $order->getCurrency());
                        ?>
                    </span>
                    <?php
                    if($discount){
                        ?>
                        <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=service/edit&id='.$discount->getId()); ?>"><?php echo $discount->getDiscount_code();?></a>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php  }  ?>
            <?php if($order->tax_price > 0) {?>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Tax', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo iwBookingUtility::getMoneyFormated($order->tax_price, $order->getCurrency()); ?> - <?php echo $order->tax.'%'; ?></span>
                </td>
            </tr>
            <?php } ?>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Grand price', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo iwBookingUtility::getMoneyFormated($order->getPrice(), $order->getCurrency()); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Paid', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <?php
                    if($order->getPaid() > 0){
                    ?>
                        <span><?php echo iwBookingUtility::getMoneyFormated($order->getPaid(), $order->getCurrency()); ?></span>
                    <?php } ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Time start', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo date(get_option('date_format'), $order->getTime_start()); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Time end', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo date(get_option('date_format'), $order->getTime_end()); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Note', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo $order->getNote(); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Status', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <?php
                    switch ($order->getStatus()) {
                        case 1:
                            _e('Pending', 'inwavethemes');
                            break;
                        case 2:
                            _e('Complate', 'inwavethemes');
                            break;
                        case 3:
                            _e('Cancel', 'inwavethemes');
                            break;
                        case 4:
                            _e('Onhold', 'inwavethemes');
                            break;
                        default:
                            break;
                    }
                    ?>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Time created', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo date(get_option('date_format'). ' '. get_option('time_format'), $order->getTime_created()); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Last update', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php  echo $order->getLast_update() ? date(get_option('date_format'). ' '. get_option('time_format'), $order->getLast_update()) : ''; ?></span>
                </td>
            </tr>
            <?php
                if (count($order->getGuests()) > 0) :
                    ?>

                        <tr class="alternate">
                            <td colspan="4"><b><u><?php echo __('Guest info', 'inwavethemes'); ?></u></b></td>
                        </tr>
                    <?php
                    foreach ($order->getGuests() as $key => $guest):
                        ?>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Guest ', 'inwavethemes'); ?><?php echo ($key + 2) ?></label>
                            </td>
                            <td colspan="3">
                                <span><?php  echo $guest; ?></span>
                            </td>
                            </tr>
            <?php
                    endforeach;
                endif;
            ?>
            <?php if (is_admin()): ?>
                <tr class="alternate">
                    <td colspan="2">
                        <table class="list-table member-info">
                            <thead>
                                <tr>
                                    <th colspan="2"><?php _e('Member Info', 'inwavethemes'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="the-list">
                                <?php
                                $member = $order->getMember();
                                if($member){
                                    ?>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('First name', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <span><?php echo $member->first_name; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Last name', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <span><?php echo $member->last_name; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <span><?php echo $member->email; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Phone', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <span><?php echo $member->phone; ?></span>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Address', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <span><?php echo $member->address; ?></span>
                                        </td>
                                    </tr>

                                    <?php
                                    if($member->field_value) {
                                        foreach ($member->field_value as $key=>$value):
                                            ?>
                                            <tr class="alternate">
                                                <td>
                                                    <label><?php echo $key; ?></label>
                                                </td>
                                                <td>
                                                    <span><?php echo $value; ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2">
                        <table class="list-table room-info">
                            <thead>
                                <tr>
                                    <th><?php _e('Room info', 'inwavethemes'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="the-list">
                                <tr>
                                    <th>#</th>
                                    <th><?php _e('Item', IW_TEXT_DOMAIN); ?></th>
                                    <th><?php _e('Item price', IW_TEXT_DOMAIN); ?></th>
                                    <th><?php _e('Room price', IW_TEXT_DOMAIN); ?></th>
                                </tr>
                                <?php
                                $rooms = $order->getRooms();
                                if($rooms){
                                    foreach ($rooms as $i=>$room):
                                        $services = $room['services'];
                                        $room_post = get_post($room['room_id']);
                                        $tr_class = $i % 2 == 0 ? 'odd' : 'even';
                                        ?>
                                        <tr class="alternate <?php echo $tr_class; ?>">
                                            <td rowspan="<?php echo 1 + count($services); ?>"><?php echo ($i+1); ?></td>
                                            <td>
                                                <span><?php echo esc_html($room_post->post_title); ?></span>
                                                <span><?php echo __('Adult : ', 'inwavethemes').$room['adult']; ?></span>
                                                <span><?php echo __('Children : ', 'inwavethemes').$room['children']; ?></span>
                                            </td>
                                            <td>
                                                <span><?php echo iwBookingUtility::getMoneyFormated($room['price'], $order->getCurrency()); ?></span>
                                            </td>
                                            <td rowspan="<?php echo 1 + count($services); ?>">
                                                <span><?php echo iwBookingUtility::getMoneyFormated($room['price_with_service'], $order->getCurrency()); ?></span>
                                            </td>
                                        </tr>
                                        <?php
                                        if($services){
                                            foreach ($services as $service){
                                                ?>
                                                <tr class="alternate <?php echo $tr_class; ?>">
                                                    <td>
                                                        <span><?php echo __('Service : '); ?><?php echo esc_html($service['title']); ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?php echo iwBookingUtility::getMoneyFormated($service['price'], $order->getCurrency()); ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    endforeach;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><h3></h3><?php echo __('Grand price') ?></h3></td>
                                    <td><strong><?php echo iwBookingUtility::getMoneyFormated($order->price, $order->getCurrency()); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <a class="button"
                           href="<?php echo admin_url("edit.php?post_type=iw_booking&page=bookings"); ?>"><?php echo __('Back to list', 'inwavethemes'); ?></a>
                        <a class="button"
                           href="<?php echo admin_url("edit.php?post_type=iw_booking&page=bookings/edit&id=" . $order->getId()); ?>"><?php echo __('Edit order', 'inwavethemes'); ?></a>
                        <!--				--><?php //if ($order->getStatus() == 2):     ?>
                        <!--					<a class="button"-->
                        <!--					   href="--><?php //echo admin_url("admin-post.php?action=orderResendEmail&id=" . $order->getId());     ?><!--">--><?php //echo __('Resend email', 'inwavethemes');     ?><!--</a>-->
                        <!--				--><?php //else:     ?>
                        <!--					<span class="button disabled">--><?php //echo __('Resend email', 'inwavethemes');     ?><!--</span>-->
                        <!--				--><?php //endif;     ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if (!is_admin()): ?>
        <div class="notice">
            <?php
            if ($order->getStatus() == 2) {
                echo $utility->getMessage(__('Your booking has completed. Thanks for donate with us', 'inwavethemes'));
            } else {
                echo $utility->getMessage(__('Your booking don\'t completed.', 'inwavethemes'), 'notice');
            }
            ?>
        </div>
    <?php endif; ?>
</div>
