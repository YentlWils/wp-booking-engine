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
 * Description of member
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Customers', 'inwavethemes'); ?>
        <!--<a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=customer/addnew'); ?>"><?php echo __("Add New"); ?></a>-->
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('payment-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
    </h2>
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" name="filter">
        <div class="iwe-filter tablenav top">
            <div class="alignleft">
                <label><?php _e('Filter', 'inwavethemes'); ?></label>
                <input type="text" name="keyword" value="<?php echo filter_input(INPUT_GET, 'keyword'); ?>" placeholder="<?php echo __('Input keyword to search', 'inwavethemes'); ?>"/>
            </div>
            <div class="alignleft">
                <input type="hidden" value="iwBookingFilter" name="action"/>
                <input class="button" type="submit" value="<?php _e('Search', 'inwavethemes'); ?>"/>
            </div>
        </div>
    </form>
    <form id="payment-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="deleteBookingMembers"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php
                    foreach ($field_to_show as $field) {
                        echo '<th>' . __($field[1], 'inwavethemes') . '</th>';
                    }
                    echo '<th>' . __('Action', 'inwavethemes') . '</th>';
                    ?>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($members)) {
                    foreach ($members as $member) {
                        ?>
                    <tr>
                        <th scope="row" class="check-column">
                            <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo $member->id; ?>"/>
                            <div class="locked-indicator"></div>
                        </th>
                    <?php
                    foreach ($field_to_show as $field) {
                        echo '<td>' . (isset($member->{$field[0]}) ? $member->{$field[0]} : '') . '</td>';
                    }
                    ?>
                        <td>
                            <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=customer/edit&id=' . $member->id); ?>" title="<?php echo __('Edit this member', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                            <a class="submitdelete" title="<?php echo __('Delete this member', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=deleteBookingMember&id=" . $member->id); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="<?php echo (count($field_to_show) + 2); ?>">
                        <?php
                        $page_list = $paging->pageList($_GET['pagenum'], $pages);
                        echo $page_list;
                        ?>
                    </td>
                </tr> 
            <?php } else {
                ?>
                <tr>
                    <td colspan="<?php echo (count($field_to_show) + 2); ?>"><?php echo __('No result', 'inwavethemes'); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>