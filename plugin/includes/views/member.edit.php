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
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <h2 class="in-title"><?php echo __('Edit member', 'inwavethemes'); ?></h2>
        <table class="list-table">
            <tbody class="the-list">
                <?php
                    foreach ($iwb_settings['register_form_fields'] as $field) :
                        $value = isset($member->{$field['name']}) ? $member->{$field['name']} : '';
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
                    endforeach;
                ?>
                <tr class="alternate">
                    <td></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $member->id; ?>"/>
                        <input type="hidden" name="action" value="saveBookingMember"/>
                        <input type="submit" value="<?php _e('Save Update', 'inwavethemes'); ?>" class="button"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>