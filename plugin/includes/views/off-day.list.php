<?php
/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Ma5 12, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of service: This is Page list service
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
    <h2 class="bt-title"><?php echo __('Off days', IW_TEXT_DOMAIN); ?>
        <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=off-day/addnew'); ?>"><?php echo __("Add New", IW_TEXT_DOMAIN); ?></a>
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('offDays-form').submit();
                return false;"><?php echo __("Delete", IW_TEXT_DOMAIN); ?></a>
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
    <form id="offDays-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="iwBookingDeleteOffDays"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="width: 5%">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', IW_TEXT_DOMAIN); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="categories" class="manage-column column-title" style="width: 20%"><?php echo __('Name', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" id="author" class="manage-column column-date sortable asc" style="width: 25%"><?php echo __('Start Date', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" id="author" class="manage-column column-date sortable asc" style="width: 20%"><?php echo __('End Date', IW_TEXT_DOMAIN); ?></th>
                    <th scope="col" id="title" class="manage-column column-title sortable desc" style="width: 10%">
                        <span>ID</span>
                    </th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($offDays)) {
                    $ultility = new iwBookingUtility();
                    foreach ($offDays as $offDay) {
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo $offDay->getId(); ?>"/>
                    <div class="locked-indicator"></div>
                    </th>
                    <td>
                        <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=off-day/edit&id=' . $offDay->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>">
                            <strong><?php echo $offDay->getNote(); ?></strong>
                        </a>
                        <div class="row-actions">
                            <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=off-day/edit&id=' . $offDay->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                            <a class="submitdelete" title="<?php echo __('Move this item to the Trash', 'inwavethemes'); ?>" onclick="javascript: return confirm('<?php _e('Are you sure do this?', 'inwavethemes'); ?>')" href="<?php echo admin_url("admin-post.php?action=iwBookingDeleteOffDay&id=" . $offDay->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                        </div>
                    </td>
                    <td>
                        
                            <?php echo date('d-m-Y', $offDay->getTime_start()) ?>

                    </td>
                    <td><?php echo date('d-m-Y', $offDay->getTime_end()); ?></td>

                    <td><?php echo $offDay->getId(); ?></td>
                    </tr>
                <?php } ?>
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
                    <td colspan="6"><?php echo __('No result', IW_TEXT_DOMAIN); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>

