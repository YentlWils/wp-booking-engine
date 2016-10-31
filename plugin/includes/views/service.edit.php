<?php
/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created Mar 6, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of exGroup
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap booking-service">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <?php if ($service->getId()): ?>
            <h2 class="bt-title header-text"><?php echo __('Edit Service', IW_TEXT_DOMAIN); ?>
                <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=service/addnew'); ?>"><?php echo __("Add New Other"); ?></a>
            </h2>
        <?php else: ?>
            <h3 class="header-text"><?php echo __("Add new Service", IW_TEXT_DOMAIN); ?></h3>
        <?php endif; ?>

        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Name', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input name="name"  type="text" value="<?php echo ($service->getName()) ? $service->getName() : ''; ?>" />
                    </td>
                    <td>
                        <span class="description"><?php _e('Name of Service', 'inwavethemes'); ?></span>
                    </td>
                </tr>
                <tr class="alternate service-type">
                    <td class="label">
                        <label><?php echo __('Service type', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $service_types = array(
                            array('text'=>__('Basic', 'inwavethemes'), 'value'=>'basic'),
                            array('text'=>__('Premium', 'inwavethemes'), 'value'=>'premium')
                        );
                        echo iwBookingUtility::selectFieldRender('', 'type', $service->getType(), $service_types, '', '', FALSE);
                        ?>
                    </td>
                    <td>
                        <span class="description"></span>
                    </td>
                </tr>
                <tr class="alternate service-price">
                    <td class="label">
                        <label><?php echo __('Price', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input name="price"  type="text" value="<?php echo ($service->getPrice()) ? $service->getPrice() : ''; ?>" />
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Description', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <textarea cols="25" rows="4" name="description"><?php echo ($service->getDescription()) ? $service->getDescription() : ''; ?></textarea>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Status', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <select name="status">
                            <option <?php echo ($service->getStatus() == '1') ? 'selected = "selected"' : ''; ?> value="1"><?php echo __('Published'); ?></option>
                            <option <?php echo ($service->getStatus() == '0') ? 'selected = "selected"' : ''; ?> value="0"><?php echo __('Unpublished'); ?></option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Rate', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <select name="rate">
                            <option <?php echo ($service->getRate() == '1') ? 'selected = "selected"' : ''; ?> value="1"><?php echo __('Fixed'); ?></option>
                            <option <?php echo ($service->getRate() == '0') ? 'selected = "selected"' : ''; ?> value="0"><?php echo __('By Night'); ?></option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="alternate">
                    <td class="label">&nbsp;</td>
                    <td>
                        <?php if ($service->getId()): ?>
                            <input type="hidden" name="id" value="<?php echo $service->getId(); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="action" value="iwBookingSaveService">
                        <input type="submit" class="button" style="min-width: initial;" value="<?php echo __("Save", 'inwavethemes'); ?>"/>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
