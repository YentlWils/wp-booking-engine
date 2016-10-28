<?php
/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created May 12, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of discount
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Discounts', 'inwavethemes'); ?>
        <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=discount/addnew'); ?>"><?php echo __("Add New"); ?></a>
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('sponsor-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
    </h2>
    <form id="sponsor-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="iwBookingDeleteDiscounts"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="width: 5%">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', IW_TEXT_DOMAIN); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" class="manage-column column-categories" style="width: 20%"><?php echo __('Name', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" class="manage-column column-categories" style="width: 20%"><?php echo __('Code', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" class="manage-column column-categories" style="width: 10%"><?php echo __('Time Start', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" class="manage-column column-categories" style="width: 10%"><?php echo __('Time End', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" class="manage-column column-tags" style="width: 10%"><?php echo __('Value', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" class="manage-column column-title" style="width: 10%"><?php echo __('Published', IW_TEXT_DOMAIN); ?></th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($discounts)) {
                    $ultility = new iwBookingUtility();

                    foreach ($discounts as $discount) {
                        $discount = new iwBookingDiscount($discount->id);
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo $discount->getId(); ?>"/>
                    <div class="locked-indicator"></div>
                    </th>
                    <td>
                        <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=service/edit&id=' . $discount->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>">
                            <strong><?php echo stripslashes($discount->getName()); ?></strong>
                        </a>
                        <div class="row-actions">
                            <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=discount/edit&id=' . $discount->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                            <a class="submitdelete" title="<?php echo __('Move this item to the Trash', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=iwBookingDeleteDiscount&id=" . $discount->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                        </div>
                    </td>
                    <td><?php echo $discount->getDiscount_code(); ?></td>
                    <td><?php echo date('m/d/Y', $discount->getTime_start()); ?></td>
                    <td><?php echo date('m/d/Y', $discount->getTime_end()); ?></td>
                    <td><?php echo $discount->getType() == 'fixed' ? $ultility->getMoneyFormated($discount->getValue()) : $discount->getValue().'%'; ?></td>
                    <td><?php echo $discount->getStatus() == 0 ? __('No', IW_TEXT_DOMAIN) : __('Yes', IW_TEXT_DOMAIN); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="6">
                        <?php
                        $page_list = $paging->pageList(isset($_GET['pagenum']) ? $_GET['pagenum'] : 1, $pages);
                        echo $page_list;
                        ?>
                    </td>
                </tr> 
            <?php } else { ?>
                <tr>
                    <td colspan="6"><?php echo __('No result'); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>
