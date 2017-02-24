<?php
/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created Mar 13, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwDirectoryMetaBox
 *
 * @developer duongca
 */
class iwBookingMetaBox {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save'));
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        if ($post_type == 'iw_booking') {
            //remove categories block
            remove_meta_box('booking_categorydiv', 'iw_booking', 'side');
            add_meta_box(
                    'booking_category_box', __('Category', 'inwavethemes'), array($this, 'render_meta_box_booking_category'), $post_type, 'side', 'high'
            );
            add_meta_box(
                    'booking_basic_info', __('Basic Info', 'inwavethemes'), array($this, 'render_meta_box_basic_info'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'booking_image_gallery', __('Image Gallery', 'inwavethemes'), array($this, 'render_meta_box_image_gallery'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'booking_include_services', __('Include Services', 'inwavethemes'), array($this, 'render_meta_box_include_services'), $post_type, 'advanced', 'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save($post_id) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        /* @var $_POST type */
        if (!isset($_POST['iw_booking_post_metabox_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['iw_booking_post_metabox_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'iw_booking_post_metabox')) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        $post_type = $_POST['post_type'];
        if ('page' == $post_type) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, its safe for us to save the data now. */

        $iw_information = $_POST['iw_information'];

        //Image gallery
        $image_gallery = isset($iw_information['image_gallery']) ? serialize($iw_information['image_gallery']) : serialize(array());

        //Svices
        $basic_services = isset($iw_information['basic_services']) ? serialize($iw_information['basic_services']) : serialize(array());
        $premium_services = isset($iw_information['premium_services']) ? serialize($iw_information['premium_services']) : serialize(array());

        //Basic Info
        $price = $iw_information['basic_info']['price'];
        $beds = $iw_information['basic_info']['beds'];
        $deposit = $iw_information['basic_info']['deposit'];
        $adult_amount = $iw_information['basic_info']['adult_amount'];
        $child_amount = $iw_information['basic_info']['child_amount'];
        $room_amount = intval($iw_information['basic_info']['room_amount']);
        $status = $iw_information['basic_info']['status'];
        $category = $iw_information['basic_info']['category'];

        // Update the meta field.
        update_post_meta($post_id, 'iw_booking_image_gallery', $image_gallery);
        update_post_meta($post_id, 'iw_booking_category', $category);
        update_post_meta($post_id, 'iw_booking_room_price', $price);
        update_post_meta($post_id, 'iw_booking_room_beds', $beds);
        update_post_meta($post_id, 'iw_booking_room_deposit', $deposit);
        update_post_meta($post_id, 'iw_booking_room_adult_amount', $adult_amount);
        update_post_meta($post_id, 'iw_booking_room_child_amount', $child_amount);
        update_post_meta($post_id, 'iw_booking_room_amount', $room_amount);
        update_post_meta($post_id, 'iw_booking_room_status', $status);
        update_post_meta($post_id, 'iw_booking_basic_services', $basic_services);
        update_post_meta($post_id, 'iw_booking_premium_services', $premium_services);

        global $wpdb;

        // Update meta data
        if ($category) {
            $term = get_term_by('slug', $category, 'booking_category');
            $wpdb->delete($wpdb->prefix . "term_relationships", array('object_id' => $post_id));
            $wpdb->insert($wpdb->prefix . "term_relationships", array('object_id' => $post_id, 'term_taxonomy_id' => $term->term_id, 'term_order' => 0));
        }

        $wpdb->delete($wpdb->prefix . "iwb_extrafield_value", array('room_id' => $post_id));
        if (isset($iw_information['extrafield'])) {
            foreach ($iw_information['extrafield'] as $key => $val) {
                if (is_array($val)) {
                    $val = serialize($val);
                }
                update_post_meta($post_id, $key, stripslashes(htmlspecialchars($val)));
                $ext_id = intval(substr($key, strrpos($key, '_') + 1));
                if ($ext_id) {
                    $wpdb->insert($wpdb->prefix . "iwb_extrafield_value", array('room_id' => $post_id, 'extrafield_id' => $ext_id, 'value' => stripslashes(htmlspecialchars($val))));
                }
            }
        }
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_booking_category($post) {
        echo iwBookingUtility::categoryField('iw_information[basic_info][category]', get_post_meta($post->ID, 'iw_booking_category', true), false);
    }

    public function render_meta_box_basic_info($post) {
        global $wpdb, $iwb_settings;
        ?>
        <div class="iw-metabox-fields">
            <table class="list-table">
                <tbody class="the-list">
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Price', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $price = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_price', true));
                            ?>
                            <input class="" value="<?php echo $price ? $price : ''; ?>" type="text" name="iw_information[basic_info][price]"/>
                            <div class="description"></div>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Deposit', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $deposit = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_deposit', true));
                            ?>
                            <input class="" value="<?php echo $deposit ? $deposit : ''; ?>" type="text" name="iw_information[basic_info][deposit]"/>
                            <div class="description"><?php _e('Input percent for deposit. Eg: Input 25 for 25%', 'inwavethemes'); ?></div>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Amount of people', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $amount_of_adult = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_adult_amount', true));
                            ?>
                            <input class="" value="<?php echo $amount_of_adult ? $amount_of_adult : ''; ?>" type="text" name="iw_information[basic_info][adult_amount]"/>
                            <div class="description"><?php _e('Maximum adult passenger allowed for room', 'inwavethemes'); ?></div>
                            <?php
                            $amount_of_child = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_child_amount', true));
                            ?>
                            <input class="" value="<?php echo $amount_of_child ? $amount_of_child : ''; ?>" type="text" name="iw_information[basic_info][child_amount]"/>
                            <div class="description"><?php _e('Maximum child passenger allowed for room', 'inwavethemes'); ?></div>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Amount', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $amount = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_amount', true));
                            ?>
                            <input type="text" value="<?php echo isset($amount) ? $amount : ''; ?>" placeholder="<?php echo __('', 'inwavethemes'); ?>" name="iw_information[basic_info][room_amount]"/>
                            <div class="description"><?php _e('Number of room available', 'inwavethemes'); ?></div>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Beds', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $beds = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_beds', true));
                            ?>
                            <input type="text" value="<?php echo isset($beds) ? $beds : ''; ?>" placeholder="<?php echo __('', 'inwavethemes'); ?>" name="iw_information[basic_info][beds]"/>
                            <div class="description"><?php _e('Number of bed in room', 'inwavethemes'); ?></div>
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __('Status', 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            $status_data = array(
                                array('text' => __('Available', IW_TEXT_DOMAIN), 'value' => '1'),
                                array('text' => __('Unavailable', IW_TEXT_DOMAIN), 'value' => '0')
                            );
                            $status_value = htmlspecialchars(get_post_meta($post->ID, 'iw_booking_room_status', true));
                            echo iwBookingUtility::selectFieldRender('status', 'iw_information[basic_info][status]', $status_value, $status_data, '', '', FALSE);
                            ?>
                            <div class="description"></div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php
            /* Create a settings metabox ----------------------------------------------------- */
            $fields = array();
            $categories = array('0');
            $cats = $wpdb->get_results($wpdb->prepare('SELECT a.term_taxonomy_id as category_id FROM ' . $wpdb->prefix . 'term_relationships as a where a.object_id =%d', $post->ID));
            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    $categories[] = $cat->category_id;
                }
            }
            $extfields = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'iwb_extrafield as a inner join ' . $wpdb->prefix . 'iwb_extrafield_category as b on a.id = b.extrafield_id WHERE b.category_id IN(' . implode(',', wp_parse_id_list($categories)) . ') group by a.id');
            if (!empty($extfields)) {
                foreach ($extfields as $extrafield) {
                    $data = array(
                        'name' => __(stripslashes($extrafield->name), 'inwavethemes'),
                        'desc' => __(stripslashes($extrafield->description), 'inwavethemes'),
                        'id' => '_iw_booking_' . $extrafield->id,
                        'type' => $extrafield->type,
                        'title' => __(htmlentities(stripslashes($extrafield->name)), 'inwavethemes'),
                        'std' => $extrafield->default_value
                    );
                    array_push($fields, $data);
                }
            }
            $this->metaBoxHtmlRender($post, $fields);
            ?>
        </div>
        <?php
    }

    public function render_meta_box_image_gallery($post) {
        $value = get_post_meta($post->ID, 'iw_booking_image_gallery', true);
        $image_gallery = unserialize($value);
        // Add an nonce field so we can check for it later.
        wp_nonce_field('iw_booking_post_metabox', 'iw_booking_post_metabox_nonce');
        ?>
        <div class="iw-metabox-fields">
            <div class="list-image-gallery">
                <?php
                if ($image_gallery):
                    foreach ($image_gallery as $item) :
                        $image = wp_get_attachment_image_src($item);
                        $img = $image[0];
                        ?>
                        <div class="iw-image-item">
                            <div class="action-overlay">
                                <span class="remove-image">x</span>
                            </div>
                            <img width="150" src="<?php echo $img; ?>"/>
                            <input type="hidden" name="iw_information[image_gallery][]" value="<?php echo $item; ?>"/>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div style="clear: both;"></div>
            <div class="button-add-image">
                <span class="button add-new-image"><?php echo __('Add new images', 'inwavethemes'); ?></span>
            </div>
        </div>
        <?php
    }

    function render_meta_box_include_services($post) {
        global $iwb_settings;
        $service = new iwBookingService();
        $basic_services = $service->getServices(0, 0, 1, 'basic');
        $premium_services = $service->getServices(0, 0, 1, 'premium');
        $basic_service_selected = unserialize(get_post_meta($post->ID, 'iw_booking_basic_services', true));
        $premium_service_selected = unserialize(get_post_meta($post->ID, 'iw_booking_premium_services', true));
        ?>
        <div class="iw-metabox-fields">
            <?php if (!empty($basic_services)): ?>
                <fieldset>
                    <legend><h4><?php _e('Basic Services', 'inwavethemes'); ?></h4></legend>
                    <?php
                    foreach ($basic_services as $srv) {
                        ?>
                        <div class="service-input-item">
                            <label><input type="checkbox" <?php echo!empty($basic_service_selected) ? (in_array($srv->getId(), $basic_service_selected) ? 'checked="checked"' : '') : ''; ?> value="<?php echo esc_attr($srv->getId()); ?>" name="iw_information[basic_services][]"/><?php echo esc_html($srv->getName()); ?></label>
                        </div>
                        <?php
                    }
                    ?>
                </fieldset>
                <?php
            endif;
            if (!empty($premium_services)):
                ?>
                <fieldset>
                    <legend><h4><?php _e('Premium Services', 'inwavethemes'); ?></h4></legend>
                    <?php
                    foreach ($premium_services as $srv) {
                        ?>
                        <div class="service-input-item">
                            <label><input type="checkbox" <?php echo !empty($premium_service_selected) ? (in_array($srv->getId(), $premium_service_selected) ? 'checked="checked"' : '') : ''; ?> value="<?php echo esc_attr($srv->getId()); ?>" name="iw_information[premium_services][]"/><?php echo esc_html($srv->getName()) . ' (' . ($srv->getPrice() ? iwBookingUtility::getMoneyFormated($srv->getPrice()) : __('Free', 'inwavethemes')) . "/" . $srv->getRateCommercialText() . ')'; ?></label>
                        </div>
                        <?php
                    }
                    ?>
                </fieldset>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Print HTML content of a meta box.
     *
     * @since 1.0.0
     * @param object $post The post
     * @param array $meta_box Meta box data
     */
    function metaBoxHtmlRender($post, $fields) {
        if (!is_array($fields)) {
            return false;
        }
        if (!empty($fields)):
            ?>
            <div class="extra-info"><?php _e('Extra Info: ', IW_TEXT_DOMAIN); ?></div>
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="left"><?php echo __('Field Name', 'inwavethemes'); ?></th>
                        <th><?php echo __('Value', 'inwavethemes'); ?></th>
                    </tr>
                </thead>
                <tbody class="the-list">
                    <?php
                    foreach ($fields as $field):
                        $meta = get_post_meta($post->ID, $field['id'], true);
                        ?>
                        <tr class="alternate">
                            <td>
                                <label for='<?php echo $field['id']; ?>'><strong><?php echo $field['name']; ?></strong><span><?php echo $field['desc']; ?></span></label>
                            </td>
                            <td>
                                <?php
                                switch ($field['type']):
                                    case 'text':
                                        echo "<input title='{$field['title']}' type='text' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($meta ? $meta : htmlentities(stripslashes($field['std']))) . "' />";
                                        break;
                                    case 'dropdown_list':
                                        $field_data = unserialize($field['std']);
                                        if ($field_data[1] == 1) {
                                            echo "<select name='iw_information[extrafield][{$field['id']}][]' id='{$field['id']}' multiple='multiple' >";
                                        } else {
                                            echo "<select name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' >";
                                        }
                                        $options = explode("\n", htmlentities(stripslashes($field_data[0])));
                                        foreach ($options as $option) {
                                            $option_data = explode("|", $option);
                                            if (count($option_data) == 3) {
                                                echo "<option value='{$option_data[0]}'";
                                                if ($meta) {
                                                    $val = $option_data[0];
                                                    if ($field_data[1] == 1) {
                                                        $values = unserialize(html_entity_decode($meta));
                                                    } else {
                                                        $values = $meta;
                                                    }
                                                    if ((is_array($values) && in_array($val, $values)) || $values == $val) {
                                                        echo ' selected="selected"';
                                                    }
                                                } else {
                                                    if ($option_data[2] == 1) {
                                                        echo ' selected="selected"';
                                                    }
                                                }
                                                echo ">{$option_data[1]}</option>";
                                            }
                                        }
                                        echo '</select>';
                                        break;
                                    case 'textarea':
                                        echo "<textarea title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}'>" . ($meta ? $meta : htmlentities(stripslashes($field['std']))) . "</textarea>";
                                        break;
                                    case 'date':
                                        echo "<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($meta ? $meta : $field['std']) . "'/>";
                                        echo '<script tpe="text/javascript">
                            jQuery(document).ready(function () {
                                jQuery("#' . $field['id'] . '").datepicker({
                                    dateFormat: "dd-mm-yy"
                                });
                            });
                        </script>';
                                        break;
                                    case 'measurement':
                                        $measurement_data = unserialize(html_entity_decode($meta));
                                        if (!$measurement_data) {
                                            $measurement_data = unserialize($field['std']);
                                        }
                                        $measurement_value = htmlentities(stripslashes($measurement_data['measurement_value']));
                                        $measurement_unit = $measurement_data['measurement_unit'];
                                        echo "<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}][measurement_value]' id='{$field['id']}' value='" . $measurement_value . "'/> {$measurement_unit}";
                                        echo "<input type='hidden' name='iw_information[extrafield][{$field['id']}][measurement_unit]' id='{$field['id']}' value='" . $measurement_unit . "'/>";
                                        break;
                                    case 'link':
                                        $link_data = unserialize(html_entity_decode($meta));
                                        if (!$link_data) {
                                            $link_data = unserialize($field['std']);
                                        }
                                        $link_url = htmlentities(stripslashes($link_data['link_value_link']));
                                        $link_text = htmlentities(stripslashes($link_data['link_value_text']));
                                        $link_target = $link_data['link_value_target'];
                                        echo '<input placeholder = "' . __('URL') . '" name = "iw_information[extrafield][' . $field['id'] . '][link_value_link]" value = "' . $link_url . '" type = "url"/>';
                                        echo '<input placeholder = "' . __('Text') . '" name = "iw_information[extrafield][' . $field['id'] . '][link_value_text]" value = "' . $link_text . '" type = "text"/>';
                                        $link_datas = array(
                                            array('value' => '_blank', 'text' => 'Blank'),
                                            array('value' => '_self', 'text' => 'Self')
                                        );
                                        echo iwBookingUtility::selectFieldRender('iw_information_' . $field['id'], 'iw_information[extrafield][' . $field['id'] . '][link_value_target]', $link_target, $link_datas, 'Target', '', false);
                                        break;
                                    case 'image':
                                        echo iwBookingUtility::imageFieldRender('', 'iw_information[extrafield][' . $field['id'] . ']', $meta ? $meta : $field['std']);
                                        ?>
                                        <?php
                                        break;
                                    case 'images':
                                        ?>
                                        <script>
                                            jQuery(function ($) {
                                                // Load images
                                                function loadImages(images) {
                                                    if (images) {
                                                        var shortcode = new wp.shortcode({
                                                            tag: 'gallery',
                                                            attrs: {ids: images},
                                                            type: 'single'
                                                        });

                                                        var attachments = wp.media.gallery.attachments(shortcode);

                                                        var selection = new wp.media.model.Selection(attachments.models, {
                                                            props: attachments.props.toJSON(),
                                                            multiple: true
                                                        });

                                                        selection.gallery = attachments.gallery;

                                                        // Fetch the query's attachments, and then break ties from the
                                                        // query to allow for sorting.
                                                        selection.more().done(function () {
                                                            // Break ties with the query.
                                                            selection.props.set({query: false});
                                                            selection.unmirror();
                                                            selection.props.unset('orderby');
                                                        });

                                                        return selection;
                                                    }

                                                    return false;
                                                }

                                                var frame,
                                                        images = '<?php echo get_post_meta($post->ID, '_iw_courses_image_ids', true); ?>'
                                                selection = loadImages(images);

                                                $('#iw_courses_image_upload').on('click', function (e) {
                                                    e.preventDefault();

                                                    // Set options for 1st frame render
                                                    var options = {
                                                        title: '<?php _e('Create Gallery', 'inwavethemes'); ?>',
                                                        state: 'gallery-edit',
                                                        frame: 'post',
                                                        selection: selection
                                                    }

                                                    // Check if frame or gallery already exist
                                                    if (frame || selection) {
                                                        options['title'] = '<?php _e('Edit Gallery', 'inwavethemes'); ?>';
                                                    }

                                                    frame = wp.media(options).open();

                                                    // Tweak views
                                                    frame.menu.get('view').unset('cancel');
                                                    frame.menu.get('view').unset('separateCancel');
                                                    frame.menu.get('view').get('gallery-edit').el.innerHTML = '<?php _e('Edit Gallery', 'inwavethemes'); ?>';
                                                    frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

                                                    // When we are editing a gallery
                                                    overrideGalleryInsert();
                                                    frame.on('toolbar:render:gallery-edit', function () {
                                                        overrideGalleryInsert();
                                                    });

                                                    frame.on('content:render:browse', function (browser) {
                                                        if (!browser)
                                                            return;
                                                        // Hide Gallery Settings in sidebar
                                                        browser.sidebar.on('ready', function () {
                                                            browser.sidebar.unset('gallery');
                                                        });
                                                        // Hide filter/search as they don't work
                                                        browser.toolbar.on('ready', function () {
                                                            if (browser.toolbar.controller._state == 'gallery-library') {
                                                                browser.toolbar.$el.hide();
                                                            }
                                                        });
                                                    });

                                                    // All images removed
                                                    frame.state().get('library').on('remove', function () {
                                                        var models = frame.state().get('library');
                                                        if (models.length == 0) {
                                                            selection = false;
                                                            $.post(ajaxurl, {ids: '', action: 'iw_courses_save_images', post_id: iw_courses_ajax.post_id, nonce: iw_courses_ajax.nonce});
                                                        }
                                                    });

                                                    // Override insert button
                                                    function overrideGalleryInsert() {
                                                        frame.toolbar.get('view').set({
                                                            insert: {
                                                                style: 'primary',
                                                                text: '<?php echo _e('Save Gallery', 'inwavethemes'); ?>',
                                                                click: function () {
                                                                    var models = frame.state().get('library'),
                                                                            ids = '';

                                                                    models.each(function (attachment) {
                                                                        ids += attachment.id + ','
                                                                    });

                                                                    this.el.innerHTML = '<?php _e('Saving ...', 'inwavethemes'); ?>';

                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: ajaxurl,
                                                                        data: {
                                                                            ids: ids,
                                                                            action: 'iw_courses_save_images',
                                                                            post_id: iw_courses_ajax.post_id,
                                                                            nonce: iw_courses_ajax.nonce
                                                                        },
                                                                        success: function () {
                                                                            selection = loadImages(ids);
                                                                            $('#_iw_courses_image_ids').val(ids);
                                                                            frame.close();
                                                                        },
                                                                        dataType: 'html'
                                                                    }).done(function (data) {
                                                                        $('.iwc-gallery-thumbnail').html(data);
                                                                    });

                                                                } // end click function
                                                            } // end insert
                                                        });
                                                    }
                                                });
                                            });
                                        </script>
                                        <?php
