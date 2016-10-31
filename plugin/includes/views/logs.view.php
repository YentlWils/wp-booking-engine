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
 * Description of logs
 *
 * @developer duongca
 */
?>
<table class="list-table">
    <thead>
        <tr>
            <th colspan="2"><?php _e('Log detail', 'inwavethemes'); ?></th>
        </tr>
    </thead>
    <tbody class="the-list">
        <tr class="alternate">
            <td>
                <label><?php echo __('ID', 'inwavethemes'); ?></label>
            </td>
            <td>
                <span><?php echo $log->getId(); ?></span>
            </td>
        </tr>
        <tr class="alternate">
            <td>
                <label><?php echo __('Log type', 'inwavethemes'); ?></label>
            </td>
            <td>
                <span><?php echo $log->getLog_type(); ?></span>
            </td>
        </tr>
        <tr class="alternate">
            <td>
                <label><?php echo __('Message', 'inwavethemes'); ?></label>
            </td>
            <td>
                <span><?php echo $log->getMessage(); ?></span>
            </td>
        </tr>
        <tr class="alternate">
            <td>
                <label><?php echo __('Log time', 'inwavethemes'); ?></label>
            </td>
            <td>
                <span><?php echo date('m/d/Y H:i:s', $log->getTimestamp()); ?></span>
            </td>
        </tr>
        <tr class="alternate">
            <td>
                <label><?php echo __('Link', 'inwavethemes'); ?></label>
            </td>
            <td>
                <span><?php
                    if ($log->getLink()) {
                        $link = explode('|', $log->getLink());
                        echo '<a href="' . $link[0] . '">' . $link[0] . '</a>';
                    }
                    ?></span>
            </td>
        </tr>
        <tr class="alternate">
            <td>
                
            </td>
            <td>
                <a class="button" href="<?php echo admin_url("admin-post.php?action=iwBookingDeleteLog&id=" . $log->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                <a class="button" href="<?php echo admin_url("edit.php?post_type=iw_booking&page=logs"); ?>"><?php echo __('Back to list', 'inwavethemes'); ?></a>
            </td>
        </tr>
    </tbody>
</table>