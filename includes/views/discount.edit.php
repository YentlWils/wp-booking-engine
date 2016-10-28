<?php
/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created May 13, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of speaker
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <?php if ($discount->getId()): ?>
            <h2 class="bt-title header-text"><?php echo __('Edit Discount'); ?>
                <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=discount/addnew'); ?>"><?php echo __("Add New Other"); ?></a>
            </h2>
        <?php else: ?>
            <h3 class="header-text"><?php echo __("Add new Discount", 'inwavethemes'); ?></h3>
        <?php endif; ?>

        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Name', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input name="name" required="required" type="text" value="<?php echo ($discount->getName()) ? $discount->getName() : ''; ?>" />
                    </td>
                    <td>
                        <span class="description"><?php _e('Name of Discount', 'inwavethemes'); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Discount Code', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input name="discount_code" placeholder="<?php _e('SUN-123456789', IW_TEXT_DOMAIN); ?>" required="required" type="text" value="<?php echo ($discount->getDiscount_code()) ? $discount->getDiscount_code() : ''; ?>" />
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Type', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $typedata = array(
                            array('text' => __('Fixed', IW_TEXT_DOMAIN), 'value' => 'fixed'),
                            array('text' => __('Percent', IW_TEXT_DOMAIN), 'value' => 'percent')
                        );
                        echo iwBookingUtility::selectFieldRender('discount_type', 'type', $discount->getType(), $typedata, '', '', FALSE);
                        ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Value', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="number" value="<?php echo $discount->getValue(); ?>" name="value" required="required"/>
                    </td>
                    <td><span class="description"><?php _e('Value to discount', IW_TEXT_DOMAIN); ?></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Time Start', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="text" required="required" class="input-date" value="<?php echo $discount->getTime_start() ? date('m/d/Y', $discount->getTime_start()) : date('m/d/Y', time()); ?>" name="time_start"/>
                    </td>
                    <td><span class="description"></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Time End', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="text" required="required" class="input-date" value="<?php echo $discount->getTime_end() ? date('m/d/Y', $discount->getTime_end()) : date('m/d/Y', time()); ?>" name="time_end"/>
                    </td>
                    <td><span class="description"></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Amount', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="number" required="required" min="0" class="" value="<?php echo $discount->getAmount(); ?>" name="amount"/>
                    </td>
                    <td><span class="description"><?php _e('Number of discount code available.', IW_TEXT_DOMAIN); ?></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Description', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <textarea cols="25" rows="4" name="description"><?php echo ($discount->getDescription()) ? $discount->getDescription() : ''; ?></textarea>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Published', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $publish_data = array(
                            array('value' => '1', 'text' => 'Published'),
                            array('value' => '0', 'text' => 'Unpublished')
                        );
                        echo iwBookingUtility::selectFieldRender('published', 'status', $discount->getStatus(), $publish_data, '', '', false);
                        ?>
                    </td>
                    <td><span class="description"></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">&nbsp;</td>
                    <td>
                        <?php if ($discount->getId()): ?>
                            <input type="hidden" name="id" value="<?php echo $discount->getId(); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="action" value="iwBookingSaveDiscount">
                        <input type="submit" class="button" style="min-width: initial;" value="<?php echo __("Save"); ?>"/>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>