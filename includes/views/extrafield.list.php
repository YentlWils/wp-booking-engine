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
 * Description of extrafield
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
    echo $_SESSION['bt_message'];
    unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Extrafields', 'inwavethemes'); ?>
        <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=room-extra/addnew'); ?>"><?php echo __("Add New"); ?></a>
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('speakers-form').submit();
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
    <form id="speakers-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="iwBookingDeleteExtras"/>
        <table class="iwbooking-list-table wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="width: 5%">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="name" class="manage-column column-author" style="width: 30%"><?php echo __('Name', 'inwavethemes'); ?></th>
                    <th scope="col" id="type" class="manage-column column-categories" style="width: 20%"><?php echo __('Type', 'inwavethemes'); ?></th>
                    <th scope="col" id="categories" class="manage-column column-categories" style="width: 20%"><?php echo __('Categories', 'inwavethemes'); ?></th>
                    <th scope="col" id="Published" class="manage-column column-categories" style="width: 15%"><?php echo __('Published', 'inwavethemes'); ?></th>
                    <th scope="col" id="title" class="manage-column column-title sortable desc" style="width: 10%">
                       <span>ID</span>
                    </th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($extrafields)) {
                    foreach ($extrafields as $extra) {
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo $extra->getId(); ?>"/>
                    <div class="locked-indicator"></div>
                    </th>
                    <td>
                        <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=room-extra/edit&id=' . $extra->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>">
                            <strong><?php echo stripslashes($extra->getName()); ?></strong>
                        </a>
                        <div class="row-actions">
                            <a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=room-extra/edit&id=' . $extra->getId()); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                            <a class="submitdelete" title="<?php echo __('Move this item to the Trash', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=iwBookingDeleteExtra&id=" . $extra->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                        </div>
                    </td>
                    <td><?php echo $extra->getType(); ?></td>
                    <td>
                        <?php
						$cats = $extra->getCategories();
                        if (empty($cats)) {
                            _e('All Categories', IW_TEXT_DOMAIN);
                        } else {
                            $categories = array();
                            foreach ($cats as $key => $value) {
                                if ($key <= 2) {
                                    $categories[] = '<a href="' . get_edit_term_link($value->getId(), 'booking_category', 'iw_booking') . '">' . $value->getName() . '</a>';
                                } else {
                                    $categories[] = __(',...', IW_TEXT_DOMAIN);
                                }
                            }
                            echo implode(', ', $categories);
                        }
                        ?>
                    </td>
                    <td><?php echo $extra->getPublished() == 1 ? __('Yes', IW_TEXT_DOMAIN) : __('No', IW_TEXT_DOMAIN); ?></td>
                    <td><?php echo $extra->getId(); ?></td>
                    </tr>
                <?php }
                ?>
                <tr>
                    <td colspan="7">
                        <?php
                        $page_list = $paging->pageList($_GET['pagenum'], $pages);
                        echo $page_list;
                        ?>
                    </td>
                </tr> 
            <?php } else { ?>
                <tr>
                    <td colspan="7"><?php echo __('No result'); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>
