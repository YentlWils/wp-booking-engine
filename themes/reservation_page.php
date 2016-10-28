<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of check_availability_block
 *
 * @developer duongca
 */
//wp_enqueue_style('bootstrap-datepicker');
//wp_enqueue_script('bootstrap-datepicker');
wp_enqueue_script( 'jquery-ui-datepicker' );
$state = isset($_GET['state']) ? $_GET['state'] : 1;
?>
<div class="iwb-reservation-page" id="iwb-reservation-page">
    <div id="reservation-process-bar" data-state="1" class="reservation-process-bar">
        <span class="choose-date <?php echo $state == 1 ? 'iwb-active' : ''; ?>" data-process="1"><?php echo __('1. Choose date', 'inwavethemes'); ?></span>
        <span class="select-rooms <?php echo $state == 2 ? 'iwb-active' : ''; ?>" data-process="2"><?php echo __('2. Select rooms', 'inwavethemes'); ?></span>
        <span class="make-reservasion" data-process="3"><?php echo __('3. Make a reservation', 'inwavethemes'); ?></span>
        <span class="comfirmation <?php echo $state == 4 ? 'iwb-active' : ''; ?>" data-process="4"><?php echo __('4. Comfirmation', 'inwavethemes'); ?></span>
    </div>
    <div class="row reservation-main">
        <form class="col-md-4 col-sm-12 col-xs-12" id="reservation-bar" data-action="iwb_booking_rooms">
            <div class="reservation-bar-inner">
                <div class="reservation-bar-title"><?php echo __('Your reservation', 'inwavethemes'); ?></div>
                <div id="reservation-summary-form">
                    <?php
                    if($state == 4){
                        $invoice = isset($_GET['invoice']) ? $_GET['invoice'] : 0;
                        if($invoice){
                            $booking = new iwBookingOrder();
                            $booking->getOrder($invoice);
                            if($booking->id){
                                $page_price_data['rooms'] = $booking->getRooms();
                                $page_price_data['total_price'] = $booking->sum_price;
                                $page_price_data['price'] = $booking->sum_price;
                                $page_price_data['discount_price'] = $booking->discount_price;
                                $page_price_data['tax_price'] = $booking->tax_price;
                                $page_price_data['tax'] = $booking->tax;
                                $page_price_data['currency'] = $booking->currency;

                                $path = includeTemplateFile('iw_booking/reservation_page_bar_summary', IWBOOKING_THEME_PATH);
                                include $path;
                            }
                        }
                    }
                    ?>
                </div>
                <div id="reservation-selected-rooms" style="<?php echo $state == 4 ? 'display: none' : '' ?>">
                    <?php
                    if($state == 2){
                        $query_params = array();
                        $query_params['adult-number'][] = $adult;
                        $query_params['children-number'][] = 0;
                        $has_active_class = false;
                        $current_room_id = 1;
                        $query_params['room-id'] = array();
                        $path = includeTemplateFile('iw_booking/reservation_page_bar', IWBOOKING_THEME_PATH);
                        include $path;
                    }?>
                </div>
                <div id="reservation-date-form" style="<?php echo $state == 4 ? 'display: none' : '' ?>">
                    <div class="reservation-form-row row-check-in">
                        <div class="reservation-form-field">
							<div class="reservation-form-field-title"><?php echo __('Check in', 'inwavethemes'); ?></div>
							<div class="reservation-form-field-value">
								<i class="fa fa-calendar-o"></i>
								<input type="text" id="iwb-check-in" placeholder="<?php echo __('Check in', 'inwavethemes'); ?>" class="iwb-datepicker" data-dfm="d M yy" data-value="<?php echo date('Y-m-d', $checkin);?>">
								<input type="hidden" name="checkin" class="iwb-datepicker-alt">
							</div>
                        </div>
                        <div class="reservation-form-field">
                            <div class="reservation-form-field-title"><?php echo __('Night', 'inwavethemes'); ?></div>
                            <select name="night" id="iwb-night">
                                <?php for($i=1; $i<10;$i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="reservation-form-row row-check-out">
                        <div class="reservation-form-field">
                            <div class="reservation-form-field-title"><?php echo __('Check out', 'inwavethemes'); ?></div>
							<div class="reservation-form-field-value">
								<i class="fa fa-calendar-o"></i>
								<input type="text" id="iwb-check-out" placeholder="<?php echo __('Check out', 'inwavethemes'); ?>" class="iwb-datepicker" data-dfm="d M yy" data-value="<?php echo date('Y-m-d', $checkout);?>">
								<input type="hidden" name="checkout" class="iwb-datepicker-alt">
							</div>
                        </div>
                        <div class="reservation-form-field">
                            <div class="reservation-form-field-title"><?php echo __('Room', 'inwavethemes'); ?></div>
                            <select name="room" id="iwb-room-number">
                                <?php for($i=1; $i<10;$i++){
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="reservation-people-amount-wrapper" style="<?php echo $state == 4 ? 'display: none' : '' ?>">
                    <div class="reservation-people-amount">
                        <div class="reservation-people-amount-title"><?php echo __('Room', 'inwavethemes'); ?> <span>1</span></div>
                        <div class="reservation-people-amount-field">
                            <div class="reservation-adult-amount">
                                <span><?php echo __('Adult', 'inwavethemes'); ?></span>
                                <select name="adult-number[]">
                                    <?php for($i = 1 ; $i < 10 ; $i++){
                                        echo '<option value="'.$i.'" '.($i == $adult ? 'selected' : '').'>'.$i.'</option>';
                                    }?>
                                <select>
                            </div>
                            <div class="reservation-children-amount">
                                <span><?php echo __('Children', 'inwavethemes'); ?></span>
                                <select name="children-number[]">
                                    <?php for($i = 0 ; $i < 10 ; $i++){
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }?>
                                <select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reservation-check-availability-btn" style="<?php echo $state != 1 ? 'display: none' : '' ?>">
                    <button type="button" class="check-availability-btn btn theme-bg"><?php echo __('Check availability', 'inwavethemes'); ?></button>
                </div>
            </div>
            <input type="hidden" name="filter-room" value="<?php echo isset($filter_room) ? $filter_room : ''; ?>">
        </form>
        <div class="col-md-8 col-sm-12 col-xs-12" id="iwb-booking-content">
            <?php
            if($state ==4 ){
                $path = includeTemplateFile('iw_booking/reservation_page_completed', IWBOOKING_THEME_PATH);
                if ($path) {
                    include $path;
                }
            }
            else
            {
            ?>
            <div id="iwb-booking-content-inner">
                <?php
                if($state == 1){
                    ?>
                    <div id="iwb-datepicker-range" data-dfm="d M yy">
                    </div>
                <?php }elseif($state == 2) {
                    $room_class = new IwBookingRooms();
                    $paged = 1;
                    $rooms = $room_class->getAvailableRooms($checkin, $checkout, $adult, $children , array(), 1, $filter_room);
                    $path = includeTemplateFile('iw_booking/reservation_page_rooms', IWBOOKING_THEME_PATH);
                    include $path;
                    if($filter_room){
                        $rooms = $room_class->getAvailableRooms($checkin, $checkout, $adult, $children , array(), 1, 0, array($filter_room));
                        if($rooms->have_posts()){
                            echo '<h4>'.__('Can you want', 'monalisa').'</h4>';
                            include $path;
                        }
                    }
                } ?>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>