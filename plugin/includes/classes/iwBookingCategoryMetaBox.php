<?php
/*
 * @package Inwave Booking
 * @version 1.0.0
 * @created Aug 6, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwBookingCategoryMetaBox
 *
 * @developer duongca
 */
class iwBookingCategoryMetaBox {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        // Add the fields to the "Class" taxonomy, using our callback function  
        add_action('booking_category_add_form_fields', array($this, 'booking_category_metabox_add'));
        add_action('booking_category_edit_form_fields', array($this, 'booking_category_metabox_edit'));

        // Save the changes made on the "Class" taxonomy, using our callback function  
        add_action('edited_booking_category', array($this, 'save_booking_category'), 10, 2);
        add_action('created_booking_category', array($this, 'save_booking_category'));
    }

    function booking_category_metabox_add($tag) {
        ?>
        <div class="form-field">
            <label for="category-icon"><?php _e("Icon") ?></label>
            <input type="text" name="category-icon" placeholder="Eg: home" placeholder="Eg: home" />
            <p class="description"><?php _e('Icon name using font Awesome', 'inwavethemes'); ?></p>
        </div>
        <div class="form-field">
            <div class="field-type field-image">
                <div class="iw-image-field">
                    <div class="image-preview hidden"></div>
                    <div class="image-add-image"><span><i class="fa fa-plus"></i></span></div>
                </div>
                <input type="hidden" value="" name="image" class="iw-field iw-image-field-data"/>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div class="form-field">
            <label for="category-status"><?php _e("Status") ?></label>
            <?php
            $category_status = array(
                array('text' => __('Published', IW_TEXT_DOMAIN), 'value' => '1'),
                array('text' => __('Unpublished', IW_TEXT_DOMAIN), 'value' => '0')
            );
            echo iwBookingUtility::selectFieldRender('category-status', 'category-status', '', $category_status, '', '', FALSE);
            ?>
        </div>
        <script type="text/javascript">
            (function ($) {
                var frame;
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
                                e_target.parent().parent().find('.iw-field.iw-image-field-data').val(attachment.id);
                                frame.close();
                            }
                        } // end insert
                    });
                });


                // DELETE IMAGE LINK
                $('.iw-image-field .image-delete').live('click', function (event) {
                    var e_target = $(this);

                    event.preventDefault();
                    // Delete the image id from the hidden input
                    e_target.parents('.field-image').find('.iw-field.iw-image-field-data').val('');
                    // Clear out the preview image
                    e_target.parents('.iw-image-field').find('div.image-preview').addClass('hidden').html('');


                });
            })(jQuery);
        </script>
        <?php
    }

    function booking_category_metabox_edit($tag) {
        $icon = get_option("iw_booking_category_icon_" . $tag->term_id);
        $image = get_option("iw_booking_category_image_" . $tag->term_id);
        $status = get_option("iw_booking_category_status_" . $tag->term_id);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="category-icon"><?php _e("Icon") ?></label></th>
            <td>
                <input type="text" name="category-icon" value="<?php echo esc_attr($icon); ?>" placeholder="Eg: home" placeholder="Eg: home" />
                <p class="description"><?php _e('Icon name using font Awesome', 'inwavethemes'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"></th>
            <td>
                <div class="field-type field-image">
                    <?php
                    $img = wp_get_attachment_image_src($image);
                    $style = '';
                    if ($img) {
                        list($width, $height) = getimagesize($img[0]);
                        if ($width > $height) {
                            $style = ' style="height:100%;"';
                        } else {
                            $style = ' style="width:100%;"';
                        }
                    }
                    ?>
                    <div class="iw-image-field">
                        <div class="image-preview <?php
                        if ($img) {
                            echo '';
                        } else {
                            echo 'hidden';
                        }
                        ?>">
                                 <?php if ($img) { ?>
                                <div class="close-overlay">
                                    <span class="image-delete">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div>
                                <img alt="" src="<?php echo esc_attr($img[0]); ?>" <?php echo esc_html($style); ?>>
                            <?php } ?>
                        </div>
                        <div class="image-add-image"><span><i class="fa fa-plus"></i></span></div>
                    </div>
                    <input type="hidden" value="<?php echo esc_attr($image); ?>" name="image" class="iw-field iw-image-field-data"/>
                    <div style="clear: both;"></div>
                </div>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="category-status"><?php _e("Status") ?></label></th>
            <td>
                <?php
                $category_status = array(
                    array('text' => __('Published', IW_TEXT_DOMAIN), 'value' => '1'),
                    array('text' => __('Unpublished', IW_TEXT_DOMAIN), 'value' => '0')
                );
                echo iwBookingUtility::selectFieldRender('category-status', 'category-status', $status, $category_status, '', '', FALSE);
                ?>
            </td>
        </tr>
        <script type="text/javascript">
            (function ($) {
                var frame;
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
                                e_target.parent().parent().find('.iw-field.iw-image-field-data').val(attachment.id);
                                frame.close();
                            }
                        } // end insert
                    });
                });


                // DELETE IMAGE LINK
                $('.iw-image-field .image-delete').live('click', function (event) {
                    var e_target = $(this);

                    event.preventDefault();
                    // Delete the image id from the hidden input
                    e_target.parents('.field-image').find('.iw-field.iw-image-field-data').val('');
                    // Clear out the preview image
                    e_target.parents('.iw-image-field').find('div.image-preview').addClass('hidden').html('');


                });
            })(jQuery);
        </script>
        <?php
    }

    function save_booking_category($term_id) {
        if (isset($_POST['category-icon'])) {
            update_option("iw_booking_category_icon_" . $term_id, $_POST['category-icon']);
        }
        if (isset($_POST['image'])) {
            update_option("iw_booking_category_image_" . $term_id, $_POST['image']);
        }
        if (isset($_POST['category-status'])) {
            update_option("iw_booking_category_status_" . $term_id, $_POST['category-status']);
        }
    }

}

