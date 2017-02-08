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
        <?php if ($offDay->getId()): ?>
            <h2 class="bt-title header-text"><?php echo __('Edit Discount'); ?>
                <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=off-day/addnew'); ?>"><?php echo __("Add New Other"); ?></a>
            </h2>
        <?php else: ?>
            <h3 class="header-text"><?php echo __("Add new Off Day", 'inwavethemes'); ?></h3>
        <?php endif; ?>

        <table class="list-table">
            <tbody class="the-list">
            <tr class="alternate">
                <td class="label">
                    <label><?php echo __('Name', 'inwavethemes'); ?></label>
                </td>
                <td>
                    <input name="note" value="<?php echo ($offDay->getNote()) ? $offDay->getNote() : ''; ?>"/>
                </td>
                <td>&nbsp;</td>
            </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Time Start', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="text" required="required" class="input-date" value="<?php echo $offDay->getTime_start() ? date('d-m-Y', $offDay->getTime_start()) : date('d-m-Y', time()); ?>" name="time_start"/>
                    </td>
                    <td><span class="description"></span></td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Time End', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="text" required="required" class="input-date" value="<?php echo $offDay->getTime_end() ? date('d-m-Y', $offDay->getTime_end()) : date('d-m-Y', time()); ?>" name="time_end"/>
                    </td>
                    <td><span class="description"></span></td>
                </tr>

                <tr class="alternate">
                    <td class="label">&nbsp;</td>
                    <td>
                        <?php if ($offDay->getId()): ?>
                            <input type="hidden" name="id" value="<?php echo $offDay->getId(); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="action" value="iwBookingSaveOffDay">
                        <input type="submit" class="button" style="min-width: initial;" value="<?php echo __("Save"); ?>"/>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>