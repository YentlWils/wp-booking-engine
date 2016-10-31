/* 
 * @package Inwave Event
 * @version 1.0.0
 * @created Mar 11, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwevent_admin
 *
 * @developer duongca
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        var $iwTab = $('.iw-tabs.event-detail'),
                content_list = $iwTab.find('.iw-tab-content .iw-tab-item-content'),
                list = $iwTab.find('.iw-tab-items .iw-tab-item'),
                accordion_day = $('.iw-tab-item-content .iw-tabs');
        $('.iw-tab-items .iw-tab-item', $iwTab).click(function () {
            if ($(this).hasClass('active')) {
                return;
            }
            $(this).addClass('active');
            var itemclick = this;
            list.each(function () {
                if (list.index(this) !== list.index(itemclick) && $(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
            loadTabContent();
        });

        function loadTabContent() {
            var item_active = $iwTab.find('.iw-tab-items .iw-tab-item.active');
            content_list.addClass('iw-hidden');
            $(content_list.get(list.index(item_active))).removeClass('iw-hidden');
        }
        ;
        loadTabContent();

        $('.iw-tab-item-content .iw-accordion-header').live('click', function () {
            var itemClick = $(this),
                    accordion_list = accordion_day.find('.iw-accordion-item'),
                    item_target = itemClick.parent();
            if (itemClick.hasClass('active')) {
                itemClick.removeClass('active');
                item_target.find('.iw-accordion-content').slideUp();
                item_target.find('.iw-accordion-header-icon .expand').hide();
                item_target.find('.iw-accordion-header-icon .no-expand').show();
                return;
            }
            itemClick.addClass('active');
            item_target.find('.iw-accordion-content').slideDown();
            item_target.find('.iw-accordion-header-icon .expand').show();
            item_target.find('.iw-accordion-header-icon .no-expand').hide();
            accordion_list.each(function () {
                if (accordion_list.index(this) !== accordion_list.index(item_target) && $(this).find('.iw-accordion-header').hasClass('active')) {
                    $(this).find('.iw-accordion-header').removeClass('active');
                    $(this).find('.iw-accordion-content').slideUp();
                    $(this).find('.iw-accordion-header-icon .expand').hide();
                    $(this).find('.iw-accordion-header-icon .no-expand').show();
                }
            });
        });

        $('.iw-accordion-time-header').live('click', function () {
            var itemClick = $(this);
            var item_target = itemClick.parent();
            var time_accordion_list = itemClick.parents('.iw-tabs.accordion').find('.iw-accordion-time-item');
            if (itemClick.hasClass('active')) {
                itemClick.removeClass('active');
                item_target.find('.iw-accordion-time-content').slideUp();
                item_target.find('.iw-accordion-time-header-icon .expand').hide();
                item_target.find('.iw-accordion-time-header-icon .no-expand').show();
                return;
            }
            itemClick.addClass('active');
            item_target.find('.iw-accordion-time-content').slideDown();
            item_target.find('.iw-accordion-time-header-icon .expand').show();
            item_target.find('.iw-accordion-time-header-icon .no-expand').hide();
            time_accordion_list.each(function () {
                if (time_accordion_list.index(this) !== time_accordion_list.index(item_target) && $(this).find('.iw-accordion-time-header').hasClass('active')) {
                    $(this).find('.iw-accordion-time-header').removeClass('active');
                    $(this).find('.iw-accordion-time-content').slideUp();
                    $(this).find('.iw-accordion-time-header-icon .expand').hide();
                    $(this).find('.iw-accordion-time-header-icon .no-expand').show();
                }
            });
        });

        //Set date event
        $('input.input-date').datepicker({dateFormat: 'mm/dd/yy',});


        //Add images gallery
        var frame;
        //Get image from wp library
        $('.button-add-image .add-new-image').click(function () {
            // Set options
            var options = {
                state: 'insert',
                frame: 'post',
                multiple: true,
                library: {
                    type: 'image'
                }
            };

            frame = wp.media(options).open();

            // Tweak views
            frame.menu.get('view').unset('gallery');
            frame.menu.get('view').unset('featured-image');

            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: 'Insert',
                    click: function () {
                        var models = frame.state().get('selection');
                        models.each(function (e) {
                            var attm = e.toJSON();
                            var item_control = '<div class="iw-image-item">';
                            item_control += '<div class="action-overlay">';
                            item_control += '<span class="remove-image">x</span>';
                            item_control += '</div>';
                            item_control += '<img src="' + attm.url + '" width="150"/>';
                            item_control += '<input type="hidden" value="' + attm.id + '" name="iw_information[image_gallery][]"/>';
                            $('.iw-metabox-fields .list-image-gallery').append(item_control);
                        });
                        frame.close();
                    }
                } // end insert
            });
        });

        //Remove image from list
        //Remove image from list gallery
        $('.list-image-gallery .action-overlay .remove-image').live('click', function () {
            $(this).parents('.iw-image-item').hide(200).remove();
        });

// ADD IMAGE LINK
        $('.iwe-image-field div.image-add-image').live('click', function (event) {
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
                        e_target.parent().parent().find('.iw-field.iwe-image-field-data').val(attachment.id);
                        frame.close();
                    }
                } // end insert
            });
        });


        // DELETE IMAGE LINK
        $('.iwe-image-field .image-delete').live('click', function (event) {
            var e_target = $(this);

            event.preventDefault();

            // Clear out the preview image
            e_target.parents('.iwe-image-field').find('div.image-preview').addClass('iw-hidden').html('');

            // Delete the image id from the hidden input
            e_target.parents('.field-input').find('.iw-field.iwe-image-field-data').val('');

        });


        //Plan ticket quantity input field process
        $('.iw-tab-item-content .plan-ticket').live('input', function () {
            var val = parseInt($(this).val());
            if (val) {
                if (val < 0) {
                    val = 0;
                }
                $(this).val(val);
            } else {
                $(this).val(0);
            }
            var plan_tickets = $('.iw-tab-item-content .plan-ticket'),
                    tickets = 0;
            plan_tickets.each(function () {
                tickets += parseInt($(this).val());
            });
            $('.iw-tab-item-content .number_tickets').val(tickets);
        }).trigger('input');

        $('.iw-tab-item-content .plan-price, .iw-tab-item-content .plan-limit').live('input', function () {
            var val = parseInt($(this).val());
            if (val) {
                if (val < 0) {
                    val = 0;
                }
                $(this).val(val);
            } else {
                $(this).val(0);
            }
        }).trigger('input');

        /*$('.iwe-wrap input.sendmail_to_cutommer').change(function () {
            if ($(this).prop("checked") === true) {
                $('.iwe-wrap .status_reason').show();
            } else {
                $('.iwe-wrap .status_reason').hide();
            }
        }).trigger('change');*/

        //Add ticket Plan
        $('.button.add-ticket-plan').click(function () {
            var plan_tickets = $('.iw-tab-item-content .plan-ticket'),
                    number_tickets = $('.iw-tab-item-content .number_tickets').val();
            var sum_ticket = 0;
            plan_tickets.each(function () {
                sum_ticket += parseInt($(this).val());
            });
            
            var item_target = $(this);
            $.ajax({
                url: iwEventCfg.ajaxUrl,
                data: {action: 'getTicketPlanHtml', tickets:number_tickets - sum_ticket},
                type: "post",
                success: function (data) {
                    item_target.parents('.iw-tab-item-content').find('.iw-tabs.accordion.pricing').append(data);
                }
            });
        });
        $('.iw-accordion-content .day-info input.plan-title').live('input', function () {
            $(this).parents('.iw-accordion-item').find('.iw-accordion-header span').html($(this).val());
        });

        //Schedule process
        $('.iw-accordion-content .day-info input.date').live('focus', function () {
            $(this).datepicker({
                minDate: $('.input-date.start-date').val(),
                maxDate: $('.input-date.end-date').val(),
                onSelect: function (dateText) {
                    $(this).parents('.iw-accordion-item').find('.iw-accordion-header span.date').html(dateText);
                }
            });
        }).trigger('focus');

        $('.iw-accordion-content .day-info input.day-title').live('input', function () {
            $(this).parents('.iw-accordion-item').find('.iw-accordion-header span.title').html($(this).val());
        });
        $('.iw-accordion-content .day-info input.subtitle').live('input', function () {
            $(this).parents('.iw-accordion-item').find('.iw-accordion-header span.sub-title').html($(this).val());
        });
        $('.iw-accordion-content .day-info input.date').live('input', function () {
            $(this).parents('.iw-accordion-item').find('.iw-accordion-header span.date').html($(this).val());
        });

        //Add Schedule Day
        $('.button.add-schedule-day').click(function () {
            var item_target = $(this);
            $.ajax({
                url: iwEventCfg.ajaxUrl,
                data: {action: 'getScheduleDayHtml'},
                type: "post",
                success: function (data) {
                    item_target.parents('.iw-tab-item-content').find('.iw-tabs.accordion.day').append(data);
                }
            });
        });

        //Remove day
        $('.iw-accordion-item .iw-accordion-header-icon').live('click', function () {
            $(this).parents('.iw-accordion-item').remove();
        });

        //Add Schedule Time
        $('.button.add-schedule-time').live('click', function () {
            var item_target = $(this), day = item_target.parents('.iw-accordion-item').data('day');
            $.ajax({
                url: iwEventCfg.ajaxUrl,
                data: {action: 'getScheduleTimeHtml', day: day},
                type: "post",
                success: function (data) {
                    item_target.parents('.iw-accordion-content').find('.iw-tabs.accordion.time').append(data);
                }
            });
        });

        //Remove time
        $('.iw-accordion-time-header-icon').live('click', function () {
            $(this).parents('.iw-accordion-time-item').remove();
        });


        $('.iwe-field-manager .field-label').live('change', function () {
            var val = $(this).val();
            if (val === '') {
                $(this).addClass('form-error');
            } else {
                $(this).removeClass('form-error');
            }
            saveSettingFormValid();
        }).trigger('change');

        $('.iwe-field-manager .field-name').live('input', function () {
            var val = $(this).val();
            if (val === '') {
                $(this).addClass('form-error');
            } else {
                if (val.indexOf(' ') > 0) {
                    $(this).addClass('form-error');
                } else {
                    $(this).removeClass('form-error');
                }
            }
            saveSettingFormValid();
        }).trigger('input');

        $('.iwe-field-manager .select-field-type').live('change', function () {
            var val = $(this).val(), row = $(this).parents('tr');
            if (val === 'select') {
                row.find('.default-value').html('<select name="iwb_settings[register_form_fields][default_value][]"></select>');
                row.find('.field-values').html('<textarea class="form-error" placeholder="value|Text" name="iwb_settings[register_form_fields][values][]"></textarea><span class="description">Multiple value with new line</span>');
            } else {
               // row.find('.field-values').html('No default value for this field type.<input type="hidden" name="iwb_settings[register_form_fields][values][]" value=""/>');
                if (val === 'text') {
                    row.find('.default-value').html('<input type="text" name="iwb_settings[register_form_fields][default_value][]"/>');
                } else {
                    row.find('.default-value').html('<textarea name="iwb_settings[register_form_fields][default_value][]"></textarea>');
                }
            }
            saveSettingFormValid();
        });

        $('.iwe-field-manager .field-values textarea').live('input', function () {
            var item_target = $(this), val = item_target.val().trim('\n'), line = val.split('\n'), options = '';
            for (var i = 0; i < line.length; i++) {
                var option = line[i].split('|');
                if (option.length !== 2) {
                    item_target.addClass('form-error');
                    break;
                } else {
                    options += '<option value="' + option[0] + '">' + option[1] + '</option>';
                    item_target.removeClass('form-error');
                }
            }
            $('.iwe-field-manager .default-value select').html(options);
            saveSettingFormValid();
        });


        $('.button.add-rgister-field').click(function () {
            var html = '<tr class="alternate">';
            html += '<td class="iwe-sortable-cell">';
            html += '<span><i class="fa fa-arrows"></i></span>';
            html += '</td>';
            html += '<td class="iwe_field_label"><input class="field-label form-error" type="text" name="iwb_settings[register_form_fields][label][]"></td>';
            html += '<td class="iwe_field_name"><input class="field-name form-error" type="text" name="iwb_settings[register_form_fields][name][]"></td>';
            html += '<td><select class="select-field-type" name="iwb_settings[register_form_fields][type][]">';
            html += '<option value="text">String</option>';
            html += '<option value="select">Select</option>';
            html += '<option value="textarea">Text</option>';
            html += '</select></td>';
            html += '<td class="default-value"><input type="text" name="iwb_settings[register_form_fields][default_value][]"></td>';
            html += '<td>';
            html += '<input type="checkbox" value="1" name="show_on_list" class="show_on_list"/>';
            html += '<input class="iwb_field_val" type="hidden" value="0" name="iwb_settings[register_form_fields][show_on_list][]"/>';
            html += '</td>';
            html += '<td>';
            html += '<input type="checkbox" value="1" name="require_field" class="require_field"/>';
            html += '<input class="iwb_field_val" type="hidden" value="0" name="iwb_settings[register_form_fields][require_field][]"/>';
            html += '</td>';
            html += '<td><span class="button remove_field">Remove</span></td>';
            html += '</tr>';

            $('.iwe-field-manager .the-list').append(html);
            saveSettingFormValid();
        });

        $('.button.remove_field').live('click', function () {
            $(this).parents('tr').remove();
            saveSettingFormValid();
        });

        $('input.show_on_list, input.require_field').live('change', function () {
            var val = $(this).is(':checked');
            if (val) {
                $(this).parent().find('.iwb_field_val').val(1);
            } else {
                $(this).parent().find('.iwb_field_val').val(0);
            }
        });

        function disableSaveSettingForm() {
            $('.iwe-save-settings input').attr('disabled', 'disabled');
        }
        function enableSaveSettingForm() {
            $('.iwe-save-settings input').removeAttr('disabled');
        }

        function saveSettingFormValid() {
            var error = $('.iwe-field-manager').find('.form-error');
            if (error.length > 0) {
                disableSaveSettingForm();
            } else {
                enableSaveSettingForm();
            }
        }

        $('.field-type.field-date input').datepicker();
        
        //booking service
        $('.booking-service .service-type select').change(function(){
            var val = $(this).val();
            if(val === 'basic'){
                $('.booking-service .service-price').hide();
            }else{
                $('.booking-service .service-price').show();
            }
        }).trigger('change');
    });
})(jQuery);


