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
 * Description of settings
 *
 * @developer duongca
 */
global $iwb_settings;
$utility = new iwBookingUtility();
wp_enqueue_script('jquery-ui-sortable');
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post">
    <div id="" class="iwbooking-settings iw-tabs event-detail layout2">
        <div class="iw-tab-items">
            <div class="iw-tab-item active">
                <div class="iw-tab-title"><span><?php _e('General', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Registration form', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Payment', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Message template', 'inwavethemes'); ?></span></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="iw-tab-content">
            <div class="iw-tab-item-content">
                <?php
                $general = isset($iwb_settings['general']) ? $iwb_settings['general'] : array();
                ?>
                <table class="list-table">
                    <tbody class="the-list">
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Currency', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $data = $utility->getIWBookingcurrencies();
                                echo iwBookingUtility::selectFieldRender('', 'iwb_settings[general][currency]', (isset($general['currency']) ? $general['currency'] : 'USD'), $data, '', '', FALSE);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Currency for payment', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Currency Position', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $data = array(
                                    array('text' => __('Left', 'inwavethemes'), 'value' => 'left'),
                                    array('text' => __('Left with space', 'inwavethemes'), 'value' => 'left_space'),
                                    array('text' => __('Right', 'inwavethemes'), 'value' => 'right'),
                                    array('text' => __('Right with space', 'inwavethemes'), 'value' => 'right_space')
                                );
                                echo $utility->selectFieldRender('', 'iwb_settings[general][currency_pos]', (isset($general['currency_pos']) ? $general['currency_pos'] : 'left'), $data, '', '', FALSE);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Currency position', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Tax', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['tax']) ? $general['tax'] : ''; ?>" name="iwb_settings[general][tax]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('In percent .Ex 10', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Booking slug', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['booking_slug']) ? $general['booking_slug'] : 'iw-booking'; ?>" name="iwb_settings[general][booking_slug]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Slug for booking post', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Category slug', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['category_slug']) ? $general['category_slug'] : 'iwb-category'; ?>" name="iwb_settings[general][category_slug]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Slug for event category', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Booking page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $pages = get_pages();
                                $page_data = array();
                                foreach ($pages as $page) {
                                    $page_data[] = array('text' => $page->post_title, 'value' => $page->ID);
                                }
                                echo iwBookingUtility::selectFieldRender('booking_page', 'iwb_settings[general][booking_page]', isset($general['booking_page']) ? $general['booking_page'] : '', $page_data, 'Select Page', '', false);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Booking page', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Check order page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                echo iwBookingUtility::selectFieldRender('payment_page', 'iwb_settings[general][check_order_page]', isset($general['check_order_page']) ? $general['check_order_page'] : '', $page_data, 'Select Page', '', false);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Allow user check order infomation', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Clear order times', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['schedule_time']) ? $general['schedule_time'] : '0.5'; ?>" name="iwb_settings[general][schedule_time]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Times to clear invalid bookings order with unit is hour. Eg: 0.5 for 30 minutes, 1 for 1 hour, 24 for 1 day...', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Reservation room per page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['reservation_room_perpage']) ? $general['reservation_room_perpage'] : '5'; ?>" name="iwb_settings[general][reservation_room_perpage]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Number of rooms per page in reservation page', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Reservation datepicker format', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['reservation_datepicker_format']) ? $general['reservation_datepicker_format'] : 'd M yy'; ?>" name="iwb_settings[general][reservation_datepicker_format]"/>
                            </td>
                            <td>
                                <span class="description"><?php echo sprintf(__('Format a date into a string value with a specified format The format can be combinations at <a href="%s" target="_blank">here</a>', 'inwavethemes'), 'http://api.jqueryui.com/datepicker/'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Reservation completed page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <textarea name="iwb_settings[general][reservation_completed_message]" style="width: 100%; min-height: 100px" placeholder="<?php echo __('Message', 'inwavethemes'); ?>"><?php echo isset($general['reservation_completed_message']) ? stripslashes($general['reservation_completed_message']) : '' ?></textarea>
                                <input type="text" value="<?php echo isset($general['reservation_completed_contact_phone']) ? $general['reservation_completed_contact_phone'] : ''; ?>" name="iwb_settings[general][reservation_completed_contact_phone]" placeholder="<?php echo __('Phone', 'inwavethemes'); ?>"/>
                                <input type="text" value="<?php echo isset($general['reservation_completed_contact_email']) ? $general['reservation_completed_contact_email'] : ''; ?>" name="iwb_settings[general][reservation_completed_contact_email]" placeholder="<?php echo __('Email', 'inwavethemes'); ?>"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('These message will be displayed when reservation is completed.', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="iw-tab-item-content iw-hidden">
                <table id="sortable" class="list-table iwe-field-manager">
                    <thead>
                        <tr class="field-mng-heading">
                            <th colspan="10"><?php _e('Field manager', 'inwavethemes'); ?></th>
                        </tr>
                        <tr>
                            <th><?php _e('Order', 'inwavethemes'); ?></th>
                            <th><?php _e('Label', 'inwavethemes'); ?></th>
                            <th><?php _e('Name', 'inwavethemes'); ?></th>
                            <th><?php _e('Type', 'inwavethemes'); ?></th>
                            <th><?php _e('Default Value', 'inwavethemes'); ?></th>
                            <th><abbr title="<?php _e('Show on List', 'inwavethemes'); ?>"><?php _e('SL', 'inwavethemes'); ?></abbr></th>
                            <th><abbr title="<?php _e('Require Field', 'inwavethemes'); ?>"><?php _e('RF', 'inwavethemes'); ?></abbr></th>
                            <th><?php _e('Actions', 'inwavethemes'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="the-list">
                        <?php
                        if (!empty($iwb_settings['register_form_fields'])):
                            foreach ($iwb_settings['register_form_fields'] as $field) :
                                ?>
                                <tr class="alternate">
                                    <td class="iwe-sortable-cell">
                                        <span><i class="fa fa-arrows"></i></span>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo $field['label'] ?>" class="field-label" name="iwb_settings[register_form_fields][label][]"/>
                                    </td>
                                    <td>
                                        <input type="text" readonly="readonly" value="<?php echo $field['name'] ?>" class="field-name" name="iwb_settings[register_form_fields][name][]"/>
                                    </td>
                                    <td>
                                        <?php
                                        $data = array(
                                            array('text' => 'String', 'value' => 'text'),
                                            array('text' => 'Select', 'value' => 'select'),
                                            array('text' => 'Text', 'value' => 'textarea'),
                                            array('text' => 'Email', 'value' => 'email')
                                        );
                                        echo $utility->selectFieldRender('', 'register_form_fields_type', $field['type'], $data, '', 'select-field-type', FALSE, 'disabled="disabled"');
                                        ?>
                                        <input type="hidden" name="iwb_settings[register_form_fields][type][]" value="<?php echo $field['type']; ?>"/>
                                    </td>
                                    <td class="default-value">
                                        <?php
                                        switch ($field['type']) {
                                            case 'select':
                                                echo $utility->selectFieldRender('', 'iwb_settings[register_form_fields][default_value][]', $field['default_value'], $field['values'], '', '', FALSE);
                                                break;
                                            case 'textarea':
                                                echo '<textarea name="iwb_settings[register_form_fields][default_value][]">' . $field['default_value'] . '</textarea>';
                                                break;
                                            case 'email':
                                                echo '<input type="email" value="' . $field['default_value'] . '" name="iwb_settings[register_form_fields][default_value][]"/>';
                                                break;

                                            default:
                                                echo '<input type="text" value="' . $field['default_value'] . '" name="iwb_settings[register_form_fields][default_value][]"/>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="show_on_list" <?php echo isset($field['show_on_list']) ? 'checked="checked"' : ''; ?> class="show_on_list"/>
                                        <input class="iwb_field_val" type="hidden" value="<?php echo isset($field['show_on_list']) ? $field['show_on_list'] : ''; ?>" name="iwb_settings[register_form_fields][show_on_list][]"/>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="require_field" <?php echo isset($field['require_field']) ? 'checked="checked"' : ''; ?> class="require_field"/>
                                        <input class="iwb_field_val" type="hidden" value="<?php echo isset($field['require_field']) ? $field['require_field'] : ''; ?>" name="iwb_settings[register_form_fields][require_field][]"/>
                                    </td>
                                    <td>
                                        <?php if ($field['name'] != 'email' && $field['name'] != 'first_name' && $field['name'] != 'last_name' && $field['name'] != 'phone' && $field['name'] != 'address'): ?>
                                            <span class="button remove_field"><?php _e('Remove', 'inwavethemes'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><?php _e('Order', 'inwavethemes'); ?></th>
                            <th><?php _e('Label', 'inwavethemes'); ?></th>
                            <th><?php _e('Name', 'inwavethemes'); ?></th>
                            <th><?php _e('Type', 'inwavethemes'); ?></th>
                            <th><?php _e('Default Value', 'inwavethemes'); ?></th>
                            <th><abbr title="<?php _e('Show on List', 'inwavethemes'); ?>"><?php _e('SL', 'inwavethemes'); ?></abbr></th>
                            <th><abbr title="<?php _e('Require Field', 'inwavethemes'); ?>"><?php _e('RF', 'inwavethemes'); ?></abbr></th>
                            <th><?php _e('Actions', 'inwavethemes'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="button-add-field">
                    <span class="button add-rgister-field"><?php _e('Add new field', 'inwavethemes'); ?></span>
                </div>
            </div>
            <div class="iw-tab-item-content iw-hidden">
                <div class="payment-setting-wrap">
                    <?php
                    $payment = isset($iwb_settings['iwb_payment']) ? $iwb_settings['iwb_payment'] : array();
                    $paypal = isset($payment['paypal']) ? $payment['paypal'] : '';
                    ?>
                    <table class="list-table">
                        <thead>
                            <tr>
                                <th colspan="3"><?php _e('PAYPAL', 'inwavethemes'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="the-list">
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Paypal email', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <input class="iwe-paypal-email" value="<?php echo isset($paypal['email']) ? $paypal['email'] : ''; ?>" type="text" placeholder="<?php echo __('example@domain.com', 'inwavethemes'); ?>" name="iwb_settings[iwb_payment][paypal][email]"/>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Paypal email to use in payment.  Disable if empty', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Test mode', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <select name="iwb_settings[iwb_payment][paypal][test_mode]">
                                        <option value="0"<?php echo isset($paypal['test_mode']) && $paypal['test_mode'] == 0 ? ' selected="selected"' : ''; ?>><?php _e('No', 'inwavethemes'); ?></option>
                                        <option value="1"<?php echo isset($paypal['test_mode']) && $paypal['test_mode'] == 1 ? ' selected="selected"' : ''; ?>><?php _e('Yes', 'inwavethemes'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Enable test mode for paypal checkout', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="iw-tab-item-content iw-hidden iw-email">
                <?php $email_template = $iwb_settings['email_template']; ?>
                <div class="iw-tabs accordion day">
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header active">
                            <div class="iw-accordion-title"><span><?php echo __('Order created', 'inwavethemes'); ?></span></div>
                        </div>
                        <div class="iw-accordion-content">
                            <table class="list-table"  style="width: 100%">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="iwb_settings[email_template][order_created][enable]" value="1" <?php echo isset($email_template['order_created']['enable']) && $email_template['order_created']['enable'] == '1' ? 'checked' : ''; ?>/>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_created][title]" value="<?php echo isset($email_template['order_created']['title']) ? $email_template['order_created']['title'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Recipient(s)', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_created][recipients]" value="<?php echo isset($email_template['order_created']['recipients']) ? $email_template['order_created']['recipients'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="iwb_settings[email_template][order_created][content]"><?php echo isset($email_template['order_created']['content']) ? $email_template['order_created']['content'] : ''; ?></textarea>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[iwb_site_name]</strong>: <span><?php _e('Name of your site', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_first_name]</strong>: <span><?php _e('Customer first name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_last_name]</strong>: <span><?php _e('Customer last name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_customer_email]</strong>: <span><?php _e('Customer email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_admin_email]</strong>: <span><?php _e('Admin email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_link]</strong>: <span><?php _e('Booking order view infomation link', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_code]</strong>: <span><?php _e('Booking order code', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_id]</strong>: <span><?php _e('Booking order id', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header active">
                            <div class="iw-accordion-title"><span><?php echo __('Order on hold', 'inwavethemes'); ?></span></div>
                        </div>
                        <div class="iw-accordion-content">
                            <table class="list-table"  style="width: 100%">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="iwb_settings[email_template][order_onhold][enable]" value="1" <?php echo isset($email_template['order_onhold']['enable']) && $email_template['order_onhold']['enable'] == '1' ? 'checked' : ''; ?>/>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_onhold][title]" value="<?php echo isset($email_template['order_onhold']['title']) ? $email_template['order_onhold']['title'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Recipient(s)', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_onhold][recipients]" value="<?php echo isset($email_template['order_onhold']['recipients']) ? $email_template['order_onhold']['recipients'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="iwb_settings[email_template][order_onhold][content]"><?php echo isset($email_template['order_onhold']['content']) ? $email_template['order_onhold']['content'] : ''; ?></textarea>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[iwb_site_name]</strong>: <span><?php _e('Name of your site', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_first_name]</strong>: <span><?php _e('Customer first name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_last_name]</strong>: <span><?php _e('Customer last name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_customer_email]</strong>: <span><?php _e('Customer email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_admin_email]</strong>: <span><?php _e('Admin email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_link]</strong>: <span><?php _e('Booking order view infomation link', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_code]</strong>: <span><?php _e('Booking order code', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_id]</strong>: <span><?php _e('Booking order id', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header active">
                            <div class="iw-accordion-title"><span><?php echo __('Order Cancelled', 'inwavethemes'); ?></span></div>
                        </div>
                        <div class="iw-accordion-content">
                            <table class="list-table" style="width: 100%">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="iwb_settings[email_template][order_cancelled][enable]" value="1" <?php echo isset($email_template['order_cancelled']['enable']) && $email_template['order_cancelled']['enable'] == '1' ? 'checked' : ''; ?>/>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_cancelled][title]" value="<?php echo isset($email_template['order_cancelled']['title']) ? $email_template['order_cancelled']['title'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Recipient(s)', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_cancelled][recipients]" value="<?php echo isset($email_template['order_cancelled']['recipients']) ? $email_template['order_cancelled']['recipients'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="iwb_settings[email_template][order_cancelled][content]"><?php echo isset($email_template['order_cancelled']['content']) ? $email_template['order_cancelled']['content'] : ''; ?></textarea>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[iwb_site_name]</strong>: <span><?php _e('Name of your site', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_first_name]</strong>: <span><?php _e('Customer first name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_last_name]</strong>: <span><?php _e('Customer last name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_customer_email]</strong>: <span><?php _e('Customer email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_admin_email]</strong>: <span><?php _e('Admin email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_link]</strong>: <span><?php _e('Booking order view infomation link', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_code]</strong>: <span><?php _e('Booking order code', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_id]</strong>: <span><?php _e('Booking order id', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header active">
                            <div class="iw-accordion-title"><span><?php echo __('Order Completed', 'inwavethemes'); ?></span></div>
                        </div>
                        <div class="iw-accordion-content">
                            <table class="list-table"  style="width: 100%">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Enable', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="iwb_settings[email_template][order_completed][enable]" value="1" <?php echo isset($email_template['order_completed']['enable']) && $email_template['order_completed']['enable'] == '1' ? 'checked' : ''; ?>/>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_completed][title]" value="<?php echo isset($email_template['order_completed']['title']) ? $email_template['order_completed']['title'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Recipient(s)', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="iwb_settings[email_template][order_completed][recipients]" value="<?php echo isset($email_template['order_completed']['recipients']) ? $email_template['order_completed']['recipients'] : ''; ?>" />
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Email content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="iwb_settings[email_template][order_completed][content]"><?php echo isset($email_template['order_completed']['content']) ? $email_template['order_completed']['content'] : ''; ?></textarea>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[iwb_site_name]</strong>: <span><?php _e('Name of your site', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_first_name]</strong>: <span><?php _e('Customer first name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_last_name]</strong>: <span><?php _e('Customer last name', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_customer_email]</strong>: <span><?php _e('Customer email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_admin_email]</strong>: <span><?php _e('Admin email', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_link]</strong>: <span><?php _e('Booking order view infomation link', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_code]</strong>: <span><?php _e('Booking order code', 'inwavethemes'); ?></span><br/>
                                            <strong>[iwb_order_id]</strong>: <span><?php _e('Booking order id', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="action" value="iwBookingSaveSettings"/>
    <div class="iwe-save-settings">
        <input disabled="disabled" class="button" type="submit" value="Save"/>
    </div>
</form>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#sortable tbody").sortable();
            $("#sortable tbody .iwe-sortable-cell").disableSelection();
        });
    })(jQuery);
</script>