// SPECIAL CASE:
                                        $meta = get_post_meta($post->ID, '_iw_courses_image_ids', true);
                                        $thumbs_output = '';
                                        $button_text = $meta ? __('Edit Gallery', 'inwavethemes') : $field['std'];
                                        if ($meta) {
                                            $field['std'] = __('Edit Gallery', 'inwavethemes');
                                            $thumbs = explode(',', $meta);
                                            foreach ($thumbs as $thumb) {
                                                $thumbs_output .= '<li>' . wp_get_attachment_image($thumb, array(50, 50)) . '</li>';
                                            }
                                        }
                                        echo
                                        "
						<input type='button' class='button' name='{$field['id']}' id='iw_courses_image_upload' value='$button_text' />
						<input type='hidden' name='iw_information[extrafield][_iw_courses_image_ids]' id='_iw_courses_image_ids' value='" . ($meta ? $meta : 'false') . "' />
						<ul class='iwc-gallery-thumbnail'>{$thumbs_output}</ul>
					";
                                        break;
                                    case 'file':
                                        ?>
                                        <script>
                                            jQuery(function ($) {
                                                var frame;

                                                $('#<?php echo $field['id']; ?>_button').on('click', function (e) {
                                                    e.preventDefault();

                                                    // Set options
                                                    var options = {
                                                        state: 'insert',
                                                        frame: 'post'
                                                    };

                                                    frame = wp.media(options).open();

                                                    // Tweak views
                                                    frame.menu.get('view').unset('gallery');
                                                    frame.menu.get('view').unset('featured-image');

                                                    frame.toolbar.get('view').set({
                                                        insert: {
                                                            style: 'primary',
                                                            text: '<?php _e('Insert', 'inwavethemes'); ?>',
                                                            click: function () {
                                                                var models = frame.state().get('selection'),
                                                                        url = models.first().attributes.url;

                                                                $('#<?php echo $field['id']; ?>').val(url);

                                                                frame.close();
                                                            }
                                                        } // end insert
                                                    });
                                                });
                                            });
                                        </script>
                                        <?php
                                        echo
                                        "
						<input type='text' title='{$field['title']}' name='iw_information[extrafield][{$field['id']}]' id='{$field['id']}' value='" . ($meta ? $meta : $field['std']) . "' class='file' />
						<input type='button' class='button' name='{$field['id']}_button' id='{$field['id']}_button' value='Browse' />
					";
                                        break;
                                    default:
                                        break;
                                endswitch;
                                ?>
                            </td>
                <!--                                <td>
                                <span class="button remove-button"><?php echo __('Remove', 'inwavethemes'); ?></span>
                            </td>-->
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
        <?php
    }

}
