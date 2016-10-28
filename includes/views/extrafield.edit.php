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
        <?php if ($extrafield->getId()): ?>
            <h2 class="bt-title header-text"><?php echo __('Edit Room Extrafield'); ?>
                <a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iw_booking&page=room-extra/addnew'); ?>"><?php echo __("Add New Other"); ?></a>
            </h2>
        <?php else: ?>
            <h3 class="header-text"><?php echo __("Add new Room Extrafield", 'inwavethemes'); ?></h3>
        <?php endif; ?>
        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Name', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input required name="name"  type="text" value="<?php echo ($extrafield->getName()) ? $extrafield->getName() : ''; ?>" />
                    </td>
                    <td>
                        <span class="description"><?php _e('Name of booking extrafield', 'inwavethemes'); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Icon', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input name="icon"  type="text" placeholder="Eg: home" value="<?php echo ($extrafield->getIcon()) ? $extrafield->getIcon() : ''; ?>" />
                    </td>
                    <td>
                        <span class="description"><?php _e('Icon name using font Awesome', 'inwavethemes'); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <div><?php echo __('Type', 'inwavethemes'); ?></div>
                    </td>
                    <td>
                        <?php
                        $data = array(
                            array('value' => 'textarea', 'text' => __('Textarea')),
                            array('value' => 'link', 'text' => __('Link')),
                            array('value' => 'image', 'text' => __('Image')),
                            array('value' => 'measurement', 'text' => __('Measurement')),
                            array('value' => 'text', 'text' => __('Text')),
                            array('value' => 'dropdown_list', 'text' => __('Dropdown list')),
                            array('value' => 'date', 'text' => __('Date'))
                        );
                        $extrafield_data = 'required';
                        echo iwBookingUtility::selectFieldRender('field_type', 'type', $extrafield->getType(), $data, '', '', false, $extrafield_data, $extrafield->getId() != NULL);
                        ?>
                    </td>
                    <td>
                        <span class="description"><?php _e('Extrafield type', 'inwavethemes'); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <div><?php echo __('Default value', 'inwavethemes'); ?></div>
                    </td>
                    <td>
                        <div class="field-type field-textarea" <?php echo $extrafield->getType() != 'textarea' && $extrafield->getType() ? 'style="display:none;"' : ''; ?>>
                            <textarea placeholder="<?php echo __('Some Text value here'); ?>" cols="25" rows="4" name="text_value"><?php echo $extrafield->getType() == 'textarea' ? htmlentities(stripslashes($extrafield->getDefault_value())) : ''; ?></textarea>
                            <div style="clear: both"></div>
                        </div>

                        <div class="field-type field-link" <?php echo $extrafield->getType() != 'link' ? 'style="display:none;"' : ''; ?>>
                            <?php
                            $link_text = $link_value = $link_target = '';
                            if ($extrafield->getType() == 'link') {
                                $link = unserialize($extrafield->getDefault_value());
                                $link_text = htmlentities(stripslashes($link['link_value_text']));
                                $link_value = htmlentities(stripslashes($link['link_value_link']));
                                $link_target = $link['link_value_target'];
                            }
                            ?>
                            <input placeholder="<?php echo __('URL'); ?>" name="link_value_link" value="<?php echo ($link_value) ? $link_value : ''; ?>" type="text"/>
                            <input placeholder="<?php echo __('Text'); ?>" name="link_value_text" value="<?php echo ($link_text) ? $link_text : ''; ?>" type="text"/>
                            <?php
                            $datas = array(
                                array('value' => '_blank', 'text' => 'Blank'),
                                array('value' => '_self', 'text' => 'Self')
                            );
                            echo iwBookingUtility::selectFieldRender('link_value_target', 'link_value_target', $link_target, $datas, 'Select Target', '', false);
                            ?>
                            <div style="clear: both"></div>
                        </div>

                        <div class="field-type field-measurement" <?php echo $extrafield->getType() != 'measurement' ? 'style="display:none;"' : ''; ?>>
                            <?php
                            $measurement_data = $measurement_value = $measurement_unit = '';
                            if ($extrafield->getType() == 'measurement') {
                                $measurement_data = unserialize($extrafield->getDefault_value());
                                $measurement_value = htmlentities(stripslashes($measurement_data['measurement_value']));
                                $measurement_unit = htmlentities(stripslashes($measurement_data['measurement_unit']));
                            }
                            ?>
                            <input placeholder="<?php echo __('Value'); ?>" name="measurement_value" value="<?php echo ($measurement_value) ? $measurement_value : ''; ?>" type="text"/>
                            <input placeholder="<?php echo __('Unit'); ?>" name="measurement_unit" value="<?php echo ($measurement_unit) ? $measurement_unit : ''; ?>" type="text"/>
                            <br/><span class="description"><?php echo __('Enter unit. Example: $, Kg, m2'); ?></span>
                            <div style="clear: both"></div>
                        </div>

                        <div class="field-type field-text" <?php echo $extrafield->getType() != 'text' ? 'style="display:none;"' : ''; ?>>
                            <input placeholder="<?php echo __('Text value'); ?>" name="string_value"  value="<?php echo $extrafield->getType() == 'text' ? htmlentities(stripslashes($extrafield->getDefault_value())) : ''; ?>" type="text"/>
                            <div style="clear: both"></div>
                        </div>

                        <div class="field-type field-dropdown_list" <?php echo $extrafield->getType() != 'dropdown_list' ? 'style="display:none;"' : ''; ?>>
                            <?php
                            $drop_value_data = $drop_value = $drop_multiselect = null;
                            if ($extrafield->getType() == 'dropdown_list') {
                                $drop_value_data = unserialize($extrafield->getDefault_value());
                                $drop_value = htmlentities(stripslashes($drop_value_data[0]));
                                $drop_multiselect = $drop_value_data[1];
                            }
                            ?>

                            <textarea placeholder="<?php echo __('VALUE|TEXT|1'); ?>" cols="25" rows="4" name="drop_value"><?php echo ($drop_value) ? $drop_value : ''; ?></textarea>
                            <br/><span class="description"><?php echo __('Multiple options are separated by newline with syntax: Value|Text|Selected(1 or 0). Example:<br />VALUE_1|TEXT_1|1<br />VALUE_2|TEXT_2|0<br />VALUE_3|TEXT_3|1'); ?></span><br/>
                            <br/><input type="checkbox" style="min-width: initial!important;" value="1" <?php echo ($drop_multiselect == 1) ? 'checked="checked"' : ""; ?> name="drop_multiselect"/> Multiple selected
                            <div style="clear: both"></div>
                        </div>

                        <div class="field-type field-image" <?php echo $extrafield->getType() != 'image' ? 'style="display:none;"' : ''; ?>>
                            <div class="iw-image-field">
                                <?php
                                $html = '';
                                if ($extrafield->getType() == 'image' && $extrafield->getDefault_value()) {
                                    $img = wp_get_attachment_image_src($extrafield->getDefault_value());
                                    if ($img) {
                                        $img_src = $img[0];
                                        list($iWidth, $iHeight) = getimagesize($img_src);
                                        if ($iWidth > $iHeight) {
                                            $style = ' style="height:100%;"';
                                        } else {
                                            $style = ' style="width:100%;"';
                                        }
                                        $html = '<div class="close-overlay"><span class="image-delete"><i class="fa fa-times"></i></span></div><img' . $style . ' src="' . $img_src . '" alt=""/>';
                                    }
                                }
                                ?>
                                <div class="image-preview <?php echo $html ? '' : 'hidden'; ?>"><?php echo ($html) ? $html : ''; ?></div>
                                <div class="image-add-image"><span><i class="fa fa-plus"></i></span></div>
                            </div>
                            <input type="hidden" value="<?php echo $extrafield->getType() == 'image' ? $extrafield->getDefault_value() : ''; ?>" name="image" class="iw-field iw-image-field-data"/>
                            <div style="clear: both;"></div>
                        </div>

                        <div class="field-type field-date" <?php echo $extrafield->getType() != 'date' ? 'style="display:none;"' : ''; ?>>
                            <input name="date_value" value="<?php echo $extrafield->getType() == 'date' ? $extrafield->getDefault_value() : ''; ?>" type="text"/>
                            <div style="clear: both"></div>
                        </div>
                    </td>
                    <td>
                        <span class="description"></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Categories', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $cats = array();
                        if ($extrafield->getCategories()) {
                            foreach ($extrafield->getCategories() as $cat) {
                                $cats[] = $cat->getId();
                            }
                        }
                        echo iwBookingUtility::categoryField('categories', $cats);
                        ?>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                        <label><?php echo __('Description', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <textarea rows="6" name="description"><?php echo ($extrafield->getDescription()) ? $extrafield->getDescription() : ''; ?></textarea>
                    </td>
                    <td>
                        <span class="description"><?php _e('More information of extrafield', 'inwavethemes'); ?></span>
                    </td>
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
                        echo iwBookingUtility::selectFieldRender('published', 'published', $extrafield->getPublished(), $publish_data, '', '', false);
                        ?>
                    </td>
                    <td>
                        <span class="description"></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td class="label">
                    </td>
                    <td>
                        <?php if ($extrafield->getId()): ?>
                            <input type="hidden" name="id" value="<?php echo $extrafield->getId(); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="action" value="iwBookingSaveExtrafield">
                        <input class="button" type="submit" style="min-width: initial;" value="<?php echo __("Save"); ?>"/>
                    </td>
                    <td>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<script tpe="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#field_type').change(function () {
                var select = $(this).val();
                $('.field-type').hide();
                $('.field-' + select).show();
            });

            $('input[name="date_value"]').datepicker({
                dateFormat: 'dd-mm-yy'
            });

            var frame;

            // ADD IMAGE LINK
            $('.iw-image-field div.image-add-image').live('click', function (event) {
                var e_target = $(this);

                event.preventDefault();

                // Create a new media frame
                frame = wp.media({
                    state: 'insert',
                    frame: 'post',
                    library: {
                        type: 'image'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                }).open();

                frame.menu.get('view').unset('featured-image');

                frame.toolbar.get('view').set({
                    insert: {
                        style: 'primary',
                        text: 'Insert',
                        click: function () {
                            // Get media attachment details from the frame state
                            var attachment = frame.state().get('selection').first().toJSON();

                            // Send the attachment URL to our custom image input field.
                            e_target.parent().find('div.image-preview').html('<div class="close-overlay"><span class="image-delete"><i class="fa fa-times"></i></span></div><img src="' + attachment.url + '" alt=""/>').removeClass('hidden');
                            var imgElement = e_target.parent().find('div.image-preview img');
                            if (imgElement.height() > imgElement.width()) {
                                imgElement.css('width', '100%');
                            } else {
                                imgElement.css('height', '100%');
                            }

                            // Send the attachment id to our hidden input
                            e_target.parents('.field-image').find('.iw-field.iw-image-field-data').val(attachment.id);
                            frame.close();
                        }
                    } // end insert
                });
            });

            // DELETE IMAGE LINK
            $('.iw-image-field .image-delete').live('click', function (event) {
                var e_target = $(this);

                event.preventDefault();

                // Clear out the preview image
                e_target.parents('.iw-image-field').find('div.image-preview').addClass('hidden').html('');

                // Delete the image id from the hidden input
                e_target.parents('.field-image').find('.iw-field.iw-image-field-data').val('');

            });

        });
    })(jQuery);
</script>