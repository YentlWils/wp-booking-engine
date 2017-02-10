/* 
 * @package Inwave Booking
 * @version 1.0.0
 * @created Jun 3, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/* global iwbCfg */

/**
 * Description of booking-script
 *
 * @developer duongca
 */
(function ($) {
    $.fn.iwb_datepicker_range = function(){
        $(this).datepicker({
            minDate: 0,
            dateFormat : iwb_objectL10n.date_format,
            numberOfMonths: [1, 2],
            beforeShowDay: function(date) {
                var date1 = $.datepicker.parseDate('yy-mm-dd', $("#iwb-check-in").siblings('.iwb-datepicker-alt').val());
                var date2 = $.datepicker.parseDate('yy-mm-dd', $("#iwb-check-out").siblings('.iwb-datepicker-alt').val());
                return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
            },
            onSelect: function(dateText, inst) {
                var date1 = $.datepicker.parseDate('yy-mm-dd', $("#iwb-check-in").siblings('.iwb-datepicker-alt').val());
                var date2 = $.datepicker.parseDate('yy-mm-dd', $("#iwb-check-out").siblings('.iwb-datepicker-alt').val());
                if (!date1 || date2) {
                    $("#iwb-check-in").datepicker('setDate', dateText);
                    $("#iwb-check-out").val('');
                    $("#iwb-check-out").siblings('.iwb-datepicker-alt').val('');

                    var selected = new Date(dateText);
                    var minDate = new Date(selected.getTime() + ((parseInt(iwb_objectL10n.minBookingDays)) * 24 * 60 * 60 * 1000));
                    var maxDate = new Date(selected.getTime() + ((parseInt(iwb_objectL10n.maxBookingDays)) * 24 * 60 * 60 * 1000));

                    $(this).datepicker('option', 'minDate', minDate);
                    $(this).datepicker('option', 'maxDate', maxDate);

                } else {
                    var checkIn = new Date($("#iwb-check-in").datepicker('getDate'));
                    var selected = new Date(dateText);
                    var minDate = new Date(checkIn.getTime() + ((parseInt(iwb_objectL10n.minBookingDays)) * 24 * 60 * 60 * 1000));
                    var maxDate = new Date(checkIn.getTime() + ((parseInt(iwb_objectL10n.maxBookingDays)) * 24 * 60 * 60 * 1000));
                    var newValue =  minDate.valueOf() > selected.valueOf() ? minDate : selected;
                    newValue =  maxDate.valueOf() < newValue.valueOf() ? maxDate : newValue;

                    $("#iwb-check-out").datepicker('setDate', newValue).trigger('change');

                    $(this).datepicker('option', 'minDate', 0);
                    $(this).datepicker('option', 'maxDate', null);
                }
            },
            closeText: iwb_objectL10n.closeText,
            currentText: iwb_objectL10n.currentText,
            monthNames: iwb_objectL10n.monthNames,
            monthNamesShort: iwb_objectL10n.monthNamesShort,
            dayNames: iwb_objectL10n.dayNames,
            dayNamesShort: iwb_objectL10n.dayNamesShort,
            dayNamesMin: iwb_objectL10n.dayNamesMin,
            firstDay: iwb_objectL10n.firstDay
        });
    };

    $.fn.iwb_datepicker = function(){
        $(this).each(function(){
            $(this).datepicker({
                dateFormat : iwb_objectL10n.date_format,
                minDate: 0,
                altField: $(this).siblings('.iwb-datepicker-alt'),
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst){
                    $(this).trigger('change');
                    $('#iwb-datepicker-range').datepicker('refresh');
                },
                closeText: iwb_objectL10n.closeText,
                currentText: iwb_objectL10n.currentText,
                monthNames: iwb_objectL10n.monthNames,
                monthNamesShort: iwb_objectL10n.monthNamesShort,
                dayNames: iwb_objectL10n.dayNames,
                dayNamesShort: iwb_objectL10n.dayNamesShort,
                dayNamesMin: iwb_objectL10n.dayNamesMin,
                firstDay: iwb_objectL10n.firstDay
            });

            var current_date = $.datepicker.parseDate('yy-mm-dd', $(this).data('value'));
            $(this).datepicker('setDate', current_date);
        });
    };

    $.fn.iwb_booking = function(){

        var area = {
            wrapper: $(this),
            resv_bar: $(this).find('#reservation-bar'),
            room_form: $(this).find('#reservation-selected-rooms'),
            date_form: $(this).find('#reservation-date-form'),
            people_form: $(this).find('#reservation-people-amount-wrapper'),
            summary_form: $(this).find('#reservation-summary-form'),
            service_form: $(this).find('#reservation-service-form'),

            proc_bar: $(this).find('#reservation-process-bar'),
            content_area: $(this).find('#iwb-booking-content-inner')
        };

        var resv_bar = {
            init: function(){

                // check in date and night num change
                area.resv_bar.on('change', '#iwb-night, #iwb-check-in', function(){
                    var check_in = area.resv_bar.find('#iwb-check-in');
                    var check_out = area.resv_bar.find('#iwb-check-out');
                    var night_num = area.resv_bar.find('#iwb-night');

                    if( check_in.val() ){
                        var check_out_date = check_in.datepicker('getDate', '+' + parseInt(iwb_objectL10n.minBookingDays) + 'd');
                        check_out_date.setTime(check_out_date.getTime() + (parseInt(night_num.val()) * 24 * 60 * 60 * 1000));

                        var check_out_min = check_in.datepicker('getDate',  '+' + parseInt(iwb_objectL10n.minBookingDays) + 'd');
                        check_out_min.setTime(check_out_min.getTime() + (parseInt(iwb_objectL10n.minBookingDays) * 24 * 60 * 60 * 1000));

                        var check_out_max = check_in.datepicker('getDate',  '+' + parseInt(iwb_objectL10n.maxBookingDays) + 'd');
                        check_out_max.setTime(check_out_max.getTime() + (parseInt(iwb_objectL10n.maxBookingDays) * 24 * 60 * 60 * 1000));

                        check_out.datepicker('option', 'minDate', check_out_min);
                        check_out.datepicker('option', 'maxDate', check_out_max);

                        check_out.datepicker('setDate', check_out_date);

                        $('#iwb-datepicker-range').datepicker('refresh');
                    }
                });

                // check out date change
                area.resv_bar.on('change', '#iwb-check-out', function(){
                    var check_in = area.resv_bar.find('#iwb-check-in').datepicker('getDate');
                    var check_out = $(this).datepicker('getDate');
                    var date_diff =  Math.ceil((check_out - check_in) / 86400000); // 1000/60/60/24

                    if( check_in && date_diff > 0 ){
                        var night_num = area.resv_bar.find('#iwb-night');
                        if( night_num.children('option[value="' + date_diff + '"]').length == 0 ){
                            night_num.append('<option value="' + date_diff + '" >' + date_diff + '</option>')
                        }
                        $('#iwb-night').val(date_diff);
                    }
                });

                // amount change
                area.resv_bar.on('change', '#iwb-room-number', function(){
                    var amount = parseInt($(this).val());
                    var room_diff = amount - area.people_form.children().length;
                    if( room_diff > 0 ){
                        for( var i=0; i<room_diff; i++ ){
                            var new_room = area.people_form.children(':first-child').clone().hide();
                            new_room.find('.reservation-people-amount-title span').html(area.people_form.children().length + 1);
                            new_room.appendTo(area.people_form).slideDown(200);
                        }
                        if( parseInt(area.proc_bar.attr('data-state')) > 1 ) {
                            main.change_state({state: 2});
                        }
                    }else if( room_diff < 0 ){
                        area.people_form.children().slice(room_diff).slideUp(200, function(){
                            $(this).remove();
                        });

                        if( parseInt(area.proc_bar.attr('data-state')) > 1 ){
                            area.room_form.children().slice(room_diff).slideUp(200, function(){
                                $(this).remove();
                            });
                            setTimeout(function () {
                                main.change_state({ state: 2 });
                            }, Math.abs(room_diff) * 210 );
                        }
                    }

                });

                // check availability button
                area.resv_bar.on('click', '.check-availability-btn', function(){
                    main.change_state({ state: 2 });
                    return false;
                });

                // query again when input change
                area.resv_bar.on('change', '#iwb-check-in, #iwb-night, #iwb-check-out, #iwb-hotel-branches', function(){
                    if( parseInt(area.proc_bar.attr('data-state')) > 1 ){
                        area.room_form.slideUp(function(){
                            $(this).html('').removeClass('iwb-active');
                            main.change_state({ state: 2 });
                        });
                    }
                });

                area.resv_bar.on('change', 'select[name="adult-number[]"], select[name="children-number[]"]', function(){
                    if( parseInt(area.proc_bar.attr('data-state')) > 1 ){
                        var index_item = $(this).closest('.reservation-people-amount').index();
                        if(index_item > -1 && $('#reservation-selected-rooms .reservation-room:eq('+index_item+')').length){
                            $('#reservation-selected-rooms .reservation-room:eq('+index_item+')').find('input').val('');
                            main.change_state({ state: 2 });
                        }
                    }
                });
            }
        };

        var proc_bar = {
            get_state: function(){
                return area.proc_bar.attr('data-state');
            },

            set_state: function( state ){
                area.proc_bar.attr('data-state', state);
                area.proc_bar.children('[data-process="' + state + '"]').addClass('iwb-active').siblings().removeClass('iwb-active');
            }
        };

        var main = {
            init: function(){

                // init date picker
                area.wrapper.find('.iwb-datepicker').iwb_datepicker();
                area.wrapper.find("#iwb-datepicker-range").iwb_datepicker_range();

                // reservation bar event
                resv_bar.init();

                // room selection event
                this.room_select();

                // contact form event
                this.contact_submit();
            },

            room_select: function(){
                /*area.content_area.on('click', '.price-breakdown-close', function(){
                    $(this).closest('.price-breakdown-wrapper').fadeOut(200);
                    return false;
                });*/
                /*area.content_area.on('click', '.price-break-down', function(){
                    $(this).children('.price-breakdown-wrapper').fadeIn(200);
                });*/

                area.content_area.on('click', '.iwb-room-selection',function(){
                    //$('body').animate({scrollTop: area.proc_bar.offset().top}, 300);
                    var service = [];
                    if($(this).closest('.iw-room').find('input[name="premium_service[]"]:checked').length){
                        $(this).closest('.iw-room').find('input[name="premium_service[]"]:checked').each(function () {
                            service.push($(this).val());
                        })
                    }
                    service = service.length ? service.join() : '';
                    area.room_form.find('.iwb-active input[name="room-id[]"]').val($(this).attr('data-roomid'));
                    area.room_form.find('.iwb-active input[name="room-service[]"]').val(service);

                    main.change_state({ state: 2});

                    return false;
                });

                area.content_area.on('click', '.iwbvent-pagination a', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    if($(this).hasClass('next')){
                        var paged = parseInt($('.iwbvent-pagination .page-numbers.current').text()) + 1;
                    }
                    else if($(this).hasClass('prev')){
                        var paged = parseInt($('.iwbvent-pagination .page-numbers.current').text()) - 1;
                    }else{
                        var paged = $(this).text();
                    }
                    if(paged){
                        main.change_state({ paged: paged, state: 2 });
                    }
                    return false;
                });

                area.room_form.on('click', '.change-room',function(){
                    $(this).closest('.reservation-room').find('input').val('');
                    main.change_state({ state: 2 });
                    return false;
                });

                // edit booking summary event
                area.summary_form.on('click', '#iwb-edit-booking-button', function(){
                    //area.room_form.find('.iwb-reservation-room:first-child input').val('');
                    main.change_state({ state: 2 });
                    return false;
                });

                area.summary_form.on('change', 'input[name="pay_deposit"]', function(){
                    if($(this).val() == 'true'){
                        area.summary_form.find('.iwb-price-deposit-inner-wrapper').slideDown();
                        area.summary_form.find('.iwb-price-summary-grand-total').removeClass('iwb-active');
                        area.summary_form.find('input[name="pay_deposit"][value="true"]').closest('span').addClass('iwb-active');
                        area.summary_form.find('input[name="pay_deposit"][value="false"]').closest('span').removeClass('iwb-active');
                    }else{
                        area.summary_form.find('.iwb-price-deposit-inner-wrapper').slideUp();
                        area.summary_form.find('.iwb-price-summary-grand-total').addClass('gdlr-active');
                        area.summary_form.find('input[name="pay_deposit"][value="true"]').closest('span').removeClass('iwb-active');
                        area.summary_form.find('input[name="pay_deposit"][value="false"]').closest('span').addClass('iwb-active');
                    }
                    return false;
                });

            },

            contact_submit: function(){

                // for submitting service
                area.content_area.on('change', '.iwb-room-service-checkbox input', function(){
                    if( $(this).is(":checked") ){
                        $(this).parent('label').addClass('iwb-active').siblings('input').prop('disabled', false);
                    }else{
                        $(this).parent('label').removeClass('iwb-active').siblings('input').prop('disabled', true);
                    }
                });
                area.content_area.on('click', '.room-selection-next',function(){
                    area.content_area.find('.iwb-error-message').slideUp();
                    main.change_state({ state: 3, coupon_code: area.content_area.find('input[name="coupon_code"]').val() });
                    return false;
                });

                // for submitting contact form
                area.content_area.on('click', '.reservation-process-from', function(){
                    if( !$(this).hasClass('iwb-clicked') ){
                        $(this).addClass('iwb-clicked');
                        area.content_area.find('.iwb-error-message').slideUp();
                        main.change_state({ state: 3, contact: $(this).closest('form'), coupon_code: area.content_area.find('input[name="coupon_code"]').val() });
                    }
                    return false;
                });

               /* area.content_area.on('click', '.iwb-booking-payment-submit', function(){
                    if( !$(this).hasClass('iwb-clicked') ){
                        $(this).addClass('iwb-clicked');
                        area.content_area.find('.iwb-error-message').slideUp();
                        main.change_state({ state: 3, contact: $(this).closest('form'), 'contact_type': 'instant_payment' });
                    }
                    return false;
                });
                */
                // payment method selection
                area.content_area.on('click', '.iwb-payment-method input[name="payment-method"]',function(){
                    $(this).parent('label').addClass('iwb-active').siblings().removeClass('iwb-active');
                });
            },

            change_state: function( options ){
                /*if( area.resv_bar.find('select[name=iwb-hotel-branches]').val() == '' ){
                    area.resv_bar.find('#please-select-branches').slideDown();
                    return false;
                }else{
                    area.resv_bar.find('#please-select-branches').slideUp();
                }*/

                area.resv_bar.find('.check-availability-btn').slideUp(200, function(){ $(this).remove(); });

                area.content_area.animate({'opacity': 0.2});
                area.content_area.parent().addClass('iwb-loading');

                var data_submit = {
                    'action': area.resv_bar.attr('data-action'),
                    'data': area.resv_bar.serialize(),
                    'state': options.state
                };
                if( options.room_id ) data_submit.room_id = options.room_id;
                if( options.coupon_code ){
                    data_submit.coupon_code = options.coupon_code;
                }
                if( options.contact ) data_submit.contact = options.contact.serialize();
                if( options.contact_type ) data_submit.contact_type = options.contact_type;
                if( options.paged ) data_submit.paged = options.paged;

                $.ajax({
                    type: 'POST',
                    url: iwbCfg.ajaxUrl,
                    data: data_submit,
                    dataType: 'json',
                    error: function( a, b, c ){ console.log(a, b, c); },
                    success: function( data ){
                        if( data.state ){
							
                            proc_bar.set_state(data.state);

                            if( data.content ){
                                var tmp_height = area.content_area.height();
                                area.content_area.html(data.content);

                                var new_height = area.content_area.height();

                                area.content_area.parent().removeClass('iwb-loading');
                                area.content_area.height(tmp_height).animate({'opacity': 1, 'height': new_height}, function(){
                                    $(this).css('height', 'auto');
                                });
							
								if($('.premium_services input[type=checkbox]').length || $('.payment-method input[type=radio]').length) {
									$('.premium_services input[type=checkbox], .payment-method input[type=radio]').iCheck({
										checkboxClass: 'icheckbox_square',
										radioClass: 'icheckbox_square',
									}).on('ifChanged', function(event){
										if(($(this).is(":checked"))){
											$(this).closest('.service-item').addClass('active');
										}
										else{
											$(this).closest('.service-item').removeClass('active');
										}
									});
								}
							}
                            if( data.summary_form ){
                                if( !area.summary_form.hasClass('iwb-active') ){
                                    area.summary_form.html(data.summary_form).slideDown().addClass('iwb-active');
                                }else{
                                    var tmp_height = area.summary_form.height();
                                    area.summary_form.html(data.summary_form);

                                    var new_height = area.summary_form.height();
                                    area.summary_form.height(tmp_height).animate({'height': new_height}, function(){
                                        $(this).css('height', 'auto');
                                    });

                                    if( data.state == 4 ){
                                        $('body').animate({scrollTop: area.proc_bar.offset().top - (tmp_height - new_height)}, 300);
                                    }
                                }
                            }

                            // error message on form submit
                            if( data.error_message ){
                                area.content_area.find('.iwb-button').removeClass('gdlr-clicked');
                                area.content_area.find('.iwb-error-message').html(data.error_message).slideDown();

                                area.content_area.parent().removeClass('iwb-loading');
                                area.content_area.animate({'opacity': 1});
                            }
                            
                            /*if( data.service ){
                                area.service_form.html(data.service);
                            }*/

                            
                            if( data.state == 2 ){
                                area.summary_form.slideUp(function(){ $(this).removeClass('iwb-active'); });
                                area.date_form.slideDown();
                                area.people_form.slideDown();

                                if( data.room_form ){
                                    if( !area.room_form.hasClass('iwb-active') ){
                                        area.room_form.html(data.room_form).slideDown().addClass('iwb-active');
                                    }else{
                                        var tmp_height = area.room_form.height();
                                        area.room_form.html(data.room_form);

                                        var new_height = area.room_form.height();
                                        area.room_form.height(tmp_height).animate({'height': new_height}, function(){
                                            $(this).css('height', 'auto');
                                        });
                                    }
                                }

                                area.people_form.children().removeClass('iwb-active');
                                if(data.current_room_id){
                                    area.people_form.children(':eq('+(parseInt(data.current_room_id) -1)+')').addClass('iwb-active');
                                }

                            }else if( data.state == 3 ){
                                // for payment option
                                if( data.paypal_url){
                                    window.location = data.paypal_url;
                                    return;
                                }

                                area.room_form.slideUp(function(){ $(this).removeClass('iwb-active'); });
                                area.date_form.slideUp();
                                area.people_form.slideUp();
                                
                            }
                        } // data.state
                    }
                });
            }
        };

        main.init();

        return this;
    };

    $(document).ready(function () {
        //new version
        $('#iwb-reservation-page').iwb_booking();

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        var iwb_availability_checkin = $('#iwb-availability-checkin');
        var iwb_availability_checkout = $('#iwb-availability-checkout');
        if(iwb_availability_checkin.length){
            iwb_availability_checkin.datepicker({
                dateFormat: 'mm/dd/yy',
                altField: $('#iwb-availability-checkin input[name="checkin"]'),
                altFormat: "yy-mm-dd",
                beforeShowDay: function(date) {
                    return date.valueOf() < now.valueOf() ? [false, ""] : [true, ""];
                },
                todayBtn: true,
                closeText: iwb_objectL10n.closeText,
                currentText: iwb_objectL10n.currentText,
                monthNames: iwb_objectL10n.monthNames,
                monthNamesShort: iwb_objectL10n.monthNamesShort,
                dayNames: iwb_objectL10n.dayNames,
                dayNamesShort: iwb_objectL10n.dayNamesShort,
                dayNamesMin: iwb_objectL10n.dayNamesMin,
                firstDay: iwb_objectL10n.firstDay,
                nextText : iwb_objectL10n.nextText,
                prevText : iwb_objectL10n.prevText,
                onSelect : function(ev) {
                    var checkout_date = iwb_availability_checkout.datepicker( "getDate");
                    var checkin_date = $.datepicker.parseDate('mm/dd/yy', ev);

                    var compare_checkin_date = new Date();
                    compare_checkin_date.setDate(checkin_date.getDate() + parseInt(iwb_objectL10n.minBookingDays));

                    if (compare_checkin_date.getTime() > checkout_date.getTime()) {
                        var new_checkout_date = $.datepicker.parseDate('mm/dd/yy', ev);
                        new_checkout_date.setDate(new_checkout_date.getDate() + parseInt(iwb_objectL10n.minBookingDays));
                        var date_value = (new_checkout_date.getMonth() + 1) + '/'+ new_checkout_date.getDate() + '/' + new_checkout_date.getFullYear();
                        iwb_availability_checkout.datepicker( "setDate", date_value);
                        iwb_availability_checkout.datepicker( "refresh");
                        var _newformated = '<strong>'+new_checkout_date.getDate()+'</strong>/'+iwb_objectL10n.monthNames[(new_checkout_date.getMonth())];
                        iwb_availability_checkout.parent().find('.datepicker-holder').html(_newformated);
                    }
                    iwb_availability_checkin.datepicker("hide" );
                    var newformated = '<strong>'+checkin_date.getDate()+'</strong>/'+iwb_objectL10n.monthNames[(checkin_date.getMonth())];
                    iwb_availability_checkin.parent().find('.datepicker-holder').html(newformated);
                    iwb_availability_checkin.find('.ui-datepicker').hide();
                    iwb_availability_checkout.find('.ui-datepicker').show();
                }
            }).find('.ui-datepicker').hide();

            iwb_availability_checkin.datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', iwb_availability_checkin.data('value')));
            iwb_availability_checkout.datepicker({
                dateFormat: 'mm/dd/yy',
                altField: $('#iwb-availability-checkout input[name="checkout"]'),
                altFormat: "yy-mm-dd",
                closeText: iwb_objectL10n.closeText,
                currentText: iwb_objectL10n.currentText,
                monthNames: iwb_objectL10n.monthNames,
                monthNamesShort: iwb_objectL10n.monthNamesShort,
                dayNames: iwb_objectL10n.dayNames,
                dayNamesShort: iwb_objectL10n.dayNamesShort,
                dayNamesMin: iwb_objectL10n.dayNamesMin,
                firstDay: iwb_objectL10n.firstDay,
                nextText : iwb_objectL10n.nextText,
                prevText : iwb_objectL10n.prevText,
                todayBtn: true,
                beforeShowDay: function(date) {
                    var checkin_date = $( "#iwb-availability-checkin" ).datepicker( "getDate");
                    var minDate = new Date(checkin_date.getTime() + ((parseInt(iwb_objectL10n.minBookingDays) -1) * 24 * 60 * 60 * 1000));

                    var maxDate = new Date(checkin_date.getTime() + ((parseInt(iwb_objectL10n.maxBookingDays)) * 24 * 60 * 60 * 1000));

                    return date.valueOf() > minDate.valueOf() && date.valueOf() < maxDate.valueOf() ? [true, ""] : [false, ""];
                },
                onSelect : function(ev) {
                    iwb_availability_checkout.datepicker( "hide" );
                    var checkout_date = $.datepicker.parseDate('mm/dd/yy', ev);
                    var newformated = '<strong>'+checkout_date.getDate()+'</strong>/'+iwb_objectL10n.monthNames[checkout_date.getMonth()];
                    iwb_availability_checkout.parent().find('.datepicker-holder').html(newformated);
                    iwb_availability_checkout.find('.ui-datepicker').hide();
                }
            }).find('.ui-datepicker').hide();

            iwb_availability_checkout.datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', iwb_availability_checkout.data('value')));
        }
        $(document).click(function(e) {
            var elem = $(e.target);
            if(!elem.hasClass("hasDatepicker") &&
                !elem.hasClass("ui-datepicker") &&
                !elem.hasClass("ui-icon") &&
                !elem.hasClass("ui-datepicker-next") &&
                !elem.hasClass("ui-datepicker-prev") &&
                !$(elem).parents(".ui-datepicker").length &&
                !$(elem).parents(".availability-date").length){
                $(".availability-date.hasDatepicker").find(".ui-datepicker").hide();
            }
        });

        $('.availability-date .input-group-addon i').click(function() {
            var parent = $(this).closest('.availability-date');
            if(parent.attr('id') == 'iwb-availability-checkin'){
                iwb_availability_checkout.find('.ui-datepicker').hide();
            }
            else{
                iwb_availability_checkin.find('.ui-datepicker').hide();
            }
            parent.find('.ui-datepicker').toggle();
        });

        //old version
        /*
        $('.add-bcart').click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('selected')) {
                return;
            }

            var self = $(this),
                    parent = $(this).closest('.iw-room'),
                    room = $(this).data('room'),
                    quantity = parent.find('.room-quantity').val(),
                    start_date = $(this).data('start-date'),
                    end_date = $(this).data('end-date'),
                    services = parent.find('input[name~="premium_service\[\]"]').map(
                    function () {
                        if ($(this).is(':checked')) {
                            return this.value;
                        }
                    }
            ).get();

            $.ajax({
                url: iwbCfg.ajaxUrl,
                data: {action: 'addBookingRoom', room: room, quantity: quantity, services: services, start_date: start_date, end_date: end_date},
                type: "post",
                dataType: "json",
				beforeSend: function (xhr) {
					self.addClass('loading');
				},
                success: function (data) {
                    if (data.code === 0) {
                        parent.find('.room-message').html(data.message);
                    } else if (data.code === 1) {
                        $('.booking-overview .booking-rooms').append(data.html);
                        $('.booking-price').html(data.price);
                        $('.booking-deposit-price').html(data.deposit_price);
                        if (data.has_deposit) {
                            $('.payment-deposit').removeClass('hidden');
                        } else {
                            $('.payment-deposit').addClass('hidden');
                        }
                        $('.total-discount .discount-string').html(data.discount_string);
                        self.addClass('selected').removeClass('loading').html('Selected');
                        parent.addClass('selected');
                    } else if (data.code === 2) {
                        $('.booking-arrival span').html(data.date_start);
                        $('.booking-departure span').html(data.date_end);
                        $('.booking-overview .booking-rooms').html(data.html);
                        $('.booking-price').html(data.price);
                        $('.booking-deposit-price').html(data.deposit_price);
                        if (data.has_deposit) {
                            $('.payment-deposit').removeClass('hidden');
                        } else {
                            $('.payment-deposit').addClass('hidden');
                        }
                        $('.total-discount .discount-string').html(data.discount_string);
                        self.addClass('selected').removeClass('loading').html('Selected');
                        parent.addClass('selected');
                    }
                }
            });

            return false;
        });

        //remove room from booking
        $('.booking-rooms').on('click', '.remove-booking-room', function () {
            var room_id = $(this).data('room');
            var self = $(this);
            $.ajax({
                url: iwbCfg.ajaxUrl,
                data: {action: 'removeBookingRoom', room_id: room_id},
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.code === 1) {
                        self.closest('.booking-room').remove();
                        $('.booking-price').html(data.price);
                        $('.booking-deposit-price').html(data.deposit_price);
                        if (data.has_deposit) {
                            $('.payment-deposit').removeClass('hidden');
                        } else {
                            $('.payment-deposit').addClass('hidden');
                        }
                        $('.total-discount .discount-string').html(data.discount_string);
                        $('a[data-room="' + room_id + '"]').removeClass('selected').html('Select room').closest('.iw-room').removeClass('selected');;
                    }
                }
            });
            return false;
        });

        //update room quantity
        $('.booking-item input.quantity').live('change', function () {
            var item = $(this).parent().find('.remove-room'),
                    room_id = item.data('room'),
                    price = item.data('price'),
                    sumprice = parseInt($('.booking-sum-price label').text()),
                    quantity = $(this).val();
            $.ajax({
                url: iwbCfg.ajaxUrl,
                data: {action: 'updateQuantity', room_id: room_id, quantity: quantity},
                type: "post",
                success: function (data) {
                    $('.booking-sum-price label').text(sumprice + (price * data) + '$');
                }
            });
        });

        $('.button.apply-discountcode').click(function () {
            var self = $(this);
            if ($(this).hasClass('disabled')) {
                return;
            }

            var code = $('input[name="discount_code"]').val();
            $.ajax({
                url: iwbCfg.ajaxUrl,
                data: {action: 'applyDiscountCode', code: code},
                type: "post",
                dataType: "json",
				beforeSend: function (xhr) {
					self.addClass('loading');
				},
                success: function (data) {
                    $('.apply-discount-message').html(data.message);
                    $('.booking-price').html(data.price);
                    $('.booking-deposit-price').html(data.deposit_price);
                    if (data.has_deposit) {
                        $('.payment-deposit').removeClass('hidden');
                    } else {
                        $('.payment-deposit').addClass('hidden');
                    }
                    $('.total-discount .discount-string').html(data.discount_string);
                    $('input[name="discount_code"]').data('discount-code', code);
                    self.addClass('disabled').removeClass('loading');
                }
            });
        });

        $('input[name="discount_code"]').on('keypress keydown keyup', function (e) {
            var discout_code = $('input[name="discount_code"]').data('discount-code');
            if (!discout_code) {
                return;
            }
            var value = $(this).val();
            if (value != discout_code) {
                $('.button.apply-discountcode').removeClass('disabled');
            } else {
                $('.button.apply-discountcode').addClass('disabled');
            }
        });

        $('.button.create-new-booking').click(function () {
            $.ajax({
                url: iwbCfg.ajaxUrl,
                data: {action: 'removeBookingCart'},
                type: "post",
                success: function (data) {
                    var rs = $.parseJSON(data);
                    $('.booking-message').html(rs.message);
                }
            });
        });
         */
    });
})(jQuery);
