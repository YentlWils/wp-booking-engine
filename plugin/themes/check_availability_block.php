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
?>
<div class="iw-check-availability">
        <form action="<?php echo esc_url(get_permalink()); ?>" method="post">
            <div class="form-group">
                <div class="row">
                    <div class="item col-md-3 col-sm-6 col-xs-12">
                        <div class="border">
                            <label><?php esc_html_e('Check-in', 'monalisa'); ?></label>
                            <div class="input-group date availability-date" id="iwb-availability-checkin" data-value="<?php echo date('Y-m-d', $checkin); ?>">
                                <span class="datepicker-holder">
                                    <strong><?php echo date("j", $checkin); ?></strong>/
                                    <?php echo date("F", $checkin); ?>
                                </span>
                                <input name="checkin" type="hidden" value="<?php date('Y-m-d', $checkin); ?>" class="form-control"><span class="input-group-addon"><i class="fa fa-angle-down"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="item check-out col-md-3 col-sm-6 col-xs-12">
                        <div class="border">
                            <label><?php esc_html_e('Check-out','monalisa');?></label>
                            <div class="input-group date availability-date" id="iwb-availability-checkout" data-value="<?php echo date('Y-m-d', $checkout); ?>">
                                <span class="datepicker-holder">
                                    <strong><?php echo date("j", $checkout); ?></strong>/
                                    <?php echo date("F", $checkout); ?>
                                </span>
                                <input name="checkout" type="hidden" value="<?php date('Y-m-d', $checkout); ?>" class="form-control"><span class="input-group-addon"><i class="fa fa-angle-down"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="item col-md-3 col-sm-6 col-xs-12">
                        <div class="guests border">
                            <label><?php echo __('Adult', 'inwavethemes') ;?></label>
                            <input class="guest-numbers" name="adult" value="<?php echo esc_attr($adult); ?>">
                            <span class="select-guest fa fa-angle-down"></span>
                            <div class="dropdown-guest">
                                <ul>
                                    <?php
                                    foreach ($guests as $g) {
                                        echo '<li>' . $g . '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item availability col-md-3 col-sm-6 col-xs-12">
                        <div class="check-availability">
                            <span><?php echo esc_html($title); ?></span>
                            <div class="action-availability">
                                <input type="hidden" value="iwBookingSearchRooms" name="action"/>
                                <input type="hidden" name="filter_room" value="<?php echo is_single() && get_post_type() == 'iw_booking' ? get_the_ID() : '' ?>"/>
                                <button class="input-button iw-bt-effect" type="submit" value="">
                                    <span><?php echo esc_html($text_action); ?></span>
                                    <i class="ion-arrow-right-c"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>