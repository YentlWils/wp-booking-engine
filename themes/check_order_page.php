<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of check_order_page
 *
 * @developer duongca
 */
$ultility = new iwBookingUtility();
$order = new iwBookingOrder();
$ord_code = filter_input(INPUT_GET, 'ordercode');
$email = filter_input(INPUT_GET, 'email');
if ($ord_code && $email) {
    $order->getOrderByCode($ord_code, $email);
}
?>
<div class="iw-check-order-page iw-reservation">
    <div class="iw-check-booking">
        <h3 class="title-block"><?php echo __("Check your booking", 'monalisa') ?></h3>
        <form action="<?php echo get_permalink(); ?>" method="post">
            <div class="form-group">
                <div class="row">
                    <div class="item col-md-4 col-sm-6 col-xs-12">
                        <div class="email">
                            <label><?php echo __("Your Email", 'monalisa') ?></label>
                            <input type="email" placeholder=""
                                   required="required" id="code" class="control" value="<?php echo esc_attr($email); ?>" name="email">
                        </div>
                    </div>
                    <div class="item col-md-5 col-sm-6 col-xs-12">
                        <div class="code">
                            <label><?php echo __("Your booking code", 'monalisa') ?></label>
                            <input type="text" placeholder=""
                                   required="required" id="code" class="control" value="<?php echo esc_attr($ord_code); ?>" name="ordercode">
                        </div>
                    </div>
                    <div class="item check-order col-md-3 col-sm-6 col-xs-12">
                        <div class="check-booking">
                            <span><?php echo __("Have not booked? Book now", 'monalisa') ?></span>
                            <div class="action-check-booking">
                                <input type="hidden" value="iwbCheckOrder" name="action"/>
                                <button class="input-button iw-bt-effect theme-bg" type="submit" value="">
                                    <span><?php echo __("check your booking", 'monalisa') ?></span>
                                    <i class="ion-arrow-right-c"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    if ($order->getId()) :
        $rooms = $order->getRooms();
        $time_start = $order->getTime_start();
        $time_end = $order->getTime_end();
        ?>
        <div class="booking-overview">
            <h3 class="title-block"><?php echo esc_html__('Your booking overview', 'monalisa'); ?></h3>
            <div class="iwb-booking-overview">
                <div class="booking-overview-content">
                    <div class="booking-date">
                        <div class="booking-arrival"><?php echo sprintf(wp_kses(__('Arrival date: <span>%s</span>', 'monalisa'), inwave_allow_tags('span')), isset($time_start) ? date('F, jS, Y', $time_start) : ''); ?></div>
                        <div class="booking-departure"><?php echo sprintf(wp_kses(__('Departure date: <span>%s</span>', 'monalisa'), inwave_allow_tags('span')), isset($time_end) ? date('F,jS,Y', $time_end) : ''); ?></div>
                    </div>
                    <?php
                    $room_class = new IwBookingRooms();
                    $i = 1;
                    foreach ($rooms as $room) :
                        $room_obj = $room_class->getRoomInfo($room['room_id']);
                        $services = $room['services'];
                        $service_titles = array();
                        foreach ($services as $service){
                            $service_titles[] = $service['title'];
                        }
                        ?>
                        <div class="booking-room">
                            <?php $image_url = wp_get_attachment_url(get_post_thumbnail_id($room['room_id'])); ?>
                            <div class="row">

                                <div class="col-md-10 col-sm-10 col-xs-9">
                                    <div class="img-wrap">
                                        <img src="<?php echo esc_url(inwave_resize($image_url, 137, 93)); ?>" alt="" />
                                    </div>
                                    <div class="room-info">
                                        <div class="booking-room-head">
                                            <h3 class="title">
                                                <a href="<?php echo esc_url(get_the_permalink($room['room_id'])); ?>" title="">
                                                    <?php echo get_the_title($room['room_id']); ?>
                                                </a>
                                            </h3>
                                            <?php
                                            if ($room_obj->average_rating) {
                                                $rating_star = (($room_obj->average_rating) / 5) * 100;
                                                $rating_star = number_format($rating_star, 2, '.', '');
                                                $review_count = $room_obj->review_count;
                                                ?>
                                                <div class="rating">
                                                    <div class="star-rating">
                                                        <span class="rating" style="width: <?php echo esc_attr($rating_star) . '%' ?>"></span>
                                                    </div>
                                                    <?php echo sprintf(_n('%d Review', '%d Reviews', $review_count, 'monalisa'), $review_count); ?><?php; ?>
                                                    <div style="clear:both;"></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="room-meta">
                                            <div class="meta-col-left">
                                                <div class="adult"><?php echo sprintf(esc_html__('Adult: %s', 'monalisa'), '<span>' . $room['adult'] . '</span>'); ?></div>
                                                <div class="children"><?php echo sprintf(esc_html__('Children: %s', 'monalisa'), '<span>' . $room['children'] . '</span>'); ?></div>
                                                <?php if($service_titles){?>
                                                    <div class="services"><?php echo sprintf(esc_html__('Service: %s', 'monalisa'), '<span>' . implode(', ', $service_titles) . '</span>'); ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-3">
                                    <div class="price">
                                        <span><?php esc_html_e('Price', 'monalisa'); ?></span><br />
                                        <strong><?php echo ($room['price_with_service'] ? $ultility->getMoneyFormated($room['price_with_service'], $order->currency) : esc_html__('Free', 'monalisa')); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="iw-booking-footer">
                        <?php
                        if ($order->getStatus() == 1 || $order->getStatus() == 4):
                            ?>
                            <span><?php echo esc_attr($order->getCountExpiredTime($order)); ?></span>
                            <form class="submit-wrap" action="<?php echo get_permalink(); ?>" method="POST">
                                <input type="hidden" name="action" value="checkOrderAction"/>
                                <input type="hidden" name="order" value="<?php echo esc_attr($order->getId()); ?>"/>
                                <div class="booking-submit-field">
                                    <button name="order_action" class="button theme-bg" type="submit" value="pay_order"><?php esc_html_e('Pay my booking', 'monalisa'); ?></button>
                                </div>
                                <div class="booking-submit-field">
                                    <button name="order_action" class="button cancel" type="submit" value="cancel_order"><?php esc_html_e('Cancal my booking', 'monalisa'); ?></button>
                                </div>
                            </form>
                        <?php endif;
                        ?>
                        <span>
                            <?php
                            if ($order->getStatus() == 2):
                                esc_attr_e('Your booking order has been completed', 'monalisa');
                            endif;
                            if ($order->getStatus() == 3):
                                esc_attr_e('Your booking order has been cancelled', 'monalisa');
                            endif;
                            ?>
                        </span>
                        <div style="clear: both;"></div>
                    </div>
                </div>
                <?php
                $i ++;
                ?>
            </div>
        </div>
    <?php elseif ($ord_code || $email): ?>
    <div class="booking-overview">
        <?php echo esc_html__('sorry we did not find any results', 'monalisa'); ?>
    </div>
    <?php endif; ?>
</div>

