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
$order->changeReaded(1, $order->getId());
$room_obj = new iwBookingRooms();
$ultility = new iwBookingUtility();
?>
<div class="iwe-wrap wrap">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <h2 class="in-title"><?php echo __('Edit order', 'inwavethemes'); ?></h2>
        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Code', 'inwavethemes'); ?></label>
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
                        <span><?php echo $ultility->getMoneyFormated($order->getSum_price()); ?></span>
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
                        <span><?php echo $ultility->getMoneyFormated($order->getPrice()); ?></span>
                    </td>
                </tr>
                <!--<tr class="alternate">
                    <td>
                        <label><?php /*echo __('Deposit price', 'inwavethemes'); */?></label>
                    </td>
                    <td colspan="3">
                        <span><?php /*echo $ultility->getMoneyFormated($order->getDeposit()); */?></span>
                    </td>
                </tr>-->
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Paid', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <input class="" type="text" value="<?php echo $order->getPaid(); ?>" name="paid"/>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Time start', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <div class="field-type field-date">
                            <input class="" type="text" value="<?php echo date('m/d/Y', $order->getTime_start()); ?>" name="time_start"/>
                        </div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Time end', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3" class="field-type field-date">
                        <input class="" type="text"
                               value="<?php echo date('m/d/Y', $order->getTime_end()); ?>" name="time_end"/>
                    </td>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Note', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <textarea name="order_note"><?php echo $order->getNote(); ?></textarea>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Status', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <?php
                        $status_data = array(array('text' => __('Pending', 'inwavethemes'), 'value' => 1), array('text' => __('Complated', 'inwavethemes'), 'value' => 2), array('text' => __('Cancelled', 'inwavethemes'), 'value' => 3), array('text' => __('Onhold', 'inwavethemes'), 'value' => 4));
                        echo iwBookingUtility::selectFieldRender('', 'new_order_status', $order->getStatus(), $status_data, '', '', FALSE)
                        ?>
                        <input type="hidden" value="<?php echo $order->getStatus(); ?>" name="order_status"/>
                    </td>
                </tr><!--
                <tr class="alternate">
                    <td>
                        <label for="sendmail_to_cutommer"><?php /*echo __('Send email notice to customer', 'inwavethemes'); */?></label>
                    </td>
                    <td colspan="3">
                        <input class="sendmail_to_cutommer" style="width: auto;" type="checkbox" value="1"
                               name="sendmail_to_cutommer" id="sendmail_to_cutommer"/>
                    </td>
                </tr>
                <tr class="alternate status_reason">
                    <td>
                        <label><?php /*echo __('Reason change status', 'inwavethemes'); */?></label>
                    </td>
                    <td colspan="3">
                        <textarea name="reason"></textarea>
                    </td>
                </tr>-->
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Time created', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo date('m/d/Y', $order->getTime_created()); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Last update', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo date('m/d/Y', $order->getLast_update()); ?></span>
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
                                            <input type="text" value="<?php echo esc_attr($member->first_name); ?>" name="member[first_name]"/>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Last name', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" value="<?php echo esc_attr($member->last_name); ?>" name="member[last_name]"/>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" value="<?php echo esc_attr($member->email); ?>" name="member[email]"/>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Phone', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" value="<?php echo esc_attr($member->phone); ?>" name="member[phone]"/>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Address', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea name="member[address]"><?php echo $member->address; ?></textarea>
                                        </td>
                                    </tr>

                                    <?php
                                    if($member->field_value) {
                                        foreach ($member->field_value as $key=>$value):
                                            foreach ($iwb_settings['register_form_fields'] as $field):
                                                if ($key == $field['name']):
                                                    ?>
                                                    <tr class="alternate">
                                                        <td>
                                                            <label><?php echo __($field['label'], 'inwavethemes'); ?></label>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            switch ($field['type']):
                                                                case 'select':
                                                                    echo '<select name="member[' . $field['name'] . ']">';
                                                                    foreach ($field['values'] as $option) {
                                                                        echo '<option value="' . $option['value'] . '" ' . ($option['value'] == $value ? 'selected="selected"' : '') . '>' . $option['text'] . '</option>';
                                                                    }
                                                                    echo '</select>';
                                                                    break;
                                                                case 'textarea':
                                                                    echo '<textarea name="member[' . $field['name'] . ']">' . $value . '</textarea>';
                                                                    break;
                                                                    break;
                                                                case 'email':
                                                                    echo '<input type="email" value="' . $value . '" name="member[' . $field['name'] . ']"/>';
                                                                    break;

                                                                default:
                                                                    echo '<input type="text" value="' . $value . '" name="member[' . $field['name'] . ']"/>';
                                                                    break;
                                                            endswitch;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    break;
                                                endif;
                                            endforeach;
                                        endforeach;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2" style="vertical-align: top">
                        <table class="list-table room-info">
                            <thead>
                                <tr>
                                    <th colspan="2"><?php _e('Room info', 'inwavethemes'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="the-list" style="text-align: left">
                                <tr>
                                    <th width="10%"><?php _e('#', IW_TEXT_DOMAIN); ?></th>
                                    <th width="30%"><?php _e('Item name', IW_TEXT_DOMAIN); ?></th>
                                    <th width="20%"><?php _e('Item price', IW_TEXT_DOMAIN); ?></th>
                                    <th width="30%"><?php _e('Room price', IW_TEXT_DOMAIN); ?></th>
                                </tr>
                                <?php
                                $rooms = $order->getRooms();
                                if($rooms){
                                    foreach ($rooms as $i=>$room):
                                        $services = $room['services'];
                                        $room_post = get_post($room['room_id']);
                                        $tr_class = $i % 2 == 0 ? 'odd' : 'even';
                                        ?>
                                        <tr class="alternate <?php echo $tr_class?>">
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
                                                <tr class="alternate <?php echo $tr_class?>">
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
                <tr class="alternate tr-action">
                    <td>
                        <input type="hidden" name="order_member" value="<?php echo $member->id; ?>"/>
                        <input type="hidden" name="order_id" value="<?php echo $order->getId(); ?>"/>
                        <input type="hidden" name="action" value="iwbUpdateOrderInfo"/>
                        <a class="button"
                           href="<?php echo admin_url("edit.php?post_type=iw_booking&page=bookings"); ?>"><?php echo __('Back to list', 'inwavethemes'); ?></a>
                        <input type="submit" value="<?php _e('Save Update', 'inwavethemes'); ?>" class="button"/>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>